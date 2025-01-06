<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\SettingSponsortype $model */

$this->title = 'Nieuw sponsortype';
$this->params['breadcrumbs'][] = ['label' => 'Setting Sponsortypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-sponsortype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
