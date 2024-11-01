<?php

namespace backend\controllers;

use backend\models\PengajuanDinas;
use backend\models\PengajuanDinasSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PengajuanDinasController implements the CRUD actions for PengajuanDinas model.
 */
class PengajuanDinasController extends Controller
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
     * Lists all PengajuanDinas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, 1, $tahun));
        $lastDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, date('t', mktime(0, 0, 0, $bulan, 1, $tahun)), $tahun));

        $tgl_mulai = $firstDayOfMonth;
        $tgl_selesai = date('Y-m-d');
        $searchModel = new PengajuanDinasSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $tgl_mulai, $tgl_selesai);

        if ($this->request->isPost) {
            $tgl_mulai = $this->request->post('PengajuanDinasSearch')['tanggal_mulai'];
            $tgl_selesai = $this->request->post('PengajuanDinasSearch')['tanggal_selesai'];
            $dataProvider = $searchModel->search($searchModel, $tgl_mulai, $tgl_selesai);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai
        ]);
    }

    /**
     * Displays a single PengajuanDinas model.
     * @param int $id_pengajuan_dinas Id Pengajuan Dinas
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_pengajuan_dinas)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_pengajuan_dinas),
        ]);
    }

    /**
     * Creates a new PengajuanDinas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanDinas();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                if ($model['status'] == Yii::$app->params['disetujui']) {
                    $model->disetujui_oleh = Yii::$app->user->identity->id;
                    $model->disetujui_pada = date('Y-m-d H:i:s');
                    $model->biaya_yang_disetujui = $model->estimasi_biaya;
                }
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Menambahkan Data ');
                } else {
                    Yii::$app->session->setFlash('error', 'gagal Menambahkan Data ');
                }
                return $this->redirect(['view', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PengajuanDinas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_pengajuan_dinas Id Pengajuan Dinas
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_pengajuan_dinas)
    {
        $model = $this->findModel($id_pengajuan_dinas);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->disetujui_oleh = Yii::$app->user->identity->id;
            $model->disetujui_pada = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PengajuanDinas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pengajuan_dinas Id Pengajuan Dinas
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_pengajuan_dinas)
    {
        $model = $this->findModel($id_pengajuan_dinas);
        if ($model->files != null) {

            $files = json_decode($model->files, true);

            if ($files) {
                foreach ($files as $file) {
                    if (file_exists(Yii::getAlias('@webroot') . '/' . $file)) {
                        unlink(Yii::getAlias('@webroot') . '/' . $file);
                    }
                }
            }
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the PengajuanDinas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pengajuan_dinas Id Pengajuan Dinas
     * @return PengajuanDinas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pengajuan_dinas)
    {
        if (($model = PengajuanDinas::findOne(['id_pengajuan_dinas' => $id_pengajuan_dinas])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
