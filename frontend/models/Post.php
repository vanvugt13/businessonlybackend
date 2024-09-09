<?php

namespace frontend\models;

use common\models\User;
use frontend\components\Image;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $title
 * @property string|null $description
 * @property string $category
 * @property resource|null $image
 * @property int|null $visible_till
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int $subscribe_event
 * @property int $subscribe_news
 * @property int $event_date
 * @property string $unique_id
 */
class Post extends \yii\db\ActiveRecord
{

    const CATEGORY_NEWS = 10;
    const CATEGORY_EVENTS = 20;
    const CATEGORY_POST =30;

    CONST VISIBLE_ONE_DAY = 10;
    CONST VISIBLE_WEEK = 20;
    CONST VISIBLE_MONTH = 30;

    public $imageApp;
    public $username;
    public $category_description;
    public $category_array=[];
    public $visible_till_options;
    public $event_date_formatted;
    public $profile_image;

    public $have_seen;

    public $from_appuser = true;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function categories($value= null){
        $categories =  [
            self::CATEGORY_EVENTS=>'Evenementen',
            self::CATEGORY_NEWS=>"Nieuws",
            self::CATEGORY_POST=>'Post',
        ];
        if($value == null){
            return $categories;
        }
        return $categories[$value]??'';
    }
    public static function visibleOptions($value=null){
        $visible_options =  [
            self::VISIBLE_ONE_DAY=>'Een dag',
            self::VISIBLE_WEEK=>"Een week",
            self::VISIBLE_MONTH=>'Een maand',
        ];
        if($value == null){
            return $visible_options;
        }
        return $visible_options[$value]??'';
    }
    /**
     * {@inheritdoc}
     */ 
    public function rules()
    {
        return [
            [['visible_till'],'date','timestampAttribute'=>'visible_till','format'=>'php:Y-m-d'],
            [['event_date_formatted'],'date','timestampAttribute'=>'event_date','format'=>'php:Y-m-d'],

            [['user_id','title','description'],'required'],
            [['user_id', 'created_at', 'updated_at','have_seen','subscribe_event','subscribe_news'], 'integer'],
            [['description', 'image','username','category_description','unique_id'], 'string'],
            [['imageApp'],'file'],
            [['title'], 'string', 'max' => 100],
            [['category','visible_till','visible_till_options'],'safe'],
        ];
    }

    public function getFilename(){
        return Image::getImageUrl($this);
    }

    public function afterSave($insert,$changedAttributes){
        parent::afterSave($insert,$changedAttributes);
        if($insert){
            if($this->from_appuser){
                $notify_users =ArrayHelper::map(User::find()->select('id')->where(['!=','id',$this->user->id])->all(),'id','id');
            }
            else{
                $notify_users =ArrayHelper::map(User::find()->select('id')->all(),'id','id');
            }
            $type = PushSubscribers::TYPE_POST;
            if(in_array(Post::CATEGORY_EVENTS,$this->category_array)){
                $type = PushSubscribers::TYPE_EVENT;
            }
            if(in_array(Post::CATEGORY_NEWS,$this->category_array)){
                $type = PushSubscribers::TYPE_NEWS;
            }
            (new PushSubscribers())->sendNotification(
                $notify_users
                ,$this->title,$this->description,type:$type);
        }
        
    }
    public function afterFind()
    {
        if(!empty($this->visible_till)){
            $this->visible_till = date("Y-m-d",$this->visible_till);
        }
      //  echo $this->category;
        $this->category_array = unserialize($this->category);
        if(!is_array($this->category_array)){
            $this->category_array=[];
        }
        $this->event_date_formatted = date("Y-m-d",$this->event_date);
    //    echo "deze";
        
    }

    public function convertValue(){
        if(empty($this->category)){
            $this->category=[];
        }
        if(is_array($this->category)){
            $this->category_array = $this->category;
            $this->category = serialize($this->category);
        }

    }

    public function revertValue(){
        $this->category = unserialize($this->category);
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->convertValue();

            switch($this->visible_till_options){
                case Post::VISIBLE_ONE_DAY:
                $this->visible_till = strtotime(date("Y-m-d",strtotime("+ 1 day")));
                break;
                case Post::VISIBLE_WEEK:
                $this->visible_till = strtotime(date("Y-m-d",strtotime("+ 1 week")));
                break;
                case Post::VISIBLE_MONTH:
                $this->visible_till = strtotime(date("Y-m-d",strtotime("+ 1 month")));
                break;
            }
            
            if(!in_array(self::CATEGORY_POST,$this->category_array)){
                $this->visible_till = null;
            }
            if(!count($this->category_array)){
                $this->addError('category','U moet minimaal één categorie selecteren');
            }
            
            if(isset($this->imageApp) AND !$this->imageApp instanceof UploadedFile ){
                $this->imageApp = UploadedFile::getInstance($this,'imageApp');
                $this->unique_id=null;
            }
            if(isset($this->imageApp) !== null AND $this->imageApp instanceof UploadedFile) { $this->unique_id=null; $this->image = base64_encode(file_get_contents($this->imageApp->tempName)); }
            else{
                $this->image = $this->oldAttributes['image']??null;
            }
            if(isset($this->image)){
                $postimage=  $this->postImage;
                if(!$postimage){
                    $postimage = new PostImage();
                    $postimage->post_id = $this->id;
                }
                $postimage->image = $this->image;
                $postimage->save();
                $this->image = null;
            }
            return !$this->hasErrors();
        }
        $this->revertValue();
        return false;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Toegevoegd door',
            'title' => 'Titel',
            'description' => 'Omschrijving',
            'category' => 'Categorie',
            'category_description'=>'Categorie omschrijving',
            'image' => 'Plaatje',
            'username'=>'Gebruikersnaam',
            'visible_till' => 'Zichtbaar tot',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'visible_till_options'=>"Zichtbaar tot",
            'imageApp'=>'Afbeelding',
            'subscribe_event'=>'Button aanmelden',
            'subscribe_news'=>'Button aanmelden',
            'event_date_formatted'=>'Datum event',

        ];
    }

    public function getPostImage(){
        return $this->hasOne(PostImage::class,['post_id'=>'id']);
    }

    public function getUser(){
        return $this->hasOne(User::class,['id'=>'user_id']);
    }
}
