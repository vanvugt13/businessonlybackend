<?php

namespace frontend\models;

use frontend\components\Notification;
use frontend\components\NotificationObject;


use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property int|null $vvog_id
 * @property string|null $title
 * @property string|null $intro
 * @property string|null $text
 * @property string|null $date
 * @property string|null $category
 * @property string|null $image
 * @property string|null $url
 */
class News extends \yii\db\ActiveRecord
{
    CONST NEWS_URL = 'https://www.vvog.nl/website/vvog/services/bc/nieuws.php';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vvog_id'], 'integer'],
            [['intro', 'text', 'image', 'url'], 'string'],
            [['date'], 'safe'],
            [['title', 'category'], 'string', 'max' => 255],
        ];
    }

    public function checkNewItems(){
        $homepage = file_get_contents(self::NEWS_URL);
        $data = json_decode($homepage);
       
        $vvog_ids = ArrayHelper::map(News::find()->all(),'vvog_id','vvog_id');
        foreach($data as $item){
            if(!in_array($item->id,$vvog_ids)){
                //new item
                $news_item = new News();
                $news_item->vvog_id = $item->id;
                $news_item->title = $item->titel;
                $news_item->intro = $item->intro;
                $news_item->image   =   $item->afbeelding;
                $news_item->url     =   $item->url;
                $news_item->text    =   $item->tekst;
                $news_item->category = $item->categorie;
                $news_item->date = $item->datum;
                if($news_item->save()){
                    $notificationObject =new NotificationObject();
                    $notificationObject->title = $news_item->title;
                    $notificationObject->text = $news_item->text;
                    $notificationObject->url=   $news_item->url;
                    $notificationObject->image = $news_item->image;
                    
                    //(new Notification())->sendNotification([$notificationObject]);
                    echo "nieuwe notificatie!";
                    //send message to phones
                }
                else{
                    echo "opslaan mislukt";
                    print_R($news_item->getErrors());
                    exit;
                }
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vvog_id' => 'Vvog ID',
            'title' => 'Title',
            'intro' => 'Intro',
            'text' => 'Text',
            'date' => 'Date',
            'category' => 'Category',
            'image' => 'Image',
            'url' => 'Url',
        ];
    }
}
