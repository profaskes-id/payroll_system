<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\models\AtasanKaryawan;
use backend\models\DetailTugasLuar;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\NotificationHelper;
use backend\models\Karyawan;
use backend\models\MasterCuti;
use backend\models\PengajuanCuti;
use backend\models\PengajuanDinas;
use backend\models\PengajuanLembur;
use backend\models\PengajuanTugasLuar;
use backend\models\PengajuanWfh;
use backend\models\RekapCuti;
use backend\models\SettinganUmum;
use DateTime;
use ReflectionFunctionAbstract;
use Yii;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


class PengajuanController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if ($action->id == 'lembur-delete' || $action->id == "checkin-tugas-luar") {
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
                $model->tanggal_mulai = $this->request->post('PengajuanCuti')['tanggal_mulai'];
                $model->tanggal_selesai = $this->request->post('PengajuanCuti')['tanggal_selesai'];
                $model->id_karyawan = $karyawan->id_karyawan;
                $model->tanggal_pengajuan = date('Y-m-d H:i:s');
                $model->jenis_cuti = Yii::$app->request->post('jenis_cuti');
                $model->sisa_hari = 90;
                if ($model->save()) {

                    // ? KIRIM NOTIFIKASI
                    $atasan = $this->getAtasanKaryawan($karyawan->id_karyawan);
                    if ($atasan != null) {
                        $adminUsers = User::find()->select(['id', 'email', 'role_id',])->where(['id' => $atasan['id_atasan']])->all();
                    } else {
                        $adminUsers = User::find()->select(['id', 'email', 'role_id',])->where(['role_id' => [1, 3]])->all();
                    }

                    $params = [
                        'judul' => 'Pengajuan cuti',
                        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan cuti.',
                        'nama_transaksi' => "cuti",
                        'id_transaksi' => $model['id_pengajuan_cuti'],
                    ];
                    $this->sendNotif($params, $model, $adminUsers, "Pengajuan cuti Baru Dari " . $model->karyawan->nama);


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

        // Ambil seluruh data jenis cuti dengan status aktif
        $jenisCuti = MasterCuti::find()
            ->where(['status' => 1])
            ->orderBy(['jenis_cuti' => SORT_ASC])
            ->all();

        // Filter jenis cuti berdasarkan kode jenis kelamin
        if ($karyawan->kode_jenis_kelamin == 'L') {
            $jenisCuti = array_filter($jenisCuti, function ($cuti) {
                return $cuti->jenis_cuti !== 'Cuti Hamil';
            });
        }

        return $this->asJson($jenisCuti);
    }

    public function actionCutiDetail($id)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanCuti::find()->where(['id_pengajuan_cuti' => $id])->one();
        if ($model) {
            return $this->render('home/pengajuan/wfh/detail', compact('model'));
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
                    // Kirim notifikasi ke atasan
                    $atasan = $this->getAtasanKaryawan($karyawan->id_karyawan);
                    if ($atasan != null) {
                        $adminUsers = User::find()->select(['id', 'email', 'role_id'])->where(['id' => $atasan['id_atasan']])->all();
                    } else {
                        $adminUsers = User::find()->select(['id', 'email', 'role_id'])->where(['role_id' => [1, 3]])->all();
                    }

                    $params = [
                        'judul' => 'Pengajuan lembur',
                        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan lembur.',
                        'nama_transaksi' => "lembur",
                        'id_transaksi' => $model['id_pengajuan_lembur'],
                    ];
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
        $pengajuanDinas = PengajuanDinas::find()->where(['id_karyawan' => $karyawan->id_karyawan])->orderBy(['tanggal_mulai' => SORT_DESC, 'status' => SORT_ASC,])->all();
        return $this->render('/home/pengajuan/dinas/index', compact('pengajuanDinas'));
    }


    public function actionDinasCreate()
    {

        $this->layout = 'mobile-main';
        $model = new PengajuanDinas();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
                $model->id_karyawan = $karyawan->id_karyawan;
                $model->disetujui_pada = date('Y-m-d');
                $model->status = 0;

                if ($model->save()) {
                    // ? KIRIM NOTIFIKASI
                    $atasan = $this->getAtasanKaryawan($karyawan->id_karyawan);
                    if ($atasan != null) {
                        $adminUsers = User::find()->select(['id', 'email', 'role_id',])->where(['id' => $atasan['id_atasan']])->all();
                    } else {
                        $adminUsers = User::find()->select(['id', 'email', 'role_id',])->where(['role_id' => [1, 3]])->all();
                    }
                    $params = [
                        'judul' => 'Pengajuan Dinas',
                        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan dinas luar.',
                        'nama_transaksi' => "dinas",
                        'id_transaksi' => $model['id_pengajuan_dinas'],
                    ];
                    $this->sendNotif($params, $model, $adminUsers, "Pengajuan Dinas Baru Dari " . $model->karyawan->nama);

                    Yii::$app->session->setFlash('success', 'Berhasil Membuat Pengajuan');
                    return $this->redirect(['/pengajuan/dinas']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/pengajuan/dinas']);
                }
            }
        }

        return $this->render('home/pengajuan/dinas/create', compact('model'));
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
                    $atasan = $this->getAtasanKaryawan($karyawan->id_karyawan);
                    if ($atasan != null) {
                        $adminUsers = User::find()->select(['id', 'email', 'role_id',])->where(['id' => $atasan['id_atasan']])->all();
                    } else {
                        $adminUsers = User::find()->select(['id', 'email', 'role_id',])->where(['role_id' => [1, 3]])->all();
                    }
                    $params = [
                        'judul' => 'Pengajuan WFH',
                        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan WFH.',
                        'nama_transaksi' => "wfh",
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

        // return $this->renderPartial('@backend/views/home/pengajuan/email', compact('model', 'adminUsers', 'subject'));
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

    public function getAtasanKaryawan($id_karyawan)
    {
        $atasan = AtasanKaryawan::find()->where(['id_karyawan' => $id_karyawan])->asArray()->one();
        return $atasan;
    }
}
