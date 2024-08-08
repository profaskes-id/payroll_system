<?php

namespace backend\controllers;

use backend\models\PengalamanKerja;
use backend\models\PengalamanKerjaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PengalamanKerjaController implements the CRUD actions for PengalamanKerja model.
 */
class PengalamanKerjaController extends Controller
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
     * Lists all PengalamanKerja models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PengalamanKerjaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PengalamanKerja model.
     * @param int $id_pengalaman_kerja Id Pengalaman Kerja
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_pengalaman_kerja)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_pengalaman_kerja),
        ]);
    }

    /**
     * Creates a new PengalamanKerja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengalamanKerja();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // dd($model);
                return $this->redirect('/panel/karyawan/view?id_karyawan=' . $model->id_karyawan);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PengalamanKerja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_pengalaman_kerja Id Pengalaman Kerja
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_pengalaman_kerja)
    {
        $model = $this->findModel($id_pengalaman_kerja);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PengalamanKerja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pengalaman_kerja Id Pengalaman Kerja
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_pengalaman_kerja)
    {
        $data = $this->findModel($id_pengalaman_kerja);
        $data->delete();

        return $this->redirect(['/karyawan/view', 'id_karyawan' => $data->id_karyawan]);
    }

    /**
     * Finds the PengalamanKerja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pengalaman_kerja Id Pengalaman Kerja
     * @return PengalamanKerja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pengalaman_kerja)
    {
        if (($model = PengalamanKerja::findOne(['id_pengalaman_kerja' => $id_pengalaman_kerja])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
