<?php

use yii\helpers\Html;
?>
Het wachtwoord wijzigen is geslaagd. U kunt inloggen op de app.

<?=Html::a('Klik hier om naar de app te gaan',Yii::$app->params["appUrl"],['class'=>'btn btn-success']);?>