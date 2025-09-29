<?php

namespace frontend\models;

use frontend\components\Mailing;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "setting_email".
 *
 * @property int $id
 * @property int|null $mail_type
 * @property string|null $subject
 * @property string|null $body
 * @property string|null $from
 * @property string|null $to
 * @property string|null $cc
 * @property string|null $bcc
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SettingEmail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting_email';
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
            [['mail_type', 'created_at', 'updated_at'], 'integer'],
            [['subject', 'body', 'from', 'to', 'cc', 'bcc'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mail_type' => 'Mail type',
            'subject' => 'Onderwerp',
            'body' => 'Bericht',
            'from' => 'Afzender',
            'to' => 'Aan',
            'cc' => 'Cc',
            'bcc' => 'Bcc',
            'created_at' => 'Aangemaakt op',
            'updated_at' => 'Updated At',
        ];
    }

    public static array $mailTypes = [
        Mailing::MAILING_NEW_USER=>'Aanmeldingsmail',
        Mailing::MAILING_NEW_USER_WITHOUT_ACTIVATION=>'Aanmeldingsmail zonder activatie (standaard)',
        Mailing::MAILING_FORGOT_PASSWORD=>"Wachtwoord vergeten",
        Mailing::MAILING_NEW_SPONSOR=>"Nieuwe sponsor optie",
    ];
}
