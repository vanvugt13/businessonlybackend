<?php
namespace frontend\components;

use common\models\User;
use frontend\models\EventSponsor;
use frontend\models\SettingEmail;
use yii\helpers\Html;

class Mailing extends Mailer{

    const MAILING_NEW_SPONSOR = 10;
    CONST MAILING_NEW_USER = 20;
    CONST MAILING_FORGOT_PASSWORD = 30;
    CONST MAILING_ACTIVATE_ACCOUNT = 40;

   // private Mailer $mailer;
   // public static function getMailing(int $mailing,Mailer $mailer){
  //      $this->mailer = $mailer;
   // }

    public $model;
    
    public EventSponsor $eventSponsorModel;
    public User $userModel;
    public function __construct(int $mailing,$model){

        $this->model = $model;
        $this->setProperties($mailing);
    }
    private function setProperties(int $mailing){
        match($mailing){
            self::MAILING_NEW_SPONSOR => $this->setNewSponsorMail(),
        };

    }

    private function setNewSponsorMail(){
        $this->eventSponsorModel = $this->model;
        $this->userModel = $this->eventSponsorModel->user;
        /** @var \frontend\models\SettingEmail $settingEmail */
        $settingEmail = SettingEmail::find()->where(['mail_type'=>self::MAILING_NEW_SPONSOR])->one();
        if($settingEmail){
            $this->subject = $settingEmail->subject;
            $this->body = $settingEmail->body;
            $this->to = $settingEmail->to;
            $this->from = $settingEmail->from;
        }else{
            $this->setDefaultSponsorMail();
        }
        
    }

    private function setDefaultSponsorMail(){
        $this->to = $this->getBeheerderMail();
        $this->from = 'sales@businessonly.nl';
        $this->subject = 'Nieuwe sponsor aangemeld';
        $this->body = 'Sponsor '.$this->model->user->contactperson .' ('.$this->model->user->username.') heeft zich aangemeld voor '.$this->model->getSponsorType().' sponsoring wedstrijd. Klik op de '.Html::a('link','https://www.vvog.nl/teams/senioren/1/wedstrijd/'.$this->model->event_id,['target'=>'_new']);
    }

    public function send(){
        //Parse the data if necessary
        $this->to = $this->parseText($this->to);
        $this->from = $this->parseText($this->from);
        $this->subject = $this->parseText($this->subject);
        $this->body = $this->parseText($this->body);
        return parent::send();
    }
    
    private function parseText($textToParse){
        $text = str_replace('{{UserEmailAddress}}',$this->userModel->emailAddress,$textToParse);
        $text = str_replace('{{UserContactPerson}}',$this->userModel->contactperson,$textToParse);
        $text = str_replace('{{UserUserName}}',$this->userModel->username,$textToParse);
        $text = str_replace('{{SponsorTypeDescription}}',$this->eventSponsorModel->getSponsorType(),$textToParse);
        return $text;
    }
}