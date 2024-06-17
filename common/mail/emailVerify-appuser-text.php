<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = Yii::$app->request->hostInfo.Yii::$app->urlManager->createUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
Hallo <?= $user->username ?>,

Klik op de onderstaande link om het mailadres te verifieren:

<?= $verifyLink ?>
