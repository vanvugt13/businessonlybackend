<?php

namespace frontend\components;

use common\models\User;
use frontend\models\EventSponsor;
use frontend\models\SettingEmail;
use Yii;
use yii\helpers\Html;

class Mailing extends Mailer
{

    const MAILING_NEW_SPONSOR = 10;
    const MAILING_NEW_USER = 20;
    const MAILING_FORGOT_PASSWORD = 30;
    const MAILING_ACTIVATE_ACCOUNT = 40;

    // private Mailer $mailer;
    // public static function getMailing(int $mailing,Mailer $mailer){
    //      $this->mailer = $mailer;
    // }

    public $model;

    public EventSponsor $eventSponsorModel;
    public User $userModel;
    public function __construct(int $mailing, $model)
    {

        $this->model = $model;
        $this->setProperties($mailing);
    }
    private function setProperties(int $mailing)
    {
        match ($mailing) {
            self::MAILING_NEW_SPONSOR => $this->setNewSponsorMail(),
        };
    }

    private function setNewSponsorMail()
    {
        $this->eventSponsorModel = $this->model;
        $this->userModel = $this->eventSponsorModel->user;
        /** @var \frontend\models\SettingEmail $settingEmail */
        $settingEmail = SettingEmail::find()->where(['mail_type' => self::MAILING_NEW_SPONSOR])->one();
        if ($settingEmail) {
            $this->subject = $settingEmail->subject;
            $this->body = $settingEmail->body;
            $this->to = $settingEmail->to;
            $this->from = $settingEmail->from;
        } else {
            $this->setDefaultSponsorMail();
        }
    }

    private function setDefaultSponsorMail()
    {
        $this->to = $this->getBeheerderMail();
        $this->from = 'sales@businessonly.nl';
        $this->subject = 'Nieuwe sponsor aangemeld';
        $this->body = 'Sponsor ' . $this->model->user->contactperson . ' (' . $this->model->user->username . ') heeft zich aangemeld voor ' . $this->model->getSponsorType() . ' sponsoring wedstrijd. Klik op de ' . Html::a('link', 'https://www.vvog.nl/teams/senioren/1/wedstrijd/' . $this->model->event_id, ['target' => '_new']);
    }

    public function send()
    {
        //Parse the data if necessary
        $this->to = $this->parseText($this->to);
        $this->from = $this->parseText($this->from);
        $this->subject = $this->parseText($this->subject);
        $this->body = $this->parseText($this->body);
        return parent::send();
    }

    private function getParseVariables()
    {
        return [
            '{{UserEmailAddress}}',
            '{{UserContactPerson}}',
            '{{UserUserName}}',
            '{{SponsorTypeDescription}}',
            '{{VerificationLink}}',
            '{{AppUrl}}',
        ];
    }
    private function parseText($textToParse)
    {
        foreach ($this->getParseVariables() as $var) {
            if (str_contains($textToParse, $var)) {
                $var = 'replaceVariable' . str_replace("{", "", str_replace("}", "", $var));
                if (is_callable([$this, $var])) {
                    $textToParse = $this->$var($textToParse);
                }
            }
        }
    }

    private function replaceVariableUserEmailAddress($textToParse)
    {
        return str_replace('{{UserEmailAddress}}', $this->userModel->email, $textToParse);
    }

    private function replaceVariableUserContactPerson($textToParse)
    {
        return str_replace('{{UserContactPerson}}', $this->userModel->contactperson, $textToParse);
    }

    private function replaceVariableUserUserName($textToParse)
    {
        return str_replace('{{UserUserName}}', $this->userModel->username, $textToParse);
    }

    private function replaceVariableSponsorTypeDescription($textToParse)
    {
        return str_replace('{{SponsorTypeDescription}}', $this->eventSponsorModel->getSponsorType(), $textToParse);
    }

    private function replaceVariableVerificationLink($textToParse)
    {
        $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['/site/verify-email', 'token' => $this->userModel->verification_token]);
        $link = Html::a(Html::encode('Activeer nu'), $verifyLink);
        $textToParse = str_replace('{{VerificationLink}}', $link, $textToParse);
        return $textToParse;
    }

    private function replaceVariableAppUrl($textToParse)
    {
        $appUrl = Yii::$app->params['appUrl'];
        $link = Html::a(Html::encode($appUrl), $appUrl);
        $textToParse = str_replace('{{AppUrl}}', $link, $textToParse);
        return $textToParse;
    }
}
