<?php

namespace backend\controllers;

use backend\models\MasterHaribesar;
use backend\models\TotalHariKerja;
use backend\models\TotalHariKerjaSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TotalHariKerjaController implements the CRUD actions for TotalHariKerja model.
 */
class TotalHariKerjaController extends Controller
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
                    'class' => \yii\filters\AccessControl::className(),
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
     * Lists all TotalHariKerja models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TotalHariKerjaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TotalHariKerja model.
     * @param int $id_total_hari_kerja Id Total Hari Kerja
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_total_hari_kerja)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_total_hari_kerja),
        ]);
    }

    /**
     * Creates a new TotalHariKerja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TotalHariKerja();

        $holidaysGroupedByMonth = TotalHariKerja::getHolidaysByMonth();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_total_hari_kerja' => $model->id_total_hari_kerja]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'holidaysGroupedByMonth' => $holidaysGroupedByMonth,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TotalHariKerja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_total_hari_kerja Id Total Hari Kerja
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_total_hari_kerja)
    {
        $model = $this->findModel($id_total_hari_kerja);
        $holidaysGroupedByMonth = TotalHariKerja::getHolidaysByMonth();

        if ($this->request->isPost && $model->load($this->request->post())) {

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Berhasil Ditambahkan');
                return $this->redirect(['view', 'id_total_hari_kerja' => $model->id_total_hari_kerja]);
            } else {
                Yii::$app->session->setFlash('success', 'Data Gagal Ditambahkan');
            }
        }

        return $this->render('update', [
            'holidaysGroupedByMonth' => $holidaysGroupedByMonth,
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TotalHariKerja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_total_hari_kerja Id Total Hari Kerja
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_total_hari_kerja)
    {
        $this->findModel($id_total_hari_kerja)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TotalHariKerja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_total_hari_kerja Id Total Hari Kerja
     * @return TotalHariKerja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_total_hari_kerja)
    {
        if (($model = TotalHariKerja::findOne(['id_total_hari_kerja' => $id_total_hari_kerja])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
