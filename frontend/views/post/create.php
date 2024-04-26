<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Post $model */

$this->title = 'Nieuw Post item';
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
