<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "setting_sponsortype".
 *
 * @property int $id
 * @property int|null $type
 * @property string|null $name
 * @property string|null $price
 * @property int|null $maximumAllowedSubscribers
 * @property int|null $order
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SettingSponsortype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting_sponsortype';
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
            [['type', 'maximumAllowedSubscribers', 'order', 'created_at', 'updated_at'], 'integer'],
            [['name', 'price'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Naam',
            'price' => 'Prijs',
            'maximumAllowedSubscribers' => 'Maximum aantal sponsoren',
            'order' => 'Volgorde',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static array $sponsorTypes = [
        10=>'Bal sponsor',
        20=>'Doelpunt sponsor',
        30=>'Wedstrijd sponsor',
        40=>'Timeout sponsor',
    ];
}
