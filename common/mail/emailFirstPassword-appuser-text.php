<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
Hallo <?= $user->username ?>,

<p>Hierbij ontvangt u het wachtwoord:</p>
    <p>Username:<?=$user->username?></P>
    <p>Password:<?=$user->password?></P>
