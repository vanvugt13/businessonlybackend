<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\SettingSponsortype $model */

$this->title = 'Update Setting Sponsortype: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Setting Sponsortypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="setting-sponsortype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
