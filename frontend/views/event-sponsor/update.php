<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\EventSponsor $model */

$this->title = 'Update Event Sponsor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Event Sponsors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="event-sponsor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
