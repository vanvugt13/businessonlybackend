<?php

use common\models\User;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = User::baseAppLoginUrl();
$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['/site/verify-email', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p>Hallo,</p>

    <p>Wat leuk dat je een account hebt aangevraagd voor de business app van VVOG.<br><br>

Om er zeker van te zijn dat we het juiste email adres van je hebben, klik hieronder op de activatie button en je ontvangt direct daarna een email met instructies en inlog gegevens.
<br><br>
 

<?= Html::a(Html::encode('Activeer nu'), $verifyLink) ?>
<br><br>

 

Deze businessclub app is een product van businessonly. Voor vragen kun je contact opnemen via sales@businessonly.nl of kijk op www.businessonly.nl

<br><br>

Met vriendelijke groeten,
<br>
Team Onboarding</p>
<p>Businessonly</p>
</div>
