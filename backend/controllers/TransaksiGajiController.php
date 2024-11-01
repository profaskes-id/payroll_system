<?php

namespace backend\controllers;

use backend\models\Absensi;
use backend\models\Bagian;
use backend\models\DataPekerjaan;
use backend\models\Karyawan;
use backend\models\MasterKode;
use backend\models\PeriodeGaji;
use backend\models\TotalHariKerja;
use backend\models\TransaksiGaji;
use backend\models\TransaksiGajiSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransaksiGajiController implements the CRUD actions for TransaksiGaji model.
 */
class TransaksiGajiController extends Controller
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
     * Lists all TransaksiGaji models.
     *
     * @return string
     */
    public function actionIndex()
    {
        // $bulan = date('m');
        // $tahun = date('Y');
        $searchModel = new TransaksiGajiSearch();

        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TransaksiGaji model.
     * @param int $id_transaksi_gaji Id Transaksi Gaji
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_transaksi_gaji)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_transaksi_gaji),
        ]);
    }

    /**
     * Creates a new TransaksiGaji model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id_karyawan, $params = null)
    {

        //! mengambil parameter
        if ($params != null) {
            $bulan = $params['bulan'];
            $tahun = $params['tahun'];
        } else {

            $bulan = date('m');
            $tahun = date('Y');
        }

        // !inisiasi awaldan akhir bulan
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, 1, $tahun));
        $lastDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, date('t', mktime(0, 0, 0, $bulan, 1, $tahun)), $tahun));

        $model = new TransaksiGaji();

        $karyawan = $model->getKaryawanData($id_karyawan, $bulan, $tahun);
        $dataPekerjaan = $model->getDataPekerjaan($id_karyawan);
        $absensiData = $model->getAbsensiData($id_karyawan, $firstDayOfMonth, $lastDayOfMonth);
        $totalCuti = $model->getTotalCutiKaryawan($id_karyawan, $firstDayOfMonth, $lastDayOfMonth);
        $gajiPokok = $model->getGajiPokok($id_karyawan,);
        $jumlahJamLembur = $model->getJumlahJamLembur($id_karyawan, $firstDayOfMonth, $lastDayOfMonth);

        $rekapandata = [

            'karyawan' => $karyawan,
            'dataPekerjaan' => $dataPekerjaan,  //data pekerjaan
            'absensiData' => $absensiData,  //data absensi
            'totalCuti' => $totalCuti,  //total cuti
            'gajiPokok' => $gajiPokok,  //gaji pokok
            'jumlahJamLembur' => $jumlahJamLembur,
        ];




        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_transaksi_gaji' => $model->id_transaksi_gaji]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'rekapandata' => $rekapandata,
        ]);
    }

    /**
     * Updates an existing TransaksiGaji model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_transaksi_gaji Id Transaksi Gaji
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_transaksi_gaji)
    {
        $model = $this->findModel($id_transaksi_gaji);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_transaksi_gaji' => $model->id_transaksi_gaji]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TransaksiGaji model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_transaksi_gaji Id Transaksi Gaji
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_transaksi_gaji)
    {
        $this->findModel($id_transaksi_gaji)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TransaksiGaji model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_transaksi_gaji Id Transaksi Gaji
     * @return TransaksiGaji the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_transaksi_gaji)
    {
        if (($model = TransaksiGaji::findOne(['id_transaksi_gaji' => $id_transaksi_gaji])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
