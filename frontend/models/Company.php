<?php

namespace frontend\models;

use frontend\components\Image;
use PHPHtmlParser\Dom;
use yii\web\UploadedFile;
use yii\behaviours\TimeStampBehaviour;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string|null $name
 ** @property string|null $description
 *  @property string|null $url
 *  @property string|null $company_url
 * @property resource|null $logo
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string $unique_id
 */
class Company extends \yii\db\ActiveRecord
{

    public $logoApp;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }
    

    public function behaviours(){
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
            [['description', 'logo','url','company_url','unique_id'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            //$image = UploadedFile::getInstanceByName("image");
           
            
            $logo = UploadedFile::getInstance($this, 'logo');
            if($logo !== null)  $this->logo = base64_encode(file_get_contents($logo->tempName));
            else{
                $this->logo = $this->oldAttributes['logo']??null;
            }
            if(isset($this->logoApp))  $this->logo = $this->logoApp;
            return true;
        }
        return false;
    }

    public function getFilename(){
        return Image::getImageUrl($this);
    }

    public function fetchImage($url){
        $dom = new Dom;
        $dom->loadFromUrl($url);

        $links = $dom->find('.sponsor-logo-lg');
        //print_r($links);
        foreach ($links as $link) {
            $image = 'https://www.vvog.nl/'.$link->getAttribute('src');
            $this->logoApp = base64_encode(file_get_contents($image));
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
        $this->description = trim($final_text);
      
        if(!$this->save()){
            
            print_r($this->getErrors());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Naam',
            'description' => 'Omschrijving',
            'logo' => 'Logo',
            'created_at' => 'Aangemaakt op',
            'updated_at' => 'Gewijzigd op',
            'company_url'=>"Bedrijfswebsite",
            'url'=>"URL sponsorpagina VVOG",
        ];
    }
}
