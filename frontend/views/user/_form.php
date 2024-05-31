<?php

use common\models\User;
use frontend\models\Company;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'username')->textInput() ?>
    <?= $form->field($model, 'contactperson')->textInput() ?>
    
    <?= $form->field($model, 'company_id')->dropDownList(ArrayHelper::map(Company::find()->all(),'id','name'),['prompt'=>'maak keuze']); ?>
    <?php
    // echo $this->render('/company/_form_add',[
    //     'model'=>new Company(),
    //      'form'=>$form,
    //     ]);
    ?> 

    <?= $form->field($model, 'phone_number')->textInput() ?>
    <?php // $form->field($model, 'email')->textInput() ?>


    <?php // $form->field($model, 'status')->dropDownList([User::STATUS_ACTIVE=>"Active",User::STATUS_INACTIVE=>"In-actief",User::STATUS_DELETED=>"Deleted"]) ?>

    <?php if(!$model->isNewRecord)echo Html::a('Reset wachtwoord',['/site/request-password-reset']);?>
    

    
   

    <div class="form-group float-end">
        <?= Html::a('Terug',['/user/index'], ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
