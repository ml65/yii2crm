<?php

use common\models\Bid;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Bid $model */
/** @var string $class */
/** @var string $message */
/** @var ActiveForm $form */
?>
<div class="alert-<?php echo $class; ?>">
    <?= $message; ?>
</div>
<div class="site-bid">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'title') ?>
        <?= $form->field($model, 'product_id')->dropDownList(Bid::getAviableProducts()) ?>
        <?= $form->field($model, 'price') ?>
        <?= $form->field($model, 'comment') ?>
        <?= $form->field($model, 'status')->hiddenInput(['value' => 0])->label(false) ?>
        <?= $form->field($model, 'phone') ?>
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('bid', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-bid -->
