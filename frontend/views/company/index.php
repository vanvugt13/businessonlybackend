<?php

use frontend\models\Company;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\CompanySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Bedrijven';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nieuw bedrijf', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Haal gegevens op van website', ['fetchdata'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Resize images', ['check-images'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            'description:ntext',
           ['attribute'=>'loginaccount',
           'value'=>function($model){
            return wordwrap($model->loginaccount,30,"\r\n",TRUE);
           }],
            'created_at:datetime',
            //'updated_at',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Company $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
