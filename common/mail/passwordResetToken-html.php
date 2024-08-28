<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>hallo <?= Html::encode($user->contactperson) ?>,</p>

    <p>Klik op de link hieronder voor een nieuw wachtwoord</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
<br>
    Met vriendelijke groeten,<br>
Team Businessonly<br>
</div>
