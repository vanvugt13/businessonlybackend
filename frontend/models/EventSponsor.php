<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
}
