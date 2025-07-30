<?php

namespace backend\controllers;

use backend\models\PengajuanTugasLuar;
use backend\models\PengajuanTugasLuarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PengajuanTugasLuarController implements the CRUD actions for PengajuanTugasLuar model.
 */
class PengajuanTugasLuarController extends Controller
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
     * Lists all PengajuanTugasLuar models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PengajuanTugasLuarSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PengajuanTugasLuar model.
     * @param int $id_tugas_luar Id Tugas Luar
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_tugas_luar)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_tugas_luar),
        ]);
    }

    /**
     * Creates a new PengajuanTugasLuar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanTugasLuar();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_tugas_luar' => $model->id_tugas_luar]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PengajuanTugasLuar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_tugas_luar Id Tugas Luar
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_tugas_luar)
    {
        $model = $this->findModel($id_tugas_luar);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_tugas_luar' => $model->id_tugas_luar]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PengajuanTugasLuar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_tugas_luar Id Tugas Luar
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_tugas_luar)
    {
        $this->findModel($id_tugas_luar)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PengajuanTugasLuar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_tugas_luar Id Tugas Luar
     * @return PengajuanTugasLuar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_tugas_luar)
    {
        if (($model = PengajuanTugasLuar::findOne(['id_tugas_luar' => $id_tugas_luar])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
