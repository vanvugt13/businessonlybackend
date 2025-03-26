<?php

use frontend\models\Setting;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\SettingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Instelling';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php echo Setting::find()->one()?"":Html::a('Maak instelling', ['create'], ['class' => 'btn btn-success']) ?>
        <?php echo Html::a('Genereer manifest file', ['create-manifest'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute'=>'mode',
            'value'=>function($model){
                return match($model->mode){
                    10=>"TEST",
                    20=>"Live",
                };
            }
            
        ],
            'beheerderMail_test:ntext',
            'from_test:ntext',
            'to_test:ntext',
            'title:ntext',
            'application_name:ntext',
            'theme_color',
            'logo_url',
            'home_team',
            'news_url',
            'calendar_url',
            'sponsor_options_url',

            'background_color',
            'background_template',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::class,
                'template'=>'{update}',
                'urlCreator' => function ($action, Setting $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
