<?php

use frontend\models\SettingEmail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\SettingEmailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Email opmaak';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-email-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nieuwe mail layout', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute'=>'mail_type',
            'value'=>function(SettingEmail $model){
                return $model::$mailTypes[$model->mail_type]??"Onbekend";
            }
        ],
            'subject:ntext',
            'body:ntext',
            'from:ntext',
            'to:ntext',
           // 'cc:ntext',
            //'bcc:ntext',
            'created_at:datetime',
            //'updated_at',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, SettingEmail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
