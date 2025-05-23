<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

/**
 * This is the model class for table "setting".
 *
 * @property int $id
 * @property int|null $mode Default TEST= 10, (20 = live)
 * @property string|null $beheerderMail_test
 * @property string|null $from_test
 * @property string|null $to_test
 * @property string|null $title
 * @property string|null $theme_color
 * @property string|null $background_color
 * @property string|null $background_template
 * @property string|null $background_image
 * @property string|null $logo_url
 * @property string|null $logo_blob
 * @property string|null $favo_icon
 * @property string|null $home_team Name of the team detected if match is home match
 * @property string|null $news_url
 * @property string|null $calendar_url
 * @property string|null $sponsor_options_url Link to show in the dialog if sponsor type is used
 * @property string|null $application_name
 * 
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Setting extends \yii\db\ActiveRecord
{
    public $favo_iconField;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mode', 'created_at', 'updated_at'], 'integer'],
            [['beheerderMail_test', 'from_test', 'to_test','application_name', 'title','logo_url','background_color','background_template','background_image','home_team','sponsor_options_url','news_url','calendar_url'], 'string'],
            [['theme_color'], 'string', 'max' => 10],
            [['logo_blob','favo_icon','favo_iconField'],'safe'],
        ];
    }

    public static function customConfigApiData() :array{
        /** @var \frontend\models\Setting $setting */
        $setting = Setting::find()->one();
        $array = [
            'title'=>$setting->title,
            'theme_color'=>$setting->theme_color,
            'logo_url'=>$setting->logo_url,
            'background_color'=>$setting->background_color,
            'background_template'=>$setting->background_template,
            'sponsorTypes'=>SettingSponsortype::getApiData(),
            'home_team'=>$setting->home_team,
            'background_image'=>$setting->background_image,
            'news_url'=>$setting->news_url,
            'sponsor_options_url'=>$setting->sponsor_options_url,
            'calendar_url'=>$setting->calendar_url,
        ];

        return $array;
    }

    public static function createManifest(){
        /** @var \frontend\models\Setting $setting */
        $setting = self::find()->one();
        $manifest = [
            'name'=>$setting->application_name,
            'background_color'=>$setting->background_color,
            'display'=>'standalone',
            'scope'=>'./',
            'start_url'=>'/',
            'icons'=>[
                [
                    "src"=> "images/icon-72x72.png",
                    "sizes"=> "72x72",
                    "type"=> "image/png",
                    "purpose"=> "maskable any"
                ],
                [
                    "src"=> "images/icon-96x96.png",
                    "sizes"=> "96x96",
                    "type"=> "image/png",
                    "purpose"=> "maskable any"
                ],
                [
                    "src"=> "images/icon-128x128.png",
                    "sizes"=> "128x128",
                    "type"=> "image/png",
                    "purpose"=> "maskable any"
                ],
                [
                    "src"=> "images/icon-144x144.png",
                    "sizes"=> "144x144",
                    "type"=> "image/png",
                    "purpose"=> "maskable any"
                ],
                [
                    "src"=> "images/icon-152x152.png",
                    "sizes"=> "152x152",
                    "type"=> "image/png",
                    "purpose"=> "maskable any"
                ],
                [
                    "src"=> "images/icon-192x192.png",
                    "sizes"=> "192x192",
                    "type"=> "image/png",
                    "purpose"=> "maskable any"
                ],
                [
                    "src"=> "images/icon-384x384.png",
                    "sizes"=> "152x152",
                    "type"=> "image/png",
                    "purpose"=> "maskable any"
                ],
                [
                    "src"=> "images/icon-512x512.png",
                    "sizes"=> "512x512",
                    "type"=> "image/png",
                    "purpose"=> "maskable any"
                ],
            ]
        ];

        $manifestPath = Yii::getAlias('@webroot').'/manifest.webmanifest';
        return file_put_contents($manifestPath,json_encode($manifest));
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mode' => 'Mode',
            'beheerderMail_test' => 'Emailadres beheerder (in Testmode)',
            'from_test' => 'Afzenderadres (in testmode)',
            'to_test' => 'Aan-adres (in testmode)',
            'title' => 'Titel',
            'theme_color' => 'Kleurcode bv (#fa849d)',
            'created_at' => 'Aangemaakt op',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $favoIcon = UploadedFile::getInstance($this,'favo_iconField');
            if($favoIcon instanceof UploadedFile){
                $this->favo_icon = file_get_contents($favoIcon->tempName);
            }
            return true;
        }
        
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert,$changedAttributes);
        if(!empty($this->favo_icon)){
            $this->generateIcons();
        }
    }

    private function generateIcons(){
        $tempPath = Yii::getAlias('@runtime').'/tempIcon.png';
        $imagesToCreate=[72,96,128,144,152,192,384,512];
        foreach($imagesToCreate as $neededSize){
            $tempPathResized = Yii::getAlias('@webroot').'/images/icon-'.$neededSize.'x'.$neededSize.'.png';
            //if(file_exists($tempPathResized)) continue;
            file_put_contents($tempPath,$this->favo_icon);
            $source = imagecreatefrompng($tempPath);
            list($width, $height) = getimagesize($tempPath);
            
            // Define new dimensions (200x200 pixels)
            $newWidth = $neededSize;
            $newHeight = $neededSize;
            
            // Create a new image
            $thumb = imagecreatetruecolor($newWidth, $newHeight);
            
            // Resize
            imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            
            // Save the resized image
            imagejpeg($thumb, $tempPathResized, 100);
        }
        

    }
}
