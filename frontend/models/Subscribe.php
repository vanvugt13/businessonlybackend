<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "subscribe".
 *
 * @property int $id
 * @property int|null $news_id
 * @property int|null $post_id
 * @property int|null $event_id
 * @property int|null $user_id
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Subscribe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscribe';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function add($user_id,$news_id=null,$event_id=null){
        if($news_id !== null){
            if(($existingmodel = self::find()->where(['user_id'=>$user_id,'news_id'=>$news_id])->one()) !== null){
                $existingmodel->delete();
                return true;
            }
            $model = new self();
            $model->user_id =$user_id;
            $model->news_id = $news_id;
        }
        if($event_id !== null){
            if(($existingmodel = self::find()->where(['user_id'=>$user_id,'event_id'=>$event_id])->one()) !== null){
                $existingmodel->delete();
                return true;
            }
            $model = new self();
            $model->user_id =$user_id;
            $model->event_id = $event_id;
        }
        
        return $model->save();
        
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id', 'post_id', 'event_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'news_id' => 'News ID',
            'post_id' => 'Post ID',
            'event_id' => 'Event ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUser(){
        return $this->hasOne(User::class,['id'=>'user_id']);
    }
}
