<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gebruikers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nieuwe gebruiker', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Resize images', ['check-images'], ['class' => 'btn btn-success']) ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
      //      ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'username',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            'email:email',
            ['attribute'=>'statusDescription',
            'filter'=>[User::STATUS_ACTIVE=>'Actief',User::STATUS_INACTIVE=>"Moet nog geactiveerd worden"],
            'filterInputOptions' => [
                'placeholder' => 'Search Name..',
                'prompt'=>'Alle statussen',
                'class'=>"form-control",
                
            ],
        ],
            'created_at:datetime',
            //'updated_at',
            //'verification_token',
            [
                'class' => ActionColumn::className(),
                'template'=>'{update}{delete}',
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
