<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
     <div class='col-md-3'>
         <?= $form->field($model, 'username')->textInput() ?>
     </div>
    <div class='col-md-3'>
        <?= $form->field($model, 'email')->textInput() ?>
    </div>
    <div class='col-md-3'>
        <?= $form->field($model, 'status')->dropDownList( User::getAviableStatus()) ?>
    </div>
    <div class='col-md-3'>
        <?= $form->field($model, 'role')->dropDownList( User::getAviableRoles()) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
