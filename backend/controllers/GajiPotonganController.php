<?php

namespace backend\controllers;

use backend\models\GajiPotongan;
use backend\models\GajiPotonganSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GajiPotonganController implements the CRUD actions for GajiPotongan model.
 */
class GajiPotonganController extends Controller
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
     * Lists all GajiPotongan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new GajiPotonganSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GajiPotongan model.
     * @param int $id_gaji_potongan Id Gaji Potongan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_gaji_potongan)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_gaji_potongan),
        ]);
    }

    /**
     * Creates a new GajiPotongan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new GajiPotongan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_gaji_potongan' => $model->id_gaji_potongan]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing GajiPotongan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_gaji_potongan Id Gaji Potongan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_gaji_potongan)
    {
        $model = $this->findModel($id_gaji_potongan);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_gaji_potongan' => $model->id_gaji_potongan]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GajiPotongan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_gaji_potongan Id Gaji Potongan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_gaji_potongan)
    {
        $this->findModel($id_gaji_potongan)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GajiPotongan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_gaji_potongan Id Gaji Potongan
     * @return GajiPotongan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_gaji_potongan)
    {
        if (($model = GajiPotongan::findOne(['id_gaji_potongan' => $id_gaji_potongan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
