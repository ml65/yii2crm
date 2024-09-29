<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Bid $model */

$this->title = Yii::t('bid', 'Update Bid: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('bid', 'Bids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('bid', 'Update');
?>
<div class="bid-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
