<?php

use common\models\User;
use frontend\models\Post;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var frontend\models\Post $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['id'=>'formpje']); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(User::find()->all(),'id','contactperson'),['prompt'=>"Kies gebruiker"]) ?>

    <?= $form->field($model, 'category')->checkboxList([Post::CATEGORY_EVENTS=>"Evenementen",Post::CATEGORY_NEWS=>"Nieuws",post::CATEGORY_POST=>"Post"]
    ,['onChange'=>'$.pjax.reload({container:"#category", method:"POST",data:{"category":$("#formpje").serialize()}})']) ?>
    <?php
    Pjax::begin(['id'=>'category']);
    if(in_array(Post::CATEGORY_POST,$model->category_array)){
        echo $this->render('_visible_till',[
            'form'=>$form,
            'model'=>$model
        ]);
    }
    echo $this->render('_extra_options',['model'=>$model,'form'=>$form,]);
    Pjax::end();
    ?>

    <?= $form->field($model, 'imageApp')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
