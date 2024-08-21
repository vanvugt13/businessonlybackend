<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\PushSubscribers $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="push-subscribers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'raw_data')->textInput() ?>

    <?= $form->field($model, 'endpoint')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'expirationDate')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'keys')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
