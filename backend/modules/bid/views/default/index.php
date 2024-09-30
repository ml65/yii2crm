<?php

use common\models\Bid;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\search\BidSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\User $user title */

$this->title = Yii::t('bid', 'Bids');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bid-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('bid', 'Создать заявку'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('bid', 'Экспортировать список'), ['export'], ['class' => 'btn btn-info']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'title',
            'product_id',
            'phone',
            //'comment:ntext',
            'price',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'label' => 'Статус',
                /* @var $model Bid */
                'value' => function ($model, $key, $index, $column) {
                    ob_start();
                    $form = ActiveForm::begin(['method' => 'post']);
                    echo $form->field($model, 'id')->hiddenInput()->label(false);
                    echo $form->field($model, 'status')->dropDownList(
                        Bid::getAviableStatus(),
                        [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()'
                        ]
                    )->label(false);
                    ActiveForm::end();
                    $str = ob_get_contents();
                    ob_end_clean();
                    return $str;
                },
                'filter' => Html::activeDropDownList(
                    $searchModel, 'status',Bid::getAviableStatus('all')
                )
            ],
            'created_at',
            //'updated_at',
            /*
             *  В ТЗ отсутствует требование редактирования и удаления заявки
             */
             [
                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, Bid $model, $key, $index, $column) {
                'urlCreator' => function ($action, Bid $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
