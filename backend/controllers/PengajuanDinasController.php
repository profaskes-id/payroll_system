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
        $tanggalAwalInt = intval($tanggalAwal->nama_kode); // Misalnya 20 atau 21
        $tanggalSekarang = date('d');
        $bulanSekarang = date('m');
        $tahunSekarang = date('Y');

        if ($tanggalSekarang < $tanggalAwalInt) {
            // Jika tanggal sekarang < tanggalAwal (misal: sekarang tgl 15, tanggalAwal = 21)
            // tgl_mulai = tanggalAwal + bulan lalu + tahun ini
            $tgl_mulai = date('Y-m-d', mktime(0, 0, 0, $bulanSekarang - 1, $tanggalAwalInt, $tahunSekarang));
            // tgl_selesai = (tanggalAwal - 1) + bulan sekarang + tahun ini
            $tgl_selesai = date('Y-m-d', mktime(0, 0, 0, $bulanSekarang, $tanggalAwalInt - 1, $tahunSekarang));
        } else {
            // Jika tanggal sekarang >= tanggalAwal (misal: sekarang tgl 25, tanggalAwal = 21)
            // tgl_mulai = tanggalAwal + bulan sekarang + tahun ini
            $tgl_mulai = date('Y-m-d', mktime(0, 0, 0, $bulanSekarang, $tanggalAwalInt, $tahunSekarang));
            // tgl_selesai = (tanggalAwal - 1) + bulan depan + tahun menyesuaikan
            $tgl_selesai = date('Y-m-d', mktime(0, 0, 0, $bulanSekarang + 1, $tanggalAwalInt - 1, $tahunSekarang));
        }

        // Override dengan input user jika ada
        if (!empty(Yii::$app->request->get()['PengajuanDinasSearch']['tanggal_mulai'])) {
            $tgl_mulai = Yii::$app->request->get()['PengajuanDinasSearch']['tanggal_mulai'];
        }
        if (!empty(Yii::$app->request->get()['PengajuanDinasSearch']['tanggal_selesai'])) {
            $tgl_selesai = Yii::$app->request->get()['PengajuanDinasSearch']['tanggal_selesai'];
        }

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

        // Ambil detail yang sudah ada dan index by tanggal
        $existingDetails = DetailDinas::find()
            ->where(['id_pengajuan_dinas' => $id_pengajuan_dinas])
            ->indexBy('tanggal')
            ->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                // Set data persetujuan jika status disetujui
                if ($model->status == Yii::$app->params['disetujui']) {
                    $model->disetujui_oleh = Yii::$app->user->identity->id;
                    $model->disetujui_pada = date('Y-m-d H:i:s');
                    $model->biaya_yang_disetujui = $model->estimasi_biaya;
                } else {
                    $model->disetujui_oleh = null;
                    $model->disetujui_pada = null;
                    $model->biaya_yang_disetujui = null;
                }

                // Simpan model utama
                if (!$model->save()) {
                    throw new \Exception('Gagal menyimpan pengajuan dinas: ' . json_encode($model->errors));
                }

                // Proses detail dinas dari POST
                $postDetails = Yii::$app->request->post('DetailDinas', []);
                $submittedDates = [];

                foreach ($postDetails as $row) {
                    if (empty($row['tanggal'])) {
                        continue;
                    }

                    $tanggal = $row['tanggal'];
                    $submittedDates[] = $tanggal;

                    // Cek apakah detail sudah ada
                    if (isset($existingDetails[$tanggal])) {
                        $detail = $existingDetails[$tanggal];
                        // Update detail yang sudah ada
                        $detail->status = 1; // Selalu set status = 1 untuk yang aktif
                    } else {
                        // Buat detail baru
                        $detail = new DetailDinas();
                        $detail->id_pengajuan_dinas = $model->id_pengajuan_dinas;
                        $detail->tanggal = $tanggal;
                        $detail->status = 1;
                    }

                    $detail->keterangan = $row['keterangan'] ?? null;

                    if (!$detail->save(false)) {
                        throw new \Exception('Gagal menyimpan detail dinas: ' . json_encode($detail->errors));
                    }
                }

                // Soft delete detail yang tidak ada di submitted dates
                foreach ($existingDetails as $tanggal => $detail) {
                    if (!in_array($tanggal, $submittedDates)) {
                        $detail->status = 2; // Status 2 = dihapus/ditolak
                        $detail->save(false);
                    }
                }

                // Hapus absensi lama yang terkait dengan dinas ini
                Absensi::deleteAll([
                    'id_karyawan' => $model->id_karyawan,
                    'kode_status_hadir' => 'DL',
                ]);

                // Buat absensi baru hanya jika status pengajuan = DISETUJUI dan isNewAbsen = 1
                if ($model->status == Yii::$app->params['disetujui']) {
                    $isNewAbsen = (int) $model->isNewAbsen;

                    if ($isNewAbsen === 1) {
                        foreach ($submittedDates as $tanggal) {
                            $this->createAbsensiDinas($model->id_karyawan, $tanggal);
                        }
                    }
                }

                $transaction->commit();

                Yii::$app->session->setFlash('success', 'Berhasil Mengupdate Data');

                // Kirim notifikasi jika status berubah
                // (Anda bisa menambahkan logika notifikasi di sini jika diperlukan)

                return $this->redirect(['view', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal Mengupdate Data: ' . $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'detailModels' => $existingDetails, // Kirim detail yang sudah ada
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
            ->andWhere(['kode_status_hadir' => 'DL'])
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
                throw new \Exception('Gagal membuat absensi dinas: ' . json_encode($absensi->errors));
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
