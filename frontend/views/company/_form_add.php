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



    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label("Bedrijfsnaam") ?>

  
    <?= $form->field($model, 'url')->textInput()->label('Website sponsor pagina VVOG') ?>




</div>
