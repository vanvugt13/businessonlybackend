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
 * @property string|null $background_color
 * @property string|null $background_template
 * @property string|null $logo_url
 * @property string|null $logo_blob
 * 
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
            [['beheerderMail_test', 'from_test', 'to_test', 'title','logo_url','background_color','background_template'], 'string'],
            [['theme_color'], 'string', 'max' => 10],
            [['logo_blob'],'safe'],
        ];
    }

    public static function customConfigApiData() :array{
        /** @var \frontend\models\Setting $setting */
        $setting = Setting::find()->one();
        $array = [
            'title'=>$setting->title,
            'theme_color'=>$setting->theme_color,
            'logo_url'=>$setting->logo_url,
            'background_color'=>$setting->background_color,
            'background_template'=>$setting->background_template,
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
            'created_at' => 'Aangemaakt op',
            'updated_at' => 'Updated At',
        ];
    }
}
