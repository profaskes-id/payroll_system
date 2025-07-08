<?php

namespace backend\controllers;

use backend\models\Bagian;
use backend\models\BagianSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BagianController implements the CRUD actions for Bagian model.
 */
class BagianController extends Controller
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
     * Lists all Bagian models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BagianSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bagian model.
     * @param int $id_bagian Id Bagian
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_bagian)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_bagian),
        ]);
    }

    /**
     * Creates a new Bagian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Bagian();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['/perusahaan/view', 'id_perusahaan' => $model->id_perusahaan]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Bagian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_bagian Id Bagian
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_bagian)
    {
        $model = $this->findModel($id_bagian);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['/perusahaan/view', 'id_perusahaan' => $model->id_perusahaan]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Bagian model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_bagian Id Bagian
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_bagian)
    {
        $model = $this->findModel($id_bagian);
        $model->delete();

        return $this->redirect(['/perusahaan/view', 'id_perusahaan' => $model->id_perusahaan]);
    }

    /**
     * Finds the Bagian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_bagian Id Bagian
     * @return Bagian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_bagian)
    {
        if (($model = Bagian::findOne(['id_bagian' => $id_bagian])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
