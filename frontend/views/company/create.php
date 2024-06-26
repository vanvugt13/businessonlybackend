<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Company $model */

$this->title = 'Nieuw bedrijf';
$this->params['breadcrumbs'][] = ['label' => 'Bedrijven', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
