<?php

namespace backend\controllers;

use backend\models\Absensi;
use backend\models\PengajuanAbsensi;
use backend\models\PengajuanAbsensiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * PengajuanAbsensiController implements the CRUD actions for PengajuanAbsensi model.
 */
class PengajuanAbsensiController extends Controller
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
     * Lists all PengajuanAbsensi models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PengajuanAbsensiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PengajuanAbsensi model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PengajuanAbsensi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanAbsensi();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {


                $model->status = 0;
                $model->tanggal_pengajuan = date('Y-m-d');

                if ($model->save()) {
                    //flash pesan
                    \Yii::$app->session->setFlash('success', 'Data berhasil disimpan');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PengajuanAbsensi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->tanggal_disetujui = date('Y-m-d');
            $model->id_approver = \Yii::$app->user->identity->id ?? null;

            if ($model->status == 1) {
                $absensi = new Absensi();
                $absensi->id_karyawan = $model->id_karyawan;
                $absensi->jam_masuk = $model->jam_masuk;
                $absensi->jam_pulang = $model->jam_keluar;
                $absensi->keterangan = $model->alasan_pengajuan;
                $absensi->tanggal = date('Y-m-d', strtotime($model->tanggal_absen));

                // Konversi ke lowercase untuk pengecekan yang konsisten
                if (strtolower($model->kode_status_hadir) == 'wfh') {
                    $absensi->kode_status_hadir = 'H'; // atau 'WFH' sesuai kebutuhan
                    $absensi->is_wfh = 1;
                } else {
                    $absensi->kode_status_hadir = $model->kode_status_hadir;
                    $absensi->is_wfh = 0; // atau null, tergantung default value
                }

                // Timestamp dan user info
                $currentTime = date('Y-m-d H:i:s');
                $currentUserId = Yii::$app->user->identity->id;

                $absensi->created_at = $currentTime;
                $absensi->created_by = $currentUserId;
                $absensi->updated_at = $currentTime;
                $absensi->updated_by = $currentUserId;
                if ($absensi->save()) {
                    if ($model->save()) {
                        \Yii::$app->session->setFlash('success', 'Data berhasil disimpan');
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        \Yii::$app->session->setFlash('error', 'Gagal menyimpan data pengajuan');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    \Yii::$app->session->setFlash('error', 'Gagal menyimpan data absensi');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                if ($model->save()) {
                    \Yii::$app->session->setFlash('success', 'Data berhasil disimpan');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    \Yii::$app->session->setFlash('error', 'Gagal menyimpan data pengajuan');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PengajuanAbsensi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PengajuanAbsensi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PengajuanAbsensi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PengajuanAbsensi::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
