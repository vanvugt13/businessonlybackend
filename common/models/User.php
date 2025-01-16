<?php

namespace common\models;

use DOMDocument;
use DOMXPath;
use frontend\components\Image;
use frontend\components\Mailer;
use frontend\models\Company;
use frontend\models\PasswordResetRequestForm;
use frontend\models\SignupForm;
use frontend\models\UserImage;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;
use PHPHtmlParser\Dom;
use yii\helpers\Html;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $token
 * @property blob $image
 * @property string $description
 * @property string $company_name
 * @property string $phone_number
 * @property int $company_id
 * @property string $contactperson
 * @property string $url
 * @property string $unique_id
 * @property int $type System user or App user (20 = app user, 10 = systemuser)
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const TYPE_SYSTEMUSER = 10;
    const TYPE_APPUSER = 20;
    const TYPE_BUSINESSONLY_ADMIN = 30;

    public $password;
    public $chat_user_id;
    public $last_message;
    public $last_message_datetime;
    public $total_unseen;
    public $imageApp;
    public string $statusDescription='';

    public $lastPostSeenDate;
    public $skipActivate = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id'],'required','on'=>['create','update']],
            [['company_id','type'],'number'],
            [['unique_id'],'string'],
            [['password','token','username','auth_key','description','company_name','email','phone_number','url','imageApp','contactperson','chat_user_id','last_message','last_message_datetime','statusDescription','skipActivate'],'safe'],
            [['image'],'file'],
            [
                'email', 'unique',
                'targetClass' => '\common\models\User',         
                'message' => 'Dit mailadres is al een keer gekozen.'
            ],
            [
                'username', 'unique',
                'targetClass' => '\common\models\User',         
                'message' => 'Deze gebruikersnaam is al een keer gekozen.'
            ],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }


    public static function baseUrl(){

        return Yii::$app->request->hostInfo.'/endpoints/';
    }
    public function attributeLabels()
    {
        return [
            'contactperson'=>"Naam contactpersoon",
            'company_id'=>'Bedrijfsnaam',
            'username'=>'Gebruikersnaam/Email',
            'phone_number'=>'Telefoonnummer',
            'created_at'=>'Aangemaakt op',
            'status'=>'Status',
            'email'=>'Email / Gebruikersnaam',
            'skipActivate'=>"Sla activeringsmail over",
        ];
    }

    public function getFilename(){
        return Image::getImageUrl($this);
    }
    public function getCompany(){
        return $this->hasOne(Company::class,['id'=>'company_id']);
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findBackendUserByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE,'type'=>[User::TYPE_SYSTEMUSER,User::TYPE_BUSINESSONLY_ADMIN]]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function defaultMail(){
        return 'jeroen2@icloud.com';
    }

    public function sendResetPasswordLink():bool{
      //  $this->email = User::defaultMail();
        $model = new PasswordResetRequestForm();
        $model->email = $this->email;
        if ($model->validate()) {
            if ($model->sendEmail()) {
               return true;
            }

           return false;
        }
    }
    public function sendPassword()
    {
   //     $this->email = $this->to;
        //TODO variable subject
        if(!$this->password){
            $this->password = rand(10000,1000000);
            $this->setPassword($this->password);
           
        }
        $subject = 'Welkom en log direct in!';
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailFirstPassword-appuser-html', 'text' => 'emailFirstPassword-appuser-text'],
                ['user' => $this]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'Sales | Business only'])
            ->setTo($this->email)
            ->setSubject($subject)
            ->send();
    }



    private static function bodyNewUser(User $user){
      
        $verifyLink = User::baseUrl();
$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['/site/verify-email', 'token' => $user->verification_token]);
$html =<<<HTML
<div class="verify-email">
<p>Hallo 
HTML; 
    $html .= $user->contactperson.',';
    $html .= <<<HTML
    <p>De sponsorcommissie van VVOG heeft een account voor je aangemaakt voor de business app van VVOG.<br><br>

Om er zeker van te zijn dat we het juiste email adres van je hebben, klik hieronder op de activatie button en je ontvangt direct daarna een email met instructies en inlog gegevens.
<br><br>
HTML;
 

$html.= Html::a(Html::encode('Activeer nu'), $verifyLink);

$html .= <<<HTML
<br><br>

 

Deze businessclub app is een product van businessonly. Voor vragen kun je contact opnemen via sales@businessonly.nl of kijk op www.businessonly.nl

<br><br>

Met vriendelijke groeten,
<br>
Team Onboarding</p>
<p>Businessonly</p>
</div>
HTML;
        return $html;

    }
    public function sendEmail()
    {

        $mailer = new Mailer();
        $mailer->to = $this->email;
        $mailer->subject = 'Activeer jouw account voor de VVOG app ';
        $mailer->body = self::bodyNewUser($this);
        $mailer->from = [Yii::$app->params['supportEmail'] => 'Sales | Business only'];
        return  $mailer->send();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert,$changedAttributes);
        if($insert){
            $userimage=  $this->userImage;
            if(!$userimage){
                $userimage = new UserImage();
                $userimage->user_id = $this->id;
            }
            $userimage->image = $this->imageApp;
            $userimage->save();
        }
    }


    public function getUserImage(){
        return $this->hasOne(UserImage::class,['user_id'=>'id']);
    }
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            //$image = UploadedFile::getInstanceByName("image");
            if($this->isNewRecord)
                $this->generateEmailVerificationToken();
            $this->auth_key = $this->generateAuthKey();
            $image = UploadedFile::getInstance($this, 'image');
           
            if($image !== null){ $this->image = base64_encode(file_get_contents($image->tempName)); $this->unique_id=null;} 
            else{
                $this->image = $this->oldAttributes['image']??null;
            }
            if(isset($this->imageApp)){ $this->unique_id=null;  $this->image = $this->imageApp;}
            if($insert){
                $this->email = $this->username;
            }

            if(isset($this->image) AND !$insert){
                $userimage=  $this->userImage;
                if(!$userimage){
                    $userimage = new UserImage();
                    $userimage->user_id = $this->id;
                }
                $userimage->image = $this->image;
                $userimage->save();
                $this->image = null;
            }else{
                $this->imageApp = $this->image;
            }
            return true;
        }
        return false;
    }

    public function fetchImage($url){
        $dom = new Dom;
        $dom->loadFromUrl($url);

        $links = $dom->find('.sponsor-logo-lg');
        //print_r($links);
        foreach ($links as $link) {
            $image = 'https://www.vvog.nl/'.$link->getAttribute('src');
            $this->imageApp = base64_encode(file_get_contents($image));
        }
    }

    public function fetchData(){
        $url = $this->url;
        
        $this->fetchImage($url);
        $page = file_get_contents($url);
        $start_pos = strpos($page, '<div class="margin-top-50">');
        $text1 = substr($page, $start_pos);
        $end_pos = strpos($text1, '</div>');
        $final_text = substr($text1, 27, $end_pos - 27);
        $this->description = $final_text;
        if(!$this->save()){
            print_r($this->getErrors());
        }
    }
}
