<?php

use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\Company $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="company-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php if(!$model->isNewRecord) echo $form->field($model, 'description')->textarea(['rows' => 6,'readonly'=>'readonly']) ?>
    <?php //$form->field($model, 'contactperson_id')->dropDownList(ArrayHelper::map(User::find()->all(),'id','username')) ?>
    <?= $form->field($model, 'url')->textInput() ?>
    <?= $form->field($model, 'company_url')->textInput() ?>
    <?php //= $form->field($model, 'logo')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
