<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;


/** @var yii\web\View $this */
/** @var frontend\models\SettingEmail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="setting-email-form">

    <?php $form = ActiveForm::begin(['layout'=>'horizontal']); ?>

    <?= $form->field($model, 'mail_type')->dropDownList($model::$mailTypes,['prompt'=>"Maak keuze"]) ?>

    <?= $form->field($model, 'subject')->textInput() ?>

    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'from')->textInput() ?>

    <?= $form->field($model, 'to')->textInput() ?>

    <?= $form->field($model, 'cc')->textInput() ?>

    <?= $form->field($model, 'bcc')->textInput() ?>

    <div class="form-group float-end">
        <?= Html::submitButton('Opslaan', ['class' => 'btn btn-success']) ?>
      
    </div>
    <div class="form-group">
        <?= Html::a('Terug',['/setting-email/index'], ['class' => 'btn btn-primary']) ?> 
    </div>
    <?php ActiveForm::end(); ?>

</div>
