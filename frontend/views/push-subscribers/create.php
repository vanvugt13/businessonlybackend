<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\PushSubscribers $model */

$this->title = 'Create Push Subscribers';
$this->params['breadcrumbs'][] = ['label' => 'Push Subscribers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="push-subscribers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
