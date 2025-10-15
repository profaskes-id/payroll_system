<?php

namespace backend\controllers;

use backend\models\RekapLembur;
use backend\models\RekapLemburSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RekapLemburController implements the CRUD actions for RekapLembur model.
 */
class RekapLemburController extends Controller
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
     * Lists all RekapLembur models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RekapLemburSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RekapLembur model.
     * @param int $id_rekap_lembur Id Rekap Lembur
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_rekap_lembur)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_rekap_lembur),
        ]);
    }

    /**
     * Creates a new RekapLembur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new RekapLembur();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_rekap_lembur' => $model->id_rekap_lembur]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RekapLembur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_rekap_lembur Id Rekap Lembur
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_rekap_lembur)
    {
        $model = $this->findModel($id_rekap_lembur);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_rekap_lembur' => $model->id_rekap_lembur]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RekapLembur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_rekap_lembur Id Rekap Lembur
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_rekap_lembur)
    {
        $this->findModel($id_rekap_lembur)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RekapLembur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_rekap_lembur Id Rekap Lembur
     * @return RekapLembur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_rekap_lembur)
    {
        if (($model = RekapLembur::findOne(['id_rekap_lembur' => $id_rekap_lembur])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
