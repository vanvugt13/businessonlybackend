<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
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
            [['description', 'image','username','category_description'], 'string'],
            [['imageApp'],'file'],
            [['title'], 'string', 'max' => 100],
            [['category','visible_till'],'safe'],
        ];
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
        $this->category_array = $this->category;
        $this->category = serialize($this->category);
    }

    public function revertValue(){
        $this->category = unserialize($this->category);
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->convertValue();

            if(!in_array(self::CATEGORY_POST,$this->category_array)){
                $this->visible_till = null;
            }
            if(!count($this->category_array)){
                $this->addError('category','U moet minimaal één categorie selecteren');
            }
            
           
            if($this->image !== null AND $this->image instanceof UploadedFile)  $this->image = base64_encode(file_get_contents($this->image->tempName));
            else{
                $this->image = $this->oldAttributes['image']??null;
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

    public function getUser(){
        return $this->hasOne(User::class,['id'=>'user_id']);
    }
}
