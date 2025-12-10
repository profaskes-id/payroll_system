<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\models\Absensi;
use backend\models\DetailCuti;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\NotificationHelper;
use backend\models\JamKerjaKaryawan;
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

        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $tanggalAwalInt = intval($tanggalAwal->nama_kode); // Misalnya 20
        $tanggalSekarang = date('d');
        $bulanSekarang = date('m');
        $tahunSekarang = date('Y');

        if ($tanggalSekarang < $tanggalAwalInt) {
            // Jika tanggal sekarang < tanggalAwal (misal: sekarang tgl 15, tanggalAwal = 20)
            // tgl_mulai = tanggalAwal+1 + bulan lalu + tahun ini
            $tgl_mulai = date('Y-m-d', mktime(0, 0, 0, $bulanSekarang - 1, $tanggalAwalInt + 1, $tahunSekarang));
            // tgl_selesai = tanggalAwal + bulan sekarang + tahun ini
            $tgl_selesai = date('Y-m-d', mktime(0, 0, 0, $bulanSekarang, $tanggalAwalInt, $tahunSekarang));
        } else {
            // Jika tanggal sekarang >= tanggalAwal (misal: sekarang tgl 25, tanggalAwal = 20)
            // tgl_mulai = tanggalAwal+1 + bulan sekarang + tahun ini
            $tgl_mulai = date('Y-m-d', mktime(0, 0, 0, $bulanSekarang, $tanggalAwalInt + 1, $tahunSekarang));
            // tgl_selesai = tanggalAwal + bulan depan + tahun menyesuaikan
            $tgl_selesai = date('Y-m-d', mktime(0, 0, 0, $bulanSekarang + 1, $tanggalAwalInt, $tahunSekarang));
        }

        // Jika ada parameter GET, gunakan nilai dari GET
        if (!empty(Yii::$app->request->get()['PengajuanCutiSearch']['tanggal_mulai'])) {
            $tgl_mulai = Yii::$app->request->get()['PengajuanCutiSearch']['tanggal_mulai'];
        }
        if (!empty(Yii::$app->request->get()['PengajuanCutiSearch']['tanggal_selesai'])) {
            $tgl_selesai = Yii::$app->request->get()['PengajuanCutiSearch']['tanggal_selesai'];
        }
        $searchModel = new PengajuanCutiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $tgl_mulai, $tgl_selesai);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai
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
    // public function actionCreate()
    // {
    //     $model = new PengajuanCuti();

    //     if ($this->request->isPost) {
    //         if ($model->load($this->request->post())) {
    //             $model->tanggal_pengajuan = date('Y-m-d');
    //             $model->sisa_hari = 0;
    //             $model->status = 0;

    //             if ($model->save()) {
    //                 Yii::$app->session->setFlash('success', 'Pengajuan Cuti Berhasil');
    //                 return $this->redirect(['view', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti]);
    //             } else {
    //                 Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan Cuti');
    //             }
    //         }
    //     } else {
    //         $model->loadDefaultValues();
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

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
            $model->ditanggapi_pada = date('Y-m-d');
            $model->ditanggapi_oleh = Yii::$app->user->identity->id;

            if ($model->save()) {
                $existingDetails = DetailCuti::find()
                    ->where(['id_pengajuan_cuti' => $model->id_pengajuan_cuti])
                    ->indexBy('id_detail_cuti')
                    ->all();
                $postDetails = Yii::$app->request->post('DetailCuti', []);

                $postDates = array_column($postDetails, 'tanggal'); // Ambil semua tanggal dari POST
                $approvedCount = 0;

                // Loop data yang sudah ada di database
                foreach ($existingDetails as $id => $detail) {
                    // Cek apakah tanggal detail ini ada di data POST
                    if (in_array($detail->tanggal, $postDates)) {
                        // Jika ada di POST, set status = 1 (Disetujui)
                        $detail->status = 1;
                        $approvedCount++;
                    } else {
                        // Jika tidak ada di POST, set status = 2 (Ditolak)
                        $detail->status = 2;
                    }
                    $detail->save();
                }

                // Insert data baru dari POST yang belum ada di database
                foreach ($postDetails as $data) {
                    $tanggal = $data['tanggal'];
                    $exists = false;

                    // Cek apakah tanggal ini sudah ada di database
                    foreach ($existingDetails as $detail) {
                        if ($detail->tanggal === $tanggal) {
                            $exists = true;
                            break;
                        }
                    }

                    // Jika belum ada, buat baru dengan status = 1 (Disetujui)
                    if (!$exists) {
                        $detail = new DetailCuti();
                        $detail->id_pengajuan_cuti = $model->id_pengajuan_cuti;
                        $detail->tanggal = $tanggal;
                        $detail->status = 1; // Status disetujui
                        $detail->save();
                        $approvedCount++;
                    }
                }

                // Jika status pengajuan = Disetujui, proses ke Absensi dan RekapCuti
                if ($model->status == Yii::$app->params['disetujui']) {
                    // Prepare data for batch insert into Absensi
                    $absensiData = [];

                    // Ambil semua detail dengan status = 1 (Disetujui)
                    $approvedDetails = DetailCuti::find()
                        ->where(['id_pengajuan_cuti' => $model->id_pengajuan_cuti, 'status' => 1])
                        ->all();

                    foreach ($approvedDetails as $detail) {
                        $absensiData[] = [
                            $model->id_karyawan,
                            '00:00:00',
                            '00:00:00',
                            $detail->tanggal,
                            'C',
                        ];
                    }

                    // Perform batch insert into Absensi table
                    if (!empty($absensiData)) {
                        Yii::$app->db->createCommand()->batchInsert(
                            'absensi',
                            ['id_karyawan', 'jam_masuk', 'jam_pulang', 'tanggal', 'kode_status_hadir'],
                            $absensiData
                        )->execute();
                    }

                    // Update or create RekapCuti
                    $rekapan = RekapCuti::find()->where([
                        'id_karyawan' => $model->id_karyawan,
                        'id_master_cuti' => $model->jenis_cuti,
                        'tahun' => date('Y')
                    ])->one();

                    if ($rekapan) {
                        $rekapan->total_hari_terpakai += $approvedCount;
                        $rekapan->save();
                    } else {
                        $newRekapCuti = new RekapCuti();
                        $newRekapCuti->id_karyawan = $model->id_karyawan;
                        $newRekapCuti->id_master_cuti = $model->jenis_cuti;
                        $newRekapCuti->total_hari_terpakai = $approvedCount;
                        $newRekapCuti->tahun = date('Y');
                        $newRekapCuti->save();
                    }
                }

                $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                $sender = Yii::$app->user->identity->id;

                $params = [
                    'judul' => 'Pengajuan cuti',
                    'deskripsi' => 'Pengajuan Cuti Anda Telah Ditanggapi Oleh Atasan ',
                    'nama_transaksi' => "cuti",
                    'id_transaksi' => $model['id_pengajuan_cuti'],
                ];
                $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan cuti Baru Dari " . $model->karyawan->nama);

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
        // Cari data utama
        $model = $this->findModel($id_pengajuan_cuti);

        // Cari semua detail yang terkait
        $details = DetailCuti::find()->where(['id_pengajuan_cuti' => $id_pengajuan_cuti])->all();

        // Hapus semua detail satu per satu
        foreach ($details as $detail) {
            $detail->delete();
        }

        // Hapus data utama
        $model->delete();

        // Redirect ke index
        Yii::$app->session->setFlash('success', 'Pengajuan cuti dan detailnya berhasil dihapus.');
        return $this->redirect(['index']);
    }

    public function actionDeleteDetail($id, $id_pengajuan_cuti)
    {
        $model = DetailCuti::find()->where(['id_detail_cuti' => $id])->one();
        $model->delete();
        Yii::$app->session->setFlash('success', 'Berhasil Menghapus Pengajuan');
        return $this->redirect('/panel/pengajuan-cuti/view?id_pengajuan_cuti=' . $id_pengajuan_cuti);
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

    public  function hitungHariKerja($tanggal_mulai, $tanggal_selesai, $containsNumber)
    {
        // Konversi tanggal menjadi timestamp
        $timestamp_mulai = strtotime($tanggal_mulai);
        $timestamp_selesai = strtotime($tanggal_selesai);

        // Inisialisasi variabel untuk menghitung hari kerja
        $hari_kerja = 0;

        // Loop melalui semua hari antara tanggal mulai dan tanggal selesai
        for ($timestamp = $timestamp_mulai; $timestamp <= $timestamp_selesai; $timestamp += 86400) { // 86400 detik = 1 hari
            // Ambil nama hari dalam seminggu (contoh: "Sunday", "Monday", dll.)
            $hari = date('l', $timestamp);

            if ($containsNumber) {
                if ($hari != 'Saturday' && $hari != 'Sunday') {
                    $hari_kerja++;
                }
            } else {
                if ($hari != 'Sunday') {
                    $hari_kerja++;
                }
            }
            // Periksa apakah hari tersebut bukan Sabtu atau Minggu
        }

        return $hari_kerja;
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
