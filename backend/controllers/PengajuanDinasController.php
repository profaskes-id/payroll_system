<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\models\Absensi;
use backend\models\DetailDinas;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\NotificationHelper;
use backend\models\MasterKode;
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
        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
        $tgl_mulai =  Yii::$app->request->get() == [] ? $firstDayOfMonth : date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $tgl_selesai =  Yii::$app->request->get() == [] ? $lastdate : date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));

        $searchModel = new PengajuanDinasSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $tgl_mulai, $tgl_selesai);

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


    public function actionCreate()
    {
        $model = new PengajuanDinas();
        $detailModels = [];

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                try {
                    $model->status = 0;
                    if ($model->save()) {
                        $postData = Yii::$app->request->post();

                        if (isset($postData['DetailDinas']) && is_array($postData['DetailDinas'])) {
                            foreach ($postData['DetailDinas'] as $detailData) {
                                if (empty($detailData['tanggal'])) {
                                    continue;
                                }

                                $detailModel = new DetailDinas();
                                $detailModel->id_pengajuan_dinas = $model->id_pengajuan_dinas;
                                $detailModel->tanggal = $detailData['tanggal'];
                                $detailModel->status = 1;

                                if ($detailModel->save()) {
                                } else {
                                    throw new \Exception('Gagal menyimpan detail dinas: ' . json_encode($detailModel->errors));
                                }
                            }
                        }

                        Yii::$app->session->setFlash('success', 'Berhasil Menambahkan Data');

                        // Kirim notifikasi
                        $sender = Yii::$app->user->identity->id;

                        $params = [
                            'judul' => 'Pengajuan dinas',
                            'deskripsi' => 'Pengajuan Dinas luar Baru Telah Dibuat.',
                            'nama_transaksi' => "dinas",
                            'id_transaksi' => $model->id_pengajuan_dinas,
                        ];
                        $this->sendNotif($params, $sender, $model, [], "Pengajuan dinas Baru Dari " . $model->karyawan->nama);
                        return $this->redirect(['view', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]);
                    } else {
                        throw new \Exception('Gagal menyimpan pengajuan dinas: ' . json_encode($model->errors));
                    }
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', 'Gagal Menambahkan Data: ' . $e->getMessage());
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'detailModels' => $detailModels,
        ]);
    }

    public function actionUpdate($id_pengajuan_dinas)
    {
        $model = $this->findModel($id_pengajuan_dinas);
        $detailModels = DetailDinas::find()->where(['id_pengajuan_dinas' => $id_pengajuan_dinas])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {

            // Set data persetujuan jika status disetujui
            if ($model->status == Yii::$app->params['disetujui']) {
                $model->disetujui_oleh = Yii::$app->user->identity->id;
                $model->disetujui_pada = date('Y-m-d H:i:s');
                $model->biaya_yang_disetujui = $model->estimasi_biaya;
            }

            try {
                if ($model->save()) {
                    $postData = Yii::$app->request->post();

                    // Hapus absensi lama yang terkait dengan dinas ini
                    $this->deleteAbsensiDinas($model->id_karyawan, $model->id_pengajuan_dinas);

                    // Simpan detail dinas baru
                    if (isset($postData['DetailDinas']) && is_array($postData['DetailDinas'])) {
                        foreach ($postData['DetailDinas'] as $detailData) {
                            // Skip jika tanggal kosong
                            if (empty($detailData['tanggal'])) {
                                continue;
                            }

                            $detailModel = new DetailDinas();
                            $detailModel->id_pengajuan_dinas = $model->id_pengajuan_dinas;
                            $detailModel->tanggal = $detailData['tanggal'];
                            $detailModel->keterangan = $detailData['keterangan'] ?? '';
                            // $detailModel->lokasi_tujuan = $detailData['lokasi_tujuan'] ?? '';
                            $detailModel->status = $detailData['status'];


                            if ($detailModel->save()) {
                                // Jika status detail = 1 (Disetujui), buat data absensi
                                if ($detailModel->status == 1 && $model->isNewAbsen == 1) {
                                    $this->createAbsensiDinas($model->id_karyawan, $detailModel->tanggal);
                                }
                            } else {
                                throw new \Exception('Gagal menyimpan detail dinas: ' . json_encode($detailModel->errors));
                            }
                        }
                    }

                    Yii::$app->session->setFlash('success', 'Berhasil Mengupdate Data');

                    // Kirim notifikasi
                    $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                    $sender = Yii::$app->user->identity->id;

                    $params = [
                        'judul' => 'Pengajuan dinas',
                        'deskripsi' => 'Pengajuan Dinas luar Anda Telah Ditanggapi Oleh Atasan.',
                        'nama_transaksi' => "dinas",
                        'id_transaksi' => $model->id_pengajuan_dinas,
                    ];
                    // $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan dinas Baru Dari " . $model->karyawan->nama);
                    return $this->redirect(['view', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]);
                } else {
                    throw new \Exception('Gagal mengupdate pengajuan dinas: ' . json_encode($model->errors));
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Gagal Mengupdate Data: ' . $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'detailModels' => $detailModels,
        ]);
    }

    /**
     * Membuat data absensi untuk dinas
     */
    private function createAbsensiDinas($idKaryawan, $tanggal)
    {
        // Cek apakah sudah ada data absensi untuk karyawan dan tanggal tersebut
        $existingAbsensi = Absensi::find()
            ->where(['id_karyawan' => $idKaryawan])
            ->andWhere(['tanggal' => $tanggal])
            ->exists();

        if (!$existingAbsensi) {
            $absensi = new Absensi();
            $absensi->id_karyawan = $idKaryawan;
            $absensi->tanggal = $tanggal;
            $absensi->kode_status_hadir = 'DL'; // Dinas Luar
            $absensi->keterangan = 'Dinas Luar';
            $absensi->created_at = date('Y-m-d H:i:s');
            $absensi->created_by = Yii::$app->user->identity->id;


            if (!$absensi->save()) {
                Yii::error('Gagal membuat absensi dinas: ' . json_encode($absensi->errors));
            }
        }
    }

    /**
     * Menghapus data absensi yang terkait dengan dinas
     */
    private function deleteAbsensiDinas($idKaryawan, $idPengajuanDinas)
    {
        // Dapatkan semua tanggal dari detail dinas yang disetujui
        $detailDates = DetailDinas::find()
            ->select('tanggal')
            ->where(['id_pengajuan_dinas' => $idPengajuanDinas])
            ->andWhere(['status' => 1])
            ->column();

        if (!empty($detailDates)) {
            // Hapus absensi yang terkait dengan tanggal-tanggal tersebut
            Absensi::deleteAll([
                'id_karyawan' => $idKaryawan,
                'tanggal' => $detailDates,
                'kode_status_hadir' => 'DL'
            ]);
        }
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
        if ($model->dokumentasi != null) {

            $files = json_decode($model->dokumentasi, true);

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
    public function actionDeleteDetail($id, $id_pengajuan_dinas)
    {
        $model = DetailDinas::findOne($id);

        // Jika data tidak ditemukan
        if ($model === null) {
            Yii::$app->session->setFlash('error', 'Data tidak ditemukan.');
            return $this->redirect(['view', 'id_pengajuan_dinas' => $id_pengajuan_dinas]);
        }

        // Hapus data
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus.');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menghapus data.');
        }

        // Redirect yang benar
        return $this->redirect(['view', 'id_pengajuan_dinas' => $id_pengajuan_dinas]);
    }



    public function actionBayarkan($id)
    {
        $model = PengajuanDinas::findOne($id);
        if (Yii::$app->request->isPost) {

            $model->status_dibayar = 1;
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Status berhasil diperbarui menjadi dibayarkan.');
            } else {
                Yii::$app->session->setFlash('error', 'Gagal memperbarui status.');
            }
        }
        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
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


    // send notif
    public function sendNotif($params, $sender, $model, $adminUsers, $subject = "Pengajuan Di Tanggapi")
    {
        try {
            NotificationHelper::sendNotification($params, $adminUsers, $sender);
        } catch (\InvalidArgumentException $e) {
            // Handle invalid argument exception
            Yii::error("Invalid argument: " . $e->getMessage());
        } catch (\RuntimeException $e) {
            // Handle runtime exception
            Yii::error("Runtime error: " . $e->getMessage());
        }

        // return $this->renderPartial('@backend/views/home/pengajuan/email', compact('model', 'adminUsers', 'subject'));
        $msgToCheck = $this->renderPartial('@backend/views/home/pengajuan/email', compact('model', 'params'));

        // $msgToCheck = $this->renderPartial('@backend/views/home/pengajuan/email');
        // Mengirim email ke setiap pengguna
        foreach ($adminUsers as $user) {
            $to = $user->email;
            if (EmailHelper::sendEmail($to, $subject, $msgToCheck)) {
                Yii::$app->session->setFlash('success', 'Email berhasil dikirim ke ' . $to);
            } else {
                Yii::$app->session->setFlash('error', 'Email gagal dikirim ke ' . $to);
            }
        }
    }
}
