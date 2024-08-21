<?php
namespace frontend\components;

use Yii;

class Mailer {

    public $body;
    public $to;
    public $from;
    public $subject;
    public array $errors=[];

    public static function checkDefaults(){
        $error = false;
        $error_message=[];
        if((new Mailer())->getFrom()===null){
            $error =true;
            $error_message[] = '"defaultFrom" niet goed geset';
        }
        if((new Mailer())->getBeheerderMail()===null){
            $error = true;
            $error_message[] = '"beheerderMail" niet goed geset';
        }
        if($error){
            echo  implode(',',$error_message);

        }
        return !$error;
 
    }
    public function send(){

        if(!$this->checkValues()){
            return false;
        }
        $this->to = 'sponsorcommissie@vvog.nl';
        $this->from = $this->getFrom();
        $mailer = Yii::$app->mailer->compose()
        ->setTo($this->to)
        ->setFrom($this->from)
        ->setSubject($this->subject)
        ->setHtmlBody($this->body);

        return $mailer->send();
        

    }


    public function getFrom(){
        if(strtolower(Yii::$app->params['mode']) == 'live'){
            if(!isset(Yii::$app->params['defaultFrom'])){
                Yii::warning('defaultFrom not set');
                return null;
            }
            return Yii::$app->params['defaultFrom'];
        }
        if(!isset(Yii::$app->params['defaultFrom_test'])){
            Yii::warning('defaultFrom_test not set');
            return null;
        }
        return Yii::$app->params['defaultFrom_test'];
    }
    public function getBeheerderMail(){
        if(strtolower(Yii::$app->params['mode']) == 'live'){
            if(!isset(Yii::$app->params['beheerderMail'])){
                Yii::warning('beheerderMail not set');
                return null;
            }
            return Yii::$app->params['beheerderMail'];
        }
        if(!isset(Yii::$app->params['beheerderMail_test'])){
            Yii::warning('beheerderMail_test not set');
            return null;
        }
        return Yii::$app->params['beheerderMail_test'];
    }
    private function checkValues(){
        if(empty($this->to) || empty($this->from)){
            $this->setError('To of From is leeg');
            return false;
        }
    }

    private function setError($message){
        $this->errors[] = $message;
    }
}