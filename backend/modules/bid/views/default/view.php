<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Bid $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('bid', 'Bids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bid-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('bid', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('bid', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('bid', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'title',
            [
                'label' => Yii::t('bid', 'Продукт'),
                'value' => $model->getProductName()
            ],
            'phone',
            'comment:ntext',
            'price',
            [
                'label' => Yii::t('bid', 'Статус'),
                'value' => $model->getStatusTitle(),
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
