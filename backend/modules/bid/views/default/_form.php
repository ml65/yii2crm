<?php

use common\models\Bid;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Bid $model */
/** @var yii\widgets\ActiveForm $form */
/** @var  $errors  */
?>

<div class="bid-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_id')->dropDownList(Bid::getAviableProducts()) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(Bid::getAviableStatus()) ?>
    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('bid', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
