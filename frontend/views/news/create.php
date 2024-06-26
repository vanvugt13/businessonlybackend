<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\News $model */

$this->title = 'Nieuw nieuwsitem';
$this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
