<?php

namespace frontend\models;

use common\models\User;
use frontend\components\Mailer;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;

/**
 * This is the model class for table "event_sponsor".
 *
 * @property int $id
 * @property int|null $event_id
 * @property int|null $user_id
 * @property int|null $sponsor_type
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class EventSponsor extends \yii\db\ActiveRecord
{
    const SPONSOR_TYPE_GAME = 10;
    const SPONSOR_TYPE_BAL = 20;
    const SPONSOR_TYPE_SCORE = 30;
    

    public $username;
    public $event_description;
    public $sponsor_typedescription;

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_sponsor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'user_id', 'sponsor_type', 'created_at', 'updated_at'], 'integer'],
            [['username','event_description','sponsor_typedescription'],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Event ID',
            'user_id' => 'User ID',
            'sponsor_type' => 'Sponsor Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUser(){
        return $this->hasOne(User::class,['id'=>'user_id']);
    }

    public function getEvent(){
        return $this->hasOne(Post::class,['id'=>'event_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert,$changedAttributes);
        if($insert){
            $this->informBackoffice();
        }
    }

    private function getSponsorType(){
        return  match($this->sponsor_type){
            self::SPONSOR_TYPE_BAL => "Bal",
            self::SPONSOR_TYPE_GAME=>"Wedstrijd",
            self::SPONSOR_TYPE_SCORE=>"Doelpunt",
        };
    }

    private function informBackoffice(){
        $mailer = new Mailer();
        $mailer->to = $mailer->getBeheerderMail();//Yii::$app->params['beheerderMail']??null;
        $mailer->from = 'eventsponsor@businessonly.nl';
        $mailer->subject = 'Nieuwe sponsor aangemeld';
        $mailer->body = 'Sponsor '.$this->user->username .' heeft zich aangemeld voor '.$this->getSponsorType().' sponsoring wedstrijd. Klik op de '.Html::a('link','https://www.vvog.nl/teams/senioren/1/wedstrijd/'.$this->event_id,['target'=>'_new']);
        $mailer->send();
    }
}
