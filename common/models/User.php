<?php

namespace common\models;

use DOMDocument;
use DOMXPath;
use frontend\components\Image;
use frontend\models\Company;
use frontend\models\SignupForm;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;
use PHPHtmlParser\Dom;

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
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public $password;
    public $chat_user_id;
    public $last_message;
    public $last_message_datetime;
    public $imageApp;

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
            [['password','token','username','auth_key','description','company_name','email','phone_number','url','imageApp','contactperson','chat_user_id','last_message','last_message_datetime'],'safe'],
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
        return 'https://socialsbackend.familie-van-vugt.nl';
    }
    public function attributeLabels()
    {
        return [
            'contactperson'=>"Naam contactpersoon",
            'company_id'=>'Bedrijfsnaam',
            'username'=>'Gebruikersnaam',
            'phone_number'=>'Telefoonnummer',
        ];
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


    public function sendEmail()
    {
        
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $this]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Uw account is aangemaakt voor ' . Yii::$app->name)
            ->send();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert,$changedAttributes);
      
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
           
            if($image !== null)  $this->image = base64_encode(file_get_contents($image->tempName));
            else{
                $this->image = $this->oldAttributes['image']??null;
            }
            if(isset($this->imageApp))  $this->image = $this->imageApp;
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
