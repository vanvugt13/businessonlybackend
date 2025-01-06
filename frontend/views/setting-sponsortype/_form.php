<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;


/** @var yii\web\View $this */
/** @var frontend\models\SettingSponsortype $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="setting-sponsortype-form">

    <?php $form = ActiveForm::begin(['layout'=>"horizontal"]); ?>

    <?= $form->field($model, 'type')->dropDownList($model::$sponsorTypes,['prompt'=>"Maak keuze"]) ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'maximumAllowedSubscribers')->textInput() ?>

    <?= $form->field($model, 'order')->textInput() ?>


    <div class="form-group float-end">
        <?= Html::submitButton('Opslaan', ['class' => 'btn btn-success']) ?>
      
    </div>
    <div class="form-group">
        <?= Html::a('Terug',['/setting-sponsortype/index'], ['class' => 'btn btn-primary']) ?> 
    </div>

    <?php ActiveForm::end(); ?>

</div>
