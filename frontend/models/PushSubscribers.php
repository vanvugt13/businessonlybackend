<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "push_subscribers".
 *
 * @property int $id
 * @property resource|null $raw_data
 * @property string|null $endpoint
 * @property string|null $expirationDate
 * @property resource|null $keys
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PushSubscribers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'push_subscribers';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['raw_data', 'endpoint', 'expirationDate', 'keys'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'raw_data' => 'Raw Data',
            'endpoint' => 'Endpoint',
            'expirationDate' => 'Expiration Date',
            'keys' => 'Keys',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
