<?php

namespace frontend\modules\bid\controllers;

use common\models\Bid;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `bid` module
 */
class DefaultController extends Controller
{

//    public function actionAdd()
    public function actionIndex()
    {
        $message = '';
        $class = 'success';
        $model = new Bid();
        if ($model->load(Yii::$app->request->post())) {
            $a = Yii::$app->request->post();
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    if ($model->save()) {
                        $message = "Создана заявка #".$model->id;
                        $model   = new Bid();
                    } else {
                        $message = "ERROR! Заявка не создана!";
                        $class   = 'danger';
                    }
                } else {
                    echo "<pre>";
                    var_dump($model->errors); exit;
                    $message = "ERROR! Заявка не создана!";
                    $class   = 'danger';
                }
            }

        }

        return $this->render('bid', [
            'model'   => $model,
            'message' => $message,
            'class'   => $class
        ]);
    }
}
