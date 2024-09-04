<?php

namespace backend\controllers;

use backend\models\MasterCuti;
use backend\models\MasterKode;
use backend\models\PengajuanCuti;
use backend\models\PengajuanCutiSearch;
use backend\models\RekapCuti;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PengajuanCutiController implements the CRUD actions for PengajuanCuti model.
 */
class PengajuanCutiController extends Controller
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
     * Lists all PengajuanCuti models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PengajuanCutiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PengajuanCuti model.
     * @param int $id_pengajuan_cuti Id Pengajuan Cuti
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_pengajuan_cuti)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_pengajuan_cuti),
        ]);
    }

    /**
     * Creates a new PengajuanCuti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanCuti();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PengajuanCuti model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_pengajuan_cuti Id Pengajuan Cuti
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_pengajuan_cuti)
    {
        $model = $this->findModel($id_pengajuan_cuti);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->sisa_hari = 0;

            if ($model->save()) {

                $rekapAsensi = new RekapCuti();
                $rekapAsensi->id_karyawan = $model->id_karyawan;
                $rekapAsensi->id_master_cuti = $model->jenis_cuti;


                $timestamp_mulai = strtotime($model->tanggal_mulai);
                $timestamp_selesai = strtotime($model->tanggal_selesai);

                // Menghitung selisih hari
                $selisih_detik = $timestamp_selesai - $timestamp_mulai;
                $selisih_hari = $selisih_detik / (60 * 60 * 24);

                if ($model->status == Yii::$app->params['diterima']) {
                    $getRekap = RekapCuti::find()->where(['id_karyawan' => $model->id_karyawan]);
                    if ($getRekap->exists()) {
                        //jika ada
                        //cek apakah jenis cutinya => $model->jenis_cuti
                        foreach ($getRekap->all() as $item) {
                            if ($item->id_master_cuti == $model->jenis_cuti) {
                                $rekapan = $getRekap->where(['id_karyawan' => $model->id_karyawan, 'id_master_cuti' => $model->jenis_cuti])->one();
                                $rekapan->total_hari_terpakai += $selisih_hari;
                                $rekapan->save();
                            }
                        }
                        // $rekapAsensi->total_hari_terpakai =  $selisih_hari;
                        // $rekapAsensi->save();
                    } else {
                        //jika tiak ada
                        //jatah cuti setahun ambil dari , masterCuti where id_master_cuti == $model->jenis_cuti
                        $masterCuti = MasterCuti::find()->where(['id_master_cuti' => $model->jenis_cuti])->one();
                        $rekapAsensi->total_hari_terpakai =  $selisih_hari;
                        $rekapAsensi->save();
                    }
                }



                Yii::$app->session->setFlash('success', 'Pengajuan Cuti Berhasil Ditanggapi');

                return $this->redirect(['view', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti]);
            }
            Yii::$app->session->setFlash('error', 'Pengajuan Cuti gagal Ditanggapi');
            return $this->redirect(['view', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PengajuanCuti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pengajuan_cuti Id Pengajuan Cuti
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_pengajuan_cuti)
    {
        $this->findModel($id_pengajuan_cuti)->delete();

        return $this->redirect(['index']);
    }



    /**
     * Finds the PengajuanCuti model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pengajuan_cuti Id Pengajuan Cuti
     * @return PengajuanCuti the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pengajuan_cuti)
    {
        if (($model = PengajuanCuti::findOne(['id_pengajuan_cuti' => $id_pengajuan_cuti])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
