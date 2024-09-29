<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
/** @var yii\web\View                    $this */
/** @var common\models\search\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider     $dataProvider */
/** @var common\models\User              $user title */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <!--- ?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?--->
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php if ($user->isAdmin()) { ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'email:email',
            'username',
            //'auth_key',
            'password_hash',
            //'password_reset_token',
            [
                'attribute' => 'status',
                'format' => 'html',
                'label' => 'Статус',
                'value' => function ($model) {
                    return User::getStatusTitle($model->status);
                },
                'filter' => Html::activeDropDownList(
                    $searchModel, 'status',User::getAviableStatus('all')
                )
            ],
            [
                'attribute' => 'role',
                'format' => 'raw',
                'label' => 'Роль',
                /* @var $model User */
                'value' => function ($model, $key, $index, $column) {
                    ob_start();
                    $form = ActiveForm::begin(['method' => 'post']);
                    echo $form->field($model, 'id')->hiddenInput()->label(false);
                    echo $form->field($model, 'role')->dropDownList(
                            User::getAviableRoles(),
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
                    $searchModel, 'role',User::getAviableRoles('all')
                )
            ],
            //'created_at',
            //'updated_at',
            //'verification_token',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
    <?php } else { ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                //'id',
                'email:email',
                'username',
                //'auth_key',
                'password_hash',
                //'password_reset_token',
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'label' => 'Статус',
                    'value' => function ($model) {
                        return User::getStatusTitle($model->status);
                    },
                    'filter' => Html::activeDropDownList(
                        $searchModel, 'status',User::getAviableStatus('all')
                    )
                ],
                [
                    'attribute' => 'role',
                    'format' => 'html',
                    'label' => 'Роль',
                    'value' => function ($model) {
                        return User::getRoleTitle($model->role);
                    },
                    'filter' => Html::activeDropDownList(
                        $searchModel, 'role',User::getAviableRoles('all')
                    )
                ],
                //'created_at',
                //'updated_at',
                //'verification_token',
                /*[
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, User $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],*/
            ],
        ]); ?>
    <?php } ?>
    <?php Pjax::end(); ?>

</div>
