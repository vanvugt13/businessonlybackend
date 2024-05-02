<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p>Hallo <?= Html::encode($user->username) ?>,</p>

    <p>Hierbij ontvangt u het wachtwoord:</p>
    <p>Username:<?=$user->username?></P>
    <p>Password:<?=$user->password?></P>
    
</div>
