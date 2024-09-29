<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Bid $model */

$this->title = Yii::t('bid', 'Create Bid');
$this->params['breadcrumbs'][] = ['label' => Yii::t('bid', 'Bids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bid-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
