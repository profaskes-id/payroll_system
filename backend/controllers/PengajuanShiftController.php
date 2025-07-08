<?php

namespace backend\controllers;

use backend\models\JadwalShift;
use backend\models\PengajuanShift;
use backend\models\PengajuanShiftSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * PengajuanShiftController implements the CRUD actions for PengajuanShift model.
 */
class PengajuanShiftController extends Controller
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
     * Lists all PengajuanShift models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PengajuanShiftSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PengajuanShift model.
     * @param int $id_pengajuan_shift Id Pengajuan Shift
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_pengajuan_shift)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_pengajuan_shift),
        ]);
    }

    /**
     * Creates a new PengajuanShift model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanShift();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_pengajuan_shift' => $model->id_pengajuan_shift]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PengajuanShift model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_pengajuan_shift Id Pengajuan Shift
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_pengajuan_shift)
    {
        $model = $this->findModel($id_pengajuan_shift);

        if ($this->request->isPost && $model->load($this->request->post())) {

            if ($model->status == '1') {

                $tanggalMulai = $model['tanggal_awal']; // dari form
                $tanggalSelesai = $model['tanggal_akhir']; // dari form
                $start = new \DateTime($tanggalMulai);
                $end = new \DateTime($tanggalSelesai);
                $end = $end->modify('+1 day'); // supaya termasuk tanggal selesai

                $successCount = 0;
                $failedCount = 0;

                $interval = new \DateInterval('P1D');
                $dateRange = new \DatePeriod($start, $interval, $end);

                foreach ($dateRange as $date) {
                    $tanggal = $date->format('Y-m-d');

                    // Cek apakah data untuk tanggal & karyawan ini sudah ada
                    $jadwal = JadwalShift::findOne([
                        'id_karyawan' => $model->id_karyawan,
                        'tanggal' => $tanggal
                    ]);




                    if (!$jadwal) {
                        $jadwal = new JadwalShift();
                        $jadwal->id_karyawan = $model->id_karyawan;
                        $jadwal->tanggal = $tanggal;
                        $jadwal->id_shift_kerja = $model->id_shift_kerja;
                    }
                    $jadwal->id_shift_kerja = $model->id_shift_kerja;

                    if ($jadwal->save()) {
                        $successCount++;
                    } else {
                        $failedCount++;
                    }
                }
                if ($successCount > 0) {
                    Yii::$app->session->setFlash('success', "$successCount shift berhasil diubah.");
                }
                if ($failedCount > 0) {
                    Yii::$app->session->setFlash('error', "$failedCount shift gagal diubah.");
                }
            }
            $model->save();

            return $this->redirect(['view', 'id_pengajuan_shift' => $model->id_pengajuan_shift]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PengajuanShift model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pengajuan_shift Id Pengajuan Shift
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_pengajuan_shift)
    {
        $this->findModel($id_pengajuan_shift)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PengajuanShift model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pengajuan_shift Id Pengajuan Shift
     * @return PengajuanShift the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pengajuan_shift)
    {
        if (($model = PengajuanShift::findOne(['id_pengajuan_shift' => $id_pengajuan_shift])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
