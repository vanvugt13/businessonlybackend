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
 * @property int|null $news_id_customer
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
            [['news_id_customer'], 'integer'],
            [['intro', 'text', 'image', 'url'], 'string'],
            [['date'], 'safe'],
            [['title', 'category'], 'string', 'max' => 255],
        ];
    }

    public function checkNewItems(){
        $homepage = file_get_contents(self::NEWS_URL);
        $data = json_decode($homepage);
       
        $news_ids_customer = ArrayHelper::map(News::find()->all(),'news_id_customer','news_id_customer');
        foreach($data as $item){
            if(!in_array($item->id,$news_ids_customer)){
                //new item
                $news_item = new News();
                $news_item->news_id_customer = $item->id;
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
            'news_id_customer' => 'News ID of Customer',
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
