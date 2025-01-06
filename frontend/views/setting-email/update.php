<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\SettingEmail $model */

$this->title = 'Wijzig Email opmaak';
$this->params['breadcrumbs'][] = ['label' => 'Setting Emails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="setting-email-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
