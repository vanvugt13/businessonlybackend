<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token],'https://vvog.businssonly.nl');
?>
Hallo <?= $user->username ?>,

Klik op de onderstaande link om het mailadres te verifieren:

<?= $verifyLink ?>
