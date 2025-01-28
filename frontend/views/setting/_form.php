<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;


/** @var yii\web\View $this */
/** @var frontend\models\Setting $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="setting-form">

    <?php $form = ActiveForm::begin(['layout'=>'horizontal','options'=>['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'mode')->dropDownList([10=>'Test',20=>'Live']) ?>

    <?= $form->field($model, 'beheerderMail_test')->textInput() ?>

    <?= $form->field($model, 'from_test')->textInput() ?>

    <?= $form->field($model, 'to_test')->textInput() ?>

    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'logo_url')->textInput() ?>
    <?= $form->field($model, 'news_url')->textInput() ?>
    <?= $form->field($model, 'sponsor_options_url')->textInput() ?>
    <?= $form->field($model, 'calendar_url')->textInput() ?>
    <?= $form->field($model, 'theme_color')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'background_color')->textInput() ?>
    <?= $form->field($model, 'background_template')->textInput() ?>
    <?= $form->field($model, 'favo_iconField')->fileInput() ?>

    <div class="form-group float-end">
        <?= Html::submitButton('Opslaan', ['class' => 'btn btn-success']) ?>
      
    </div>
    <div class="form-group">
        <?= Html::a('Terug',['/setting/index'], ['class' => 'btn btn-primary']) ?> 
    </div>

    <?php ActiveForm::end(); ?>

</div>
