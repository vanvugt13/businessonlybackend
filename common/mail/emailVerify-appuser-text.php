<?php

/** @var yii\web\View $this */

use common\models\User;
use yii\helpers\Html;

/** @var common\models\User $user */

$verifyLink = User::baseAppLoginUrl().Yii::$app->urlManager->createUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
Hallo <?=$user->contactperson?>,

<p>Wat leuk dat je een account hebt aangevraagd voor de business app van VVOG.<br><br>

Om er zeker van te zijn dat we het juiste email adres van je hebben, klik hieronder op de activatie button en je ontvangt direct daarna een email met instructies en inlog gegevens.
<br><br>
 

<?= Html::a(Html::encode($verifyLink), $verifyLink) ?>
<br><br>

 

Deze businessclub app is een product van businessonly.nl. Voor vragen kun je contact opnemen via sales@businessonly.nl of kijk op www.businessonly.nl

<br><br>

Met vriendelijke groeten
<br><br>
Team Onboarding</p>
<p>Businessonly.nl</p>
