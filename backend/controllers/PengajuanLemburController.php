<?php

namespace backend\controllers;

use backend\models\PengajuanLembur;
use backend\models\PengajuanLemburSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PengajuanLemburController implements the CRUD actions for PengajuanLembur model.
 */
class PengajuanLemburController extends Controller
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
     * Lists all PengajuanLembur models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PengajuanLemburSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PengajuanLembur model.
     * @param int $id_pengajuan_lembur Id Pengajuan Lembur
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_pengajuan_lembur)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_pengajuan_lembur),
        ]);
    }

    /**
     * Creates a new PengajuanLembur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanLembur();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->disetujui_oleh = Yii::$app->user->identity->id;
                $model->save();
                return $this->redirect(['view', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PengajuanLembur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_pengajuan_lembur Id Pengajuan Lembur
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_pengajuan_lembur)
    {
        $model = $this->findModel($id_pengajuan_lembur);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PengajuanLembur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pengajuan_lembur Id Pengajuan Lembur
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_pengajuan_lembur)
    {
        $this->findModel($id_pengajuan_lembur)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PengajuanLembur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pengajuan_lembur Id Pengajuan Lembur
     * @return PengajuanLembur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pengajuan_lembur)
    {
        if (($model = PengajuanLembur::findOne(['id_pengajuan_lembur' => $id_pengajuan_lembur])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
