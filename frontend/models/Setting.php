<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Setting extends \yii\db\ActiveRecord
{
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
            [['beheerderMail_test', 'from_test', 'to_test', 'title'], 'string'],
            [['theme_color'], 'string', 'max' => 10],
        ];
    }

    public static function customConfigApiData() :array{
        $setting = Setting::find()->one();
        $array = [
            'title'=>$setting->title,
            'theme_color'=>$setting->theme_color,
            'sponsorTypes'=>SettingSponsortype::getApiData(),
        ];

        return $array;
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
