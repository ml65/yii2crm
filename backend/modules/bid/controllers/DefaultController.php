<?php

namespace backend\modules\bid\controllers;

use common\models\Bid;
use common\models\search\BidSearch;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for Bid model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Bid models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BidSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        /** @var $user User */
        $user = yii::$app->user->identity;

        if ($user->isAdmin()) {
            if ($this->request->isPost) {
                $data = $this->request->post('Bid');
                $model = $this->findModel($data['id']);
                if (isset($data['status'])) {
                    $model->status = $data['status'];
                }
                if ($model->validate()) {
                    $model->save();
                } else {
                    //TODO! отработка некорректных данных на входе index.php
                }
            }
        }

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'user'         => $user,
        ]);
    }

    /**
     * Displays a single Bid model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Bid model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Bid();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Bid model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $a = $model->errors;
                echo "<pre>";
                var_dump($a); exit;
            }
        }

        /** @var Bid $model */
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Bid model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Bid model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Bid the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bid::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('bid', 'The requested page does not exist.'));
    }

    public function actionExport()
    {
        $data = "Номер заявки;Имя клиента;Наименование заявки;Наименование товара;Телефон;Время создания заявки;Статус;Комментарий;Цена\r\n";
        $model = Bid::find()->indexBy('id')->all();
        /** @var  $row Bid */
        foreach ($model as $row) {
            $data .= $row->id.
                ';' . $row->username .
                ';' . $row->title .
                ';' . $row->getProductName() .
                ';' . $row->phone .
                ';' . $row->created_at .
                ';' . $row->getStatusTitle() .
                ';' . $row->comment .
                ';' . $row->price .
                "\r\n";
        }
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export_'.date('Y-m-d').'.csv"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($data) );
        echo $data;
        //Yii::app()->end();
        exit;
    }
}
