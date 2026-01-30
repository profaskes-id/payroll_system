<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\models\AtasanKaryawan;
use backend\models\DetailDinas;
use backend\models\DetailTugasLuar;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\NotificationHelper;
use backend\models\helpers\UseMessageHelper;
use backend\models\Karyawan;
use backend\models\MasterCuti;
use backend\models\MasterGaji;
use backend\models\PengajuanCuti;
use backend\models\PengajuanDinas;
use backend\models\PengajuanKasbon;
use backend\models\PengajuanLembur;
use backend\models\PengajuanTugasLuar;
use backend\models\PengajuanWfh;
use backend\models\RekapCuti;
use backend\models\SettinganUmum;
use DateTime;
use ReflectionFunctionAbstract;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class PengajuanController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if ($action->id == 'lembur-delete' || $action->id == "checkin-tugas-luar" || $action->id == "jenis-cuti") {
            // Menonaktifkan CSRF verification untuk aksi 'view'
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }


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
    public function getViewPath()
    {
        return Yii::getAlias('@backend/views/');
    }

    public function actionIndex()
    {
        return $this->redirect(['/home']);
    }








    public function actionTugasLuar()
    {
        $this->layout = 'mobile-main';

        $id_karyawan = Yii::$app->user->identity->id_karyawan;

        if (!$id_karyawan) {
            Yii::$app->session->setFlash('success', 'Dibutuhkan Id Karyawan');
            return $this->redirect(['/']);
        }
        $pengajuanTugasLuar = PengajuanTugasLuar::find()->where(['id_karyawan' => $id_karyawan])->orderBy(['status_pengajuan' => SORT_ASC,])->all();
        return $this->render('/home/pengajuan/tugasluar/index', compact('pengajuanTugasLuar'));
    }



    public function actionTugasLuarCreate()
    {
        $this->layout = 'mobile-main';
        $model = new PengajuanTugasLuar();
        $details = [new DetailTugasLuar()]; // Array untuk menyimpan detail tugas

        // Set default values
        $model->id_karyawan = Yii::$app->user->identity->id_karyawan;
        $model->status_pengajuan = 0; // Set default status pending

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Simpan model utama terlebih dahulu
                if (!$model->save(false)) {
                    throw new \Exception('Gagal menyimpan pengajuan tugas luar.');
                }

                // Proses detail tugas luar
                $details = [];
                if (isset($_POST['DetailTugasLuar']) && is_array($_POST['DetailTugasLuar'])) {
                    foreach ($_POST['DetailTugasLuar'] as $i => $detailData) {
                        $detail = new DetailTugasLuar();
                        $detail->attributes = $detailData;
                        $detail->id_tugas_luar = $model->id_tugas_luar;
                        $detail->urutan = $i + 1;
                        $detail->status_check = 0; // Default belum check in

                        if (!$detail->save(false)) {
                            throw new \Exception('Gagal menyimpan detail tugas luar.');
                        }
                        $details[] = $detail;
                    }
                }

                // Validasi minimal 1 detail
                if (empty($details)) {
                    throw new \Exception('Setidaknya harus ada satu lokasi tugas.');
                }

                $transaction->commit();

                $useMessage = new UseMessageHelper();
                $adminUsers = $useMessage->getUserAtasanReceiver($model->id_karyawan);


                $params = [
                    'judul' => 'Pengajuan Tugas Luar ',
                    'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan Tugas Luar.',
                    'nama_transaksi' => "/panel/tanggapan/tugas-luar-view?id",
                    'id_transaksi' => $model['id_tugas_luar'],
                ];

                $this->sendNotif($params, $model, $adminUsers, "Pengajuan Tugas Luar Baru Dari " . $model->karyawan->nama);


                Yii::$app->session->setFlash('success', 'Pengajuan tugas luar berhasil disimpan.');
                return $this->redirect(['index']); // Ganti dengan route yang sesuai

            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal menyimpan pengajuan: ' . $e->getMessage());

                // Kembalikan detail yang sudah diinput untuk ditampilkan kembali di form
                $details = [];
                if (isset($_POST['DetailTugasLuar']) && is_array($_POST['DetailTugasLuar'])) {
                    foreach ($_POST['DetailTugasLuar'] as $detailData) {
                        $detail = new DetailTugasLuar();
                        $detail->attributes = $detailData;
                        $details[] = $detail;
                    }
                }
                if (empty($details)) {
                    $details = [new DetailTugasLuar()];
                }
            }
        }

        return $this->render('/home/pengajuan/tugasluar/create', [
            'model' => $model,
            'details' => $details,
        ]);
    }


    public function actionTugasLuarUpdate($id)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanTugasLuar::findOne($id);
        $details = $model->detailTugasLuars; // Ambil detail yang sudah ada

        // Validasi kepemilikan pengajuan (hanya pemilik yang bisa edit)
        if ($model->id_karyawan != Yii::$app->user->identity->id_karyawan) {
            throw new \yii\web\ForbiddenHttpException('Anda tidak memiliki akses untuk mengedit pengajuan ini.');
        }

        // Validasi status (hanya yang pending bisa diedit)
        if ($model->status_pengajuan != 0) {
            Yii::$app->session->setFlash('error', 'Pengajuan yang sudah disetujui/ditolak tidak dapat diubah.');
            return $this->redirect(['index']);
        }

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Simpan model utama
                if (!$model->save(false)) {
                    throw new \Exception('Gagal memperbarui pengajuan tugas luar.');
                }

                // Proses detail tugas luar
                $newDetails = [];
                $existingDetails = [];

                if (isset($_POST['DetailTugasLuar']) && is_array($_POST['DetailTugasLuar'])) {
                    // Hapus semua detail yang ada (kita akan buat ulang)

                    foreach ($model->detailTugasLuars as $detail) {
                        if (!empty($detail->bukti_foto)) {
                            $filePath = Yii::getAlias('@webroot/uploads/bukti_tugas_luar/') . $detail->bukti_foto;
                            if (file_exists($filePath) && is_file($filePath) && !unlink($filePath)) {
                                throw new \Exception("Gagal menghapus file: " . $detail->bukti_foto);
                            }
                        }
                    }
                    DetailTugasLuar::deleteAll(['id_tugas_luar' => $model->id_tugas_luar]);

                    foreach ($_POST['DetailTugasLuar'] as $i => $detailData) {
                        $detail = new DetailTugasLuar();
                        $detail->attributes = $detailData;
                        $detail->id_tugas_luar = $model->id_tugas_luar;
                        $detail->urutan = $i + 1;
                        $detail->status_check = 0; // Reset status check karena ini update

                        if (!$detail->save(false)) {
                            throw new \Exception('Gagal menyimpan detail tugas luar.');
                        }
                        $newDetails[] = $detail;
                    }
                }

                // Validasi minimal 1 detail
                if (empty($newDetails)) {
                    throw new \Exception('Setidaknya harus ada satu lokasi tugas.');
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Pengajuan tugas luar berhasil diperbarui.');
                return $this->redirect(['/pengajuan/tugas-luar-detail', 'id' => $model->id_tugas_luar]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal memperbarui pengajuan: ' . $e->getMessage());

                // Kembalikan detail yang sudah diinput untuk ditampilkan kembali di form
                $details = [];
                if (isset($_POST['DetailTugasLuar']) && is_array($_POST['DetailTugasLuar'])) {
                    foreach ($_POST['DetailTugasLuar'] as $detailData) {
                        $detail = new DetailTugasLuar();
                        $detail->attributes = $detailData;
                        $details[] = $detail;
                    }
                }
                if (empty($details)) {
                    $details = [new DetailTugasLuar()];
                }
            }
        }

        return $this->render('/home/pengajuan/tugasluar/update', [
            'model' => $model,
            'details' => $details,
        ]);
    }

    public function actionTugasLuarDetail($id)
    {
        $this->layout = 'mobile-main';

        $id_karyawan = Yii::$app->user->identity->id_karyawan;

        if (!$id_karyawan) {
            Yii::$app->session->setFlash('success', 'Dibutuhkan Id Karyawan');
            return $this->redirect(['/']);
        }
        $model = PengajuanTugasLuar::find()->where(['id_karyawan' => $id_karyawan, 'id_tugas_luar' => $id])->orderBy(['status_pengajuan' => SORT_ASC,])->one();
        if ($model) {
            $detail = $model->detailTugasLuars ? $model->detailTugasLuars : [];
            return $this->render('/home/pengajuan/tugasluar/detail', [
                "model" => $model,
                "detail" => $detail
            ]);
        } else {
            Yii::$app->session->setFlash('error', 'Pengajuan Tidak Ditemukan');
            $this->redirect(['/home/index']);
        }
    }


    // PHP (Server-side - Controller)
    public function actionCheckinTugasLuar($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = DetailTugasLuar::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Data tidak ditemukan'];
        }

        $request = Yii::$app->request;
        $model->latitude = $request->post('latitude');
        $model->longitude = $request->post('longitude');
        $model->jam_check_in = date('Y-m-d H:i:s');
        $model->status_check = 1;
        $model->updated_at = date('Y-m-d H:i:s');

        // Handle file upload
        $uploadedFile = \yii\web\UploadedFile::getInstanceByName('bukti_foto');
        if ($uploadedFile) {
            $fileName = 'bukti_' . $model->id_detail . '_' . time() . '.' . $uploadedFile->extension;
            $filePath = Yii::getAlias('@webroot/uploads/bukti_tugas_luar/') . $fileName;

            // Create directory if not exists
            $dir = Yii::getAlias('@webroot/uploads/bukti_tugas_luar/');
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            if ($uploadedFile->saveAs($filePath)) {
                $model->bukti_foto = $fileName;
            } else {
                return ['success' => false, 'message' => 'Gagal menyimpan file'];
            }
        }

        if ($model->save()) {
            return ['success' => true, 'message' => 'Check-in berhasil'];
        } else {
            Yii::error("Error saving model: " . print_r($model->errors, true));
            return [
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'errors' => $model->errors
            ];
        }
    }







    // ?=================================Pengajuan cuti
    public function actionCuti()
    {
        $this->layout = 'mobile-main';


        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
        $pengajuanCuti = PengajuanCuti::find()->where(['id_karyawan' => $karyawan->id_karyawan])->orderBy(['tanggal_pengajuan' => SORT_DESC, 'status' => SORT_ASC,])->all();
        return $this->render('/home/pengajuan/cuti/index', compact('pengajuanCuti'));
    }

    public function actionCutiCreate()
    {


        $this->layout = 'mobile-main';
        $model = new PengajuanCuti();
        $karyawan = Karyawan::find()
            ->select(['id_karyawan', 'kode_jenis_kelamin'])
            ->where(['email' => Yii::$app->user->identity->email])
            ->one();

        // Ambil seluruh data jenis cuti dengan status aktif
        $jenisCuti = MasterCuti::find()
            ->where(['status' => 1])
            ->orderBy(['jenis_cuti' => SORT_ASC])
            ->all();

        // Filter jenis cuti berdasarkan kode jenis kelamin
        if ($karyawan->kode_jenis_kelamin == 'L') { // Laki-laki
            $jenisCuti = array_filter($jenisCuti, function ($cuti) {
                return $cuti->jenis_cuti !== 'Cuti Hamil';
            });
        }

        $rekapCuti = RekapCuti::find()->where(['id_karyawan' => $karyawan->id_karyawan, 'tahun' => date('Y')])->all();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                // Ambil tanggal-tanggal dari form
                $tanggalString = $this->request->post('PengajuanCuti')['tanggal'];
                $tanggalArray = array_map('trim', explode(',', $tanggalString));

                // Set properti ke model cuti
                $model->id_karyawan = $karyawan->id_karyawan;
                $model->tanggal_pengajuan = date('Y-m-d H:i:s');
                $model->jenis_cuti = Yii::$app->request->post('jenis_cuti');
                $model->sisa_hari = 0;
                if ($model->save()) {

                    // Siapkan data detail cuti
                    $detailData = [];
                    foreach ($tanggalArray as $tanggal) {
                        $detailData[] = [
                            'id_pengajuan_cuti' => $model->id_pengajuan_cuti, // jika kolom ini ada
                            'tanggal' => $tanggal,
                            'keterangan' => null,
                            'status' => 0,
                        ];
                    }


                    Yii::$app->db->createCommand()
                        ->batchInsert('{{%detail_cuti}}', ['id_pengajuan_cuti', 'tanggal', 'keterangan', 'status'], $detailData)
                        ->execute();

                    $useMessage = new UseMessageHelper();
                    $adminUsers = $useMessage->getUserAtasanReceiver($karyawan->id_karyawan);


                    $params = [
                        'judul' => 'Pengajuan   Cuti',
                        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan Cuti.',
                        'nama_transaksi' => "/panel/tanggapan/cuti-view?id_pengajuan_cuti",
                        'id_transaksi' => $model['id_pengajuan_cuti'],
                    ];

                    $this->sendNotif($params, $model, $adminUsers, "Pengajuan Cuti Baru Dari " . $model->karyawan->nama);



                    Yii::$app->session->setFlash('success', 'Berhasil Membuat Pengajuan');
                    return $this->redirect(['/pengajuan/cuti']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/pengajuan/cuti']);
                }
            }
        }

        return $this->render('home/pengajuan/cuti/create', compact('model', 'jenisCuti', 'rekapCuti'));
    }


    public function actionJenisCuti()
    {
        $karyawan = Karyawan::find()
            ->select(['id_karyawan', 'kode_jenis_kelamin'])
            ->where(['email' => Yii::$app->user->identity->email])
            ->one();

        $tahun = date('Y'); // Tahun sekarang

        // Ambil seluruh data jenis cuti dengan status aktif
        $jenisCuti = MasterCuti::find()
            ->alias('mc') // alias untuk master_cuti
            ->select([
                'mc.*', // semua kolom dari master_cuti
                'jatah.jatah_hari_cuti',
                'jatah.tahun',
                'jatah.id_karyawan',
                // Ubah total_hari_terpakai jadi 0 jika NULL
                new \yii\db\Expression('COALESCE(rekap.total_hari_terpakai, 0) AS total_hari_terpakai'),
                // Sisa cuti = jatah - terpakai (pastikan keduanya tidak NULL)
                new \yii\db\Expression('COALESCE(jatah.jatah_hari_cuti, 0) - COALESCE(rekap.total_hari_terpakai, 0) AS sisa_cuti_tahun_ini'),
            ])
            ->leftJoin(
                ['jatah' => 'jatah_cuti_karyawan'],
                'jatah.id_master_cuti = mc.id_master_cuti 
             AND jatah.tahun = :tahun 
             AND jatah.id_karyawan = :id_karyawan',
                [':tahun' => $tahun, ':id_karyawan' => $karyawan->id_karyawan]
            )
            ->leftJoin(
                ['rekap' => 'rekap_cuti'],
                'rekap.id_master_cuti = mc.id_master_cuti 
             AND rekap.id_karyawan = jatah.id_karyawan 
             AND rekap.tahun = :tahun',
                [':tahun' => $tahun]
            )
            ->where([
                'mc.status' => 1,
            ])
            ->orderBy(['mc.jenis_cuti' => SORT_ASC])
            ->asArray()
            ->all();

        return $this->asJson($jenisCuti);
    }

    public function actionCutiDetail($id)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanCuti::find()->where(['id_pengajuan_cuti' => $id])->one();

        if ($model) {
            return $this->render('home/pengajuan/cuti/detail', compact('model'));
        } else {
            Yii::$app->session->setFlash('error', 'Pengajuan Tidak Ditemukan');
            $this->redirect(['/home/index']);
        }
    }





    // ?=================================Pengajuan Lembur
    public function actionLembur()
    {
        $this->layout = 'mobile-main';
        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
        $pengajuanLembur = PengajuanLembur::find()->where(['id_karyawan' => $karyawan->id_karyawan])->orderBy(['tanggal' => SORT_DESC, 'status' => SORT_ASC,])->all();
        return $this->render('/home/pengajuan/lembur/index', compact('pengajuanLembur'));
    }

    public function actionLemburCreate()
    {
        $this->layout = 'mobile-main';
        $model = new PengajuanLembur();
        $poinArray = [];

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
                $model->id_karyawan = $karyawan->id_karyawan;
                $model->pekerjaan = json_encode(Yii::$app->request->post('pekerjaan'));
                $model->tanggal = date('Y-m-d H:i:s');
                $model->status = 0;

                $jamMulai = strtotime($model->jam_mulai);
                $jamSelesai = strtotime($model->jam_selesai);

                if ($jamSelesai < $jamMulai) {
                    $jamSelesai += 24 * 60 * 60;
                }

                $selisihDetik = $jamSelesai - $jamMulai;

                $durasiMenit = $selisihDetik / 60;
                $durasiJam = floor($durasiMenit / 60);
                $durasiMenitSisa = $durasiMenit % 60;

                $hitunganLembur = 0;

                // Ambil settingan kalkulasi_jam_lembur
                $settingKalkulasi = SettinganUmum::find()
                    ->where(['kode_setting' => 'kalkulasi_jam_lembur'])
                    ->one();

                if ($settingKalkulasi !== null && intval($settingKalkulasi->nilai_setting) === 0) {
                    // Versi pengali 1.5 dan 2.0

                    if ($durasiJam >= 1) {
                        $hitunganLembur += 1.5;
                        $durasiJam -= 1;
                    }

                    if ($durasiJam > 0) {
                        $hitunganLembur += $durasiJam * 2;
                    }

                    if ($durasiMenitSisa > 0) {
                        if ($durasiMenitSisa >= 30) {
                            $hitunganLembur += 1;
                        } else {
                            $hitunganLembur += 0.5;
                        }
                    }
                } else {
                    // Versi 1:1
                    $hitunganLembur = round($durasiMenit / 60, 2);
                }

                $model->durasi = gmdate('H:i', $selisihDetik);
                $model->hitungan_jam = $hitunganLembur;
                if ($model->save()) {
                    // dapatkan atasan
                    $useMessage = new UseMessageHelper();
                    $adminUsers = $useMessage->getUserAtasanReceiver($karyawan->id_karyawan);

                    // atur parameter yang alkan ditampilkan
                    $params = [
                        'judul' => 'Pengajuan lembur',
                        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan lembur.',
                        'nama_transaksi' => "/panel/tanggapan/lembur-view?id_pengajuan_lembur",
                        'id_transaksi' => $model['id_pengajuan_lembur'],
                    ];
                    // kirim notifikasi ke notifikasi dan atasan
                    $this->sendNotif($params, $model, $adminUsers, "Pengajuan lembur Baru Dari " . $model->karyawan->nama);

                    Yii::$app->session->setFlash('success', 'Berhasil Membuat Pengajuan');
                    return $this->redirect(['/pengajuan/lembur']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/pengajuan/lembur']);
                }
            }
        }

        return $this->render('home/pengajuan/lembur/create', compact('model', 'poinArray'));
    }


    public function actionLemburUpdate($id)
    {

        $pengajuanLembur = PengajuanLembur::find()->where(['id_pengajuan_lembur' => $id])->one();

        $poinArray = json_decode($pengajuanLembur->pekerjaan);
        if ($this->request->isPost) {
            if ($pengajuanLembur->load($this->request->post())) {
                $pengajuanLembur->pekerjaan = json_encode(Yii::$app->request->post('pekerjaan'));
                $jamMulai = strtotime($pengajuanLembur->jam_mulai);
                $jamSelesai = strtotime($pengajuanLembur->jam_selesai);
                $selisihDetik = $jamSelesai - $jamMulai;
                $durasi = gmdate('H:i', $selisihDetik);
                $pengajuanLembur->durasi = $durasi;
                if ($pengajuanLembur->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Mengubah Pengajuan');
                    return $this->redirect(['/pengajuan/lembur']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Mengubah Pengajuan');
                    return $this->redirect(['/pengajuan/lembur']);
                }
            }
        }

        $this->layout = 'mobile-main';
        return $this->render('home/pengajuan/lembur/update', [
            'model' => $pengajuanLembur,
            'poinArray' => $poinArray
        ]);
    }
    public function actionLemburDetail($id)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanLembur::find()->where(['id_pengajuan_lembur' => $id])->one();
        if ($model) {
            $poinArray = json_decode($model->pekerjaan);
            return $this->render('home/pengajuan/lembur/detail', compact('model', 'poinArray'));
        } else {
            Yii::$app->session->setFlash('error', 'Pengajuan Tidak Ditemukan, data telah dihapus');
            $this->redirect(['/home/index']);
        }
    }

    public function actionLemburDelete()
    {
        $id = Yii::$app->request->post('id');
        $model = PengajuanLembur::find()->where(['id_pengajuan_lembur' => $id])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Pengajuan');
            return $this->redirect(['/pengajuan/lembur']);
        }

        Yii::$app->session->setFlash('error', 'Gagal Menghapus Pengajuan');
        return $this->redirect(['/pengajuan/lembur']);
    }





    //?==============pengajuan dinas
    public function actionDinas()
    {
        $this->layout = 'mobile-main';
        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
        $pengajuanDinas = PengajuanDinas::find()->where(['id_karyawan' => $karyawan->id_karyawan])
            ->orderBy(['status' => SORT_ASC])->all();
        return $this->render('/home/pengajuan/dinas/index', compact('pengajuanDinas'));
    }




    public function actionDinasDetail($id)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanDinas::find()->where(['id_pengajuan_dinas' => $id])->one();
        if ($model) {
            return $this->render('home/pengajuan/dinas/detail', compact('model'));
        } else {
            Yii::$app->session->setFlash('error', 'Pengajuan Tidak Ditemukan');
            $this->redirect(['/home/index']);
        }
    }

    public function actionDinasCreate()
    {
        $this->layout = 'mobile-main';
        $model = new PengajuanDinas();
        $dinasDetail = new DetailDinas();
        $detailModels = [];

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
                $model->id_karyawan = $karyawan->id_karyawan;
                $model->disetujui_pada = date('Y-m-d');
                $model->status = 0;

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
                        $postData = Yii::$app->request->post();
                        // Siapkan data detail dinas
                        $detailData = [];

                        // Ambil tanggal dari DetailDinas[tanggal] yang berupa string
                        if (isset($postData['DetailDinas']['tanggal']) && !empty($postData['DetailDinas']['tanggal'])) {
                            $tanggalString = $postData['DetailDinas']['tanggal'];
                            $tanggalArray = array_map('trim', explode(',', $tanggalString));
                            foreach ($tanggalArray as $tanggal) {
                                // Skip jika tanggal kosong
                                if (empty($tanggal)) {
                                    continue;
                                }

                                $detailData[] = [
                                    'id_pengajuan_dinas' => $model->id_pengajuan_dinas,
                                    'tanggal' => $tanggal,
                                    'keterangan' => '',
                                    'status' => 1,
                                ];
                            }
                        }

                        // Simpan detail dinas menggunakan batchInsert
                        if (!empty($detailData)) {
                            Yii::$app->db->createCommand()
                                ->batchInsert(
                                    '{{%detail_dinas}}',
                                    ['id_pengajuan_dinas', 'tanggal', 'keterangan', 'status'],
                                    $detailData
                                )
                                ->execute();
                        }

                        $transaction->commit();

                        // Dapatkan atasan
                        $useMessage = new UseMessageHelper();
                        $adminUsers = $useMessage->getUserAtasanReceiver($karyawan->id_karyawan);

                        // Atur parameter yang akan ditampilkan
                        $params = [
                            'judul' => 'Pengajuan Dinas',
                            'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan dinas luar.',
                            'nama_transaksi' => "/panel/tanggapan/dinas-view?id_pengajuan_dinas",
                            'id_transaksi' => $model->id_pengajuan_dinas,
                        ];

                        // Kirim notifikasi ke notifikasi dan atasan
                        $this->sendNotif($params, $model, $adminUsers, "Pengajuan Dinas Baru Dari " . $model->karyawan->nama);

                        Yii::$app->session->setFlash('success', 'Berhasil Membuat Pengajuan');
                        return $this->redirect(['/pengajuan/dinas']);
                    } else {
                        throw new \Exception('Gagal menyimpan pengajuan dinas: ' . json_encode($model->errors));
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan: ' . $e->getMessage());
                    return $this->redirect(['/pengajuan/dinas']);
                }
            }
        }

        return $this->render('home/pengajuan/dinas/create', [
            'model' => $model,
            'detailModels' => $detailModels,
            'dinasDetail' => $dinasDetail
        ]);
    }

    public function actionUploadDokumentasi()
    {
        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
        $model = PengajuanDinas::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one();
        $files = $model->dokumentasi;
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $dataGambar = UploadedFile::getInstances($model, 'files');

                // Path upload folder
                $uploadPath = Yii::getAlias('@webroot/uploads/dokumentasi/');

                // Array untuk menyimpan path file
                $filePaths = [];

                // Ambil gambar lama dari database
                if ($files) {
                    $existingFiles = json_decode($files);
                    $filePaths = array_merge($filePaths, $existingFiles);
                }

                foreach ($dataGambar as $file) {
                    $fileName = uniqid() . '.' . $file->extension;
                    $filePath = $uploadPath . $fileName;

                    if ($file->saveAs($filePath)) {
                        // Simpan path file baru
                        $filePaths[] = 'uploads/dokumentasi/' . $fileName;
                    }
                }

                // Simpan informasi file di database
                $model->dokumentasi = json_encode($filePaths); // Simpan dalam format JSON


                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Files Berhasil Di upload.');
                    return $this->redirect(['/pengajuan/dinas']);
                } else {
                    Yii::$app->session->setFlash('error', ' Gagal Melakukan upload file.');
                    return $this->redirect(['/pengajuan/dinas']);
                }
            }
        }
    }

    public function actionDeleteDokumentasi($id)
    {

        $model = PengajuanDinas::find()->where(['id_pengajuan_dinas' => $id])->one();

        if ($model->dokumentasi) {
            $data = json_decode($model->dokumentasi, true);
            foreach ($data as $key => $item) {
                if (file_exists(Yii::getAlias('@webroot') . '/' . $item)) {
                    unlink(Yii::getAlias('@webroot') . '/' . $item);
                }
            }
        }
        $model->dokumentasi = null;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Files Berhasil Di hapus.');
            return $this->redirect(['/pengajuan/dinas']);
        } else {
            Yii::$app->session->setFlash('error', ' Gagal Melakukan hapus file.');
            return $this->redirect(['/pengajuan/dinas']);
        }
    }





    // ? ==========Pengajuan WFH=========
    public function actionWfh()
    {
        $this->layout = 'mobile-main';
        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
        $pengajuanWfh = PengajuanWfh::find()->asArray()->where(['id_karyawan' => $karyawan->id_karyawan])->orderBy(['status' => SORT_ASC,])->all();
        return $this->render('/home/pengajuan/wfh/index', compact('pengajuanWfh'));
    }

    public function actionWfhCreate()
    {

        $this->layout = 'mobile-main';
        $model = new PengajuanWfh();

        $poinArray = [];
        if ($this->request->isPost) {
            // Simpan pesan ke tabel message

            if ($model->load($this->request->post())) {
                $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
                $model->id_karyawan = $karyawan->id_karyawan;
                $tanggalArray = [];
                $startDate = new DateTime($model->tanggal_mulai);
                $endDate = new DateTime($model->tanggal_selesai);


                while ($startDate <= $endDate) {
                    $tanggalArray[] = $startDate->format('Y-m-d');
                    $startDate->modify('+1 day');
                }
                $model->tanggal_array = json_encode($tanggalArray);
                if ($model->save()) {

                    // ? KIRIM NOTIFIKASI
                    $useMessage = new UseMessageHelper();
                    $adminUsers = $useMessage->getUserAtasanReceiver($karyawan->id_karyawan);


                    $params = [
                        'judul' => 'Pengajuan WFH',
                        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan WFH.',
                        'nama_transaksi' => "/panel/tanggapan/wfh-view?id_pengajuan_wfh",
                        'id_transaksi' => $model['id_pengajuan_wfh'],
                    ];

                    $this->sendNotif($params, $model, $adminUsers, "Pengajuan WFH Baru Dari " . $model->karyawan->nama);

                    Yii::$app->session->setFlash('success', 'Berhasil Membuat Pengajuan');

                    return $this->redirect(['/pengajuan/wfh']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/pengajuan/wfh']);
                }
            }
        }

        return $this->render('home/pengajuan/wfh/create', compact('model'));
    }
    public function actionWfhUpdate($id)
    {

        $pengajuanWfh = PengajuanWfh::find()->where(['id_pengajuan_wfh' => $id])->one();
        // $poinArray = json_decode($pengajuanWfh->pekerjaan);
        if ($this->request->isPost) {
            if ($pengajuanWfh->load($this->request->post())) {
                // $pengajuanWfh->pekerjaan = json_encode(Yii::$app->request->post('pekerjaan'));
                if ($pengajuanWfh->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Mengubah Pengajuan');
                    return $this->redirect(['/pengajuan/wfh']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal , Pastikan data yang anda masukkan benar');
                }
            }
        }

        $this->layout = 'mobile-main';
        return $this->render('home/pengajuan/wfh/update', [
            'model' => $pengajuanWfh,
            // 'poinArray' => $poinArray
        ]);
    }

    public function actionWfhDetail($id)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanWfh::find()->where(['id_pengajuan_wfh' => $id])->one();
        if ($model) {
            return $this->render('home/pengajuan/wfh/detail', compact('model'));
        } else {
            Yii::$app->session->setFlash('error', 'Pengajuan Tidak Ditemukan');
            $this->redirect(['/home/index']);
        }
    }

    public function actionWfhDelete()
    {
        $id = Yii::$app->request->post('id');
        $model = PengajuanWfh::find()->where(['id_pengajuan_wfh' => $id])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Pengajuan');
            return $this->redirect(['/pengajuan/wfh']);
        }

        Yii::$app->session->setFlash('error', 'Gagal Menghapus Pengajuan');
        return $this->redirect(['/pengajuan/wfh']);
    }



    // Pengajuan Kasbon

    //?==============pengajuan kasbon
    public function actionKasbon()
    {
        $this->layout = 'mobile-main';
        $karyawan = Karyawan::find()
            ->select('id_karyawan')
            ->where(['email' => Yii::$app->user->identity->email])
            ->one();

        $pengajuanKasbon = PengajuanKasbon::find()
            ->where(['id_karyawan' => $karyawan->id_karyawan])
            ->orderBy(['tanggal_pengajuan' => SORT_DESC])
            ->all();

        return $this->render('/home/pengajuan/kasbon/index', compact('pengajuanKasbon'));
    }


    public function actionKasbonCreate()
    {
        $this->layout = 'mobile-main';

        // Dapatkan data karyawan berdasarkan user login
        $karyawan = Karyawan::find()
            ->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])
            ->one();

        if (!$karyawan) {
            Yii::$app->session->setFlash('error', 'Data karyawan tidak ditemukan.');
            return $this->redirect(['/home/index']);
        }

        // Dapatkan gaji karyawan
        $gaji = MasterGaji::find()
            ->select('nominal_gaji')
            ->where(['id_karyawan' => $karyawan->id_karyawan])
            ->scalar(); // ambil langsung nilainya

        $gajiPokok = $gaji ? $gaji : 0;

        // Buat model baru
        $model = new PengajuanKasbon();
        $model->gaji_pokok = $gajiPokok;
        $model->jumlah_kasbon = $gajiPokok * 3; // default 3x gaji
        $model->lama_cicilan = 0;
        $model->angsuran_perbulan = 0;
        $model->tanggal_pengajuan = date('Y-m-d');
        $model->status = 0;
        $model->created_at = time();
        $model->created_by = Yii::$app->user->id;

        // Jika form disubmit
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                // Set data tambahan yang tidak diinput user
                $model->id_karyawan = $karyawan->id_karyawan;
                $model->gaji_pokok = $gajiPokok;
                $model->tanggal_pengajuan = date('Y-m-d');
                $model->status = 0;
                $model->created_at = time();
                $model->created_by = Yii::$app->user->id;

                // Hitung otomatis angsuran perbulan (jika belum diisi)
                if (empty($model->angsuran_perbulan) && $model->lama_cicilan > 0) {
                    $model->angsuran_perbulan = $model->jumlah_kasbon / $model->lama_cicilan;
                }

                // Simpan data
                if ($model->save(false)) {
                    // Kirim notifikasi ke atasan
                    $useMessage = new UseMessageHelper();
                    $adminUsers = $useMessage->getUserAtasanReceiver($karyawan->id_karyawan);

                    $params = [
                        'judul' => 'Pengajuan Kasbon',
                        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan kasbon baru.',
                        'nama_transaksi' => '/panel/tanggapan/kasbon-view?id_pengajuan_kasbon=',
                        'id_transaksi' => $model->id_pengajuan_kasbon,
                    ];

                    $this->sendNotif($params, $model, $adminUsers, "Pengajuan Kasbon Baru Dari " . $model->karyawan->nama);

                    Yii::$app->session->setFlash('success', 'Berhasil Membuat Pengajuan Kasbon.');
                    return $this->redirect(['/pengajuan/kasbon']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan Kasbon.');
                    return $this->redirect(['/pengajuan/kasbon']);
                }
            }
        }

        // Kirim data ke form
        return $this->render('/home/pengajuan/kasbon/create', [
            'model' => $model,
            'gajiPokok' => $gajiPokok,
        ]);
    }
    public function actionKasbonDetail($id)
    {

        $this->layout = 'mobile-main';
        // Cari data kasbon berdasarkan ID
        $model = \backend\models\PengajuanKasbon::find()
            ->where(['id_pengajuan_kasbon' => $id])
            ->one();

        // Jika data tidak ditemukan
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Data pengajuan kasbon tidak ditemukan.');
        }

        // Render view detail
        return $this->render('/home/pengajuan/kasbon/detail', [
            'model' => $model,
        ]);
    }






    public function sendNotif($params, $model, $adminUsers, $subject = "Pengajuan Karyawan")
    {
        try {
            NotificationHelper::sendNotification($params, $adminUsers);
        } catch (\InvalidArgumentException $e) {
            // Handle invalid argument exception
            Yii::error("Invalid argument: " . $e->getMessage());
        } catch (\RuntimeException $e) {
            // Handle runtime exception
            Yii::error("Runtime error: " . $e->getMessage());
        }

        $msgToCheck = $this->renderPartial('@backend/views/home/pengajuan/email_user', compact('model', 'params'));

        foreach ($adminUsers as $atasan) {
            $to = $atasan['email'];
            if (EmailHelper::sendEmail($to, $subject, $msgToCheck)) {
                Yii::$app->session->setFlash('success', 'Email berhasil dikirim ke ' . $to);
            } else {
                Yii::$app->session->setFlash('error', 'Email gagal dikirim ke ' . $to);
            }
        }
    }
}
