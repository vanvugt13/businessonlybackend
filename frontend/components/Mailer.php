<?php
namespace frontend\components;

use Yii;

class Mailer {

    public $body;
    public $to;
    public $from;
    public $subject;
    public array $errors=[];

    public function send(){

        if(!$this->checkValues()){
            return false;
        }
        $this->to = 'sponsorcommissie@vvog.nl';
        $this->from = 'sales@businessonly.nl';
        $mailer = Yii::$app->mailer->compose()
        ->setTo($this->to)
        ->setFrom($this->from)
        ->setSubject($this->subject)
        ->setHtmlBody($this->body);

        return $mailer->send();
        

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