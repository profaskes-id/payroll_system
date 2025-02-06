<?php

namespace backend\controllers;

use backend\models\ShiftKerja;
use backend\models\ShiftKerjaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShiftKerjaController implements the CRUD actions for ShiftKerja model.
 */
class ShiftKerjaController extends Controller
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
     * Lists all ShiftKerja models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ShiftKerjaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShiftKerja model.
     * @param int $id_shift_kerja Id Shift Kerja
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_shift_kerja)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_shift_kerja),
        ]);
    }

    /**
     * Creates a new ShiftKerja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ShiftKerja();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_shift_kerja' => $model->id_shift_kerja]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ShiftKerja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_shift_kerja Id Shift Kerja
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_shift_kerja)
    {
        $model = $this->findModel($id_shift_kerja);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_shift_kerja' => $model->id_shift_kerja]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ShiftKerja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_shift_kerja Id Shift Kerja
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_shift_kerja)
    {
        $this->findModel($id_shift_kerja)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ShiftKerja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_shift_kerja Id Shift Kerja
     * @return ShiftKerja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_shift_kerja)
    {
        if (($model = ShiftKerja::findOne(['id_shift_kerja' => $id_shift_kerja])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
