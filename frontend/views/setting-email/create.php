<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\SettingEmail $model */

$this->title = 'Nieuwe Email opmaak';
$this->params['breadcrumbs'][] = ['label' => 'Setting Emails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-email-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
