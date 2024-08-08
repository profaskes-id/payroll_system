<?php

namespace backend\controllers;

use backend\models\DataPekerjaan;
use backend\models\DataPekerjaanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DataPekerjaanController implements the CRUD actions for DataPekerjaan model.
 */
class DataPekerjaanController extends Controller
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
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all DataPekerjaan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DataPekerjaanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DataPekerjaan model.
     * @param int $id_data_pekerjaan Id Data Pekerjaan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_data_pekerjaan)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_data_pekerjaan),
        ]);
    }

    /**
     * Creates a new DataPekerjaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new DataPekerjaan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_data_pekerjaan' => $model->id_data_pekerjaan]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DataPekerjaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_data_pekerjaan Id Data Pekerjaan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_data_pekerjaan)
    {
        $model = $this->findModel($id_data_pekerjaan);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_data_pekerjaan' => $model->id_data_pekerjaan]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DataPekerjaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_data_pekerjaan Id Data Pekerjaan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_data_pekerjaan)
    {
        $this->findModel($id_data_pekerjaan)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DataPekerjaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_data_pekerjaan Id Data Pekerjaan
     * @return DataPekerjaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_data_pekerjaan)
    {
        if (($model = DataPekerjaan::findOne(['id_data_pekerjaan' => $id_data_pekerjaan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
