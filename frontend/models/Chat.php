<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property int $id
 * @property int|null $source_user_id
 * @property string|null $message
 * @property int|null $destination_user_id
 * @property int|null $created_at
 * @property int|null $seen
 */
class Chat extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['source_user_id', 'destination_user_id', 'created_at', 'seen'], 'integer'],
            [['message'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'source_user_id' => 'Source User ID',
            'message' => 'Message',
            'destination_user_id' => 'Destination User ID',
            'created_at' => 'Created At',
            'seen'=>'Gezien',
        ];
    }
}
