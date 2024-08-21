<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\PushSubscribers $model */

$this->title = 'Update Push Subscribers: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Push Subscribers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="push-subscribers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
