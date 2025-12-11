<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\service\LemburService;
use backend\models\Absensi;
use backend\models\AtasanKaryawan;
use backend\models\DataKeluarga;
use backend\models\helpers\CompressImagesHelper;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\FaceRecognationHelper;
use backend\models\helpers\ManualSHiftHelper;
use backend\models\helpers\NotificationHelper;
use backend\models\helpers\UseMessageHelper;
use backend\models\IzinPulangCepat;
use backend\models\JadwalKerja;
use backend\models\JadwalShift;

use backend\models\JamKerjaKaryawan;
use backend\models\Karyawan;
use backend\models\MasterHaribesar;
use backend\models\MasterKode;
use backend\models\Message;
use backend\models\MessageReceiver;

use backend\models\PengajuanLembur;
use backend\models\PengajuanShift;
use backend\models\PengajuanWfh;
use backend\models\PengalamanKerja;
use backend\models\Pengumuman;
use backend\models\RekapLembur;
use backend\models\RiwayatKesehatan;
use backend\models\RiwayatPelatihan;
use backend\models\RiwayatPendidikan;
use backend\models\SettinganUmum;
use backend\models\ShiftKerja;
use backend\service\CekPengajuanKaryawanService;
use backend\service\JamKerjaKaryawanService;
use Exception;
use InvalidArgumentException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\UploadedFile;

class HomeController extends Controller
{
    // ?behaviors
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


    public function beforeAction($action)
    {
        if ($action->id == 'open-message' || $action->id == 'view' || $action->id == 'expirience-pekerjaan-delete' || $action->id == 'expirience-pendidikan-delete' || $action->id == 'data-keluarga-delete' || $action->id == 'riwayat-pelatihan-delete' || $action->id == 'riwayat-kesehatan-delete' || $action->id == 'change-shift' || $action->id == 'pengajuan-shift') {
            // Menonaktifkan CSRF verification untuk aksi 'view'
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }



    // ?========================Base

    public function actionIndex()
    {


        $this->layout = 'mobile-main';
        $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();
        if (!$karyawan) {
            return "anda tidak terdaftar, silahkan hubungi administrator";
        }


        $is_ada_notif =  MessageReceiver::find()
            ->where(['receiver_id' => Yii::$app->user->id, 'is_open' => 0])
            ->count();


        $pengumuman = Pengumuman::find()->orderBy(['dibuat_pada' => SORT_DESC])->limit(5)->all();
        $absensi = Absensi::find()->where(['id_karyawan' => $karyawan->id_karyawan, 'tanggal' => date('Y-m-d')])->one();

        $lama_kerja = null;
        if ($absensi != null && $absensi->jam_pulang != null) {
            $masuk_timestamp = strtotime($absensi->jam_masuk);
            $keluar_timestamp = strtotime($absensi->jam_pulang);
            $duration = $keluar_timestamp - $masuk_timestamp;
            $hours = floor($duration / 3600);
            $minutes = floor(($duration % 3600) / 60);
            $lama_kerja = sprintf('%02d:%02d', $hours, $minutes);
        }

        $jamKerjaToday = JamKerjaKaryawan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one();

        $dataShift = [];
        if ($jamKerjaToday != null) {


            if ($jamKerjaToday->is_shift == 1) {
                $tanggalHariIni = date('Y-m-d');
                $jadwalShiftHariIni = JadwalShift::find()
                    ->where(['id_karyawan' => $jamKerjaToday['id_karyawan'], 'tanggal' => $tanggalHariIni])
                    ->asArray()
                    ->one();
                if (!$jadwalShiftHariIni) {
                    $dataShif = null;
                } else {
                    $dataShift = ShiftKerja::find()->where(['id_shift_kerja' => $jadwalShiftHariIni['id_shift_kerja']])->one();
                }
            }
        }


        $manual_shift =   ManualSHiftHelper::isManual();
        $change_shift =  ManualSHiftHelper::changeShift();

        $deviasiAbsensi = SettinganUmum::find()->where(['kode_setting' => Yii::$app->params['absensi-tertinggal']])->one();
        return $this->render('index', compact('is_ada_notif', 'karyawan', 'pengumuman', 'absensi', 'lama_kerja', 'jamKerjaToday', 'dataShift', 'manual_shift', 'deviasiAbsensi', 'change_shift'));
    }

    public function actionView($id_user)
    {

        $this->layout = 'mobile-main';

        if ($this->request->isPost) {
            $tanggal_searh = Yii::$app->request->post('tanggal_searh');
            $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();

            $formaterTanggal = date('Y-m-d', strtotime($tanggal_searh));
            $absensiSearh = Absensi::find()->where(['id_karyawan' => $karyawan->id_karyawan, 'tanggal' => $formaterTanggal])->all();

            return $this->render('view', [
                'absensi' => $absensiSearh,
            ]);
        }

        $user = User::find()->where(['id' => $id_user])->one();
        if (!$user) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => $user->email])->asArray()->one();
        $tanggalSatuBulanLalu = (new \DateTime())->modify('-1 month')->format('Y-m-d');

        $absensi = Absensi::find()
            ->asArray()
            ->select(['tanggal', 'jam_masuk', 'jam_pulang', 'keterangan', 'kode_status_hadir', 'is_lembur', 'is_wfh', 'is_terlambat', 'lama_terlambat', 'lama_terlambat', 'is_24jam', 'tanggal_pulang'])
            ->where(['id_karyawan' => $karyawan['id_karyawan']])
            ->andWhere(['>=', 'tanggal', $tanggalSatuBulanLalu])
            ->orderBy(['tanggal' => SORT_DESC])
            ->all();

        return $this->render('view', [
            'absensi' => $absensi,
        ]);
    }

    public function actionUpdate($id_absensi)
    {
        $model = $this->findModel($id_absensi);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_absensi' => $model->id_absensi]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id_absensi)
    {
        $this->findModel($id_absensi)->delete();
        return $this->redirect(['index']);
    }



    //============================================START ABSEN REGULAR============================================


    public function actionAbsenMasuk()
    {
        $hariIni = (int) date('w');
        $this->layout = 'mobile-main';
        $manual_shift = ManualSHiftHelper::isManual(); //melihat apakah shift di atur manual oleh admin
        $comporess = new CompressImagesHelper(); // melakukan kompresi gambar
        $setting_fr = FaceRecognationHelper::cekFaceRecognation(); // melihat apakah face recognation di aktifkan
        $verificationFr = FaceRecognationHelper::cekVerificationFr(); // melihat apakah minimal nilai similaritu di aktifkan
        $model = new Absensi();
        $isTerlambatActive = false; // apakah is terlambat di aktifkan
        $dataProvider = new ActiveDataProvider([
            'query' => Absensi::find(),
        ]);
        $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan, 'is_aktif' => 1])->one();  //karyawan yang login
        $absensiToday = Absensi::find()->where(['tanggal' => date('Y-m-d'), 'id_karyawan' => $karyawan->id_karyawan])->all(); //melihat data absesni hari ini
        $adaWajahTeregistrasi = karyawan::find()->select('id_karyawan')->where(['id_karyawan' => $karyawan->id_karyawan])->andWhere(['IS NOT', 'liveness_passed', null])->andWhere(['<>', 'liveness_passed', ''])->exists();
        $atasanPenempatan = AtasanKaryawan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one(); // data atasa dan penempatan karyawan yang loin
        if ($atasanPenempatan) {
            $masterLokasi = $atasanPenempatan->masterLokasi; // lokasi karyawan ditempatkan
        } else {
            Yii::$app->session->setFlash('error', 'Data Atasan Dan Penempatan Kerja belum ada, Silahkan Hubungi Admin');
            return $this->redirect(['/']);
        }
        $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one(); //jam kerja karyawan dan apakah shift dan maximal terlambat
        if (!$jamKerjaKaryawan) {
            throw new NotFoundHttpException('Admin Belum Melakukan Settingan Jam Kerja Pada Akun Anda');
        }
        $jamKerjaHari = $jamKerjaKaryawan->jamKerja->jadwalKerjas ?? []; //semua jadwal kerja karyawan

        $hasilLembur = CekPengajuanKaryawanService::CekLemburService($karyawan->id_karyawan);
        $isPulangCepat = CekPengajuanKaryawanService::CekIzinPulangCepatHariIniService($karyawan->id_karyawan);
        foreach ($jamKerjaHari as $key => $value) {
            if ($value['nama_hari'] == $hariIni && $value['is_24jam'] == 1) {
                $absensiTerakhir = Absensi::find()
                    ->where(['id_karyawan' => $karyawan->id_karyawan])
                    ->orderBy(['tanggal' => SORT_DESC])
                    ->one();

                $isPulangCepat = CekPengajuanKaryawanService::CekIzinPulangCepatHariIniService($karyawan->id_karyawan);

                return $this->render('absensi/absen-24jam', [
                    'model' => $model,
                    'jamKerjaKaryawan' => $value,
                    'absensiTerakhir' => $absensiTerakhir,
                    'isPulangCepat' => $isPulangCepat,
                    'masterLokasi' => $masterLokasi,
                ]);
            }
        }

        $jamKerjaToday = JamKerjaKaryawanService::getJamKerjaKaryawanHariIni($jamKerjaKaryawan, $jamKerjaHari, $hariIni, $manual_shift);

        $dataJam = [
            'liveness_passed' => $adaWajahTeregistrasi ? 1 : 0,
            'karyawan' => $jamKerjaKaryawan,
            'today' => $jamKerjaToday,
            'lembur' => $hasilLembur,
            'manual_shift' => $manual_shift
        ];


        if ($this->handlePostAbsenMasuk($model, $comporess, $manual_shift, $setting_fr, $verificationFr)) {
            // jika sukses post dan simpan data, redirect atau render sesuai kebutuhan
            return $this->redirect(['absen-masuk']); // sesuaikan redirect
        }


        return $this->handleAbsenView(
            $model,
            $absensiToday,
            $dataProvider,
            $jamKerjaKaryawan,
            $dataJam,
            $jamKerjaToday,
            $masterLokasi,
            $isTerlambatActive,
            $isPulangCepat,
            $manual_shift,
            $setting_fr
        );
    }


    protected function handlePostAbsenMasuk($model, $comporess, $manual_shift, $setting_fr, $verificationFr)
    {
        if (!$this->request->isPost) {
            return false;
        }

        $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();
        $isAda = Absensi::find()->where(['id_karyawan' => $karyawan->id_karyawan, 'tanggal' => date('Y-m-d')])->one();


        $model->id_karyawan = $karyawan->id_karyawan;
        $model->tanggal = date('Y-m-d');
        $model->kode_status_hadir = "H";
        $model->jam_masuk = date('H:i:s');
        $model->is_lembur = Yii::$app->request->post('Absensi')['is_lembur'] ?? 0;
        $model->is_wfh = Yii::$app->request->post('Absensi')['is_wfh'] ?? 0;
        $model->keterangan = $model->is_lembur ? 'Lembur' : '-';
        $model->latitude = Yii::$app->request->post('Absensi')['latitude'];
        $model->longitude = Yii::$app->request->post('Absensi')['longitude'];
        $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one();
        $model->foto_masuk = Yii::$app->request->post('Absensi')['foto_masuk'] ?? null;
        $model->liveness_passed = Yii::$app->request->post('Absensi')['liveness_passed'] ?? null;
        if ($jamKerjaKaryawan && $jamKerjaKaryawan->is_shift == 1) {
            if ($manual_shift == 1) {
                $tanggalHariIni = date('Y-m-d');
                $jadwalShiftHariIni = JadwalShift::find()
                    ->where([
                        'id_karyawan' => $jamKerjaKaryawan->id_karyawan,
                        'tanggal' => $tanggalHariIni
                    ])
                    ->asArray()
                    ->one();

                if ($jadwalShiftHariIni) {
                    $shif = ShiftKerja::find()->where(['id_shift_kerja' => $jadwalShiftHariIni['id_shift_kerja']])->one();
                    if ($shif) {
                        $model->id_shift = $shif->id_shift_kerja;
                    }
                    if ($shif && strtotime($model->jam_masuk) > strtotime($shif->jam_masuk)) {
                        $model->is_terlambat = 1;
                        $lamaTerlambat = gmdate('H:i:s', strtotime($model->jam_masuk) - strtotime($shif->jam_masuk));
                        $model->lama_terlambat = $lamaTerlambat;
                    }
                }
            } else {
                $AbsensiPost = Yii::$app->request->post('Absensi');
                $shiftMasuk = $AbsensiPost['id_shift'] ?? null;

                if ($shiftMasuk) {
                    $dataShift = ShiftKerja::find()->where(['id_shift_kerja' => $shiftMasuk])->one();
                    if ($dataShift) {
                        $model->id_shift = $dataShift->id_shift_kerja;
                    }
                    $jamMasukShift = $dataShift['jam_masuk'];

                    $jamSekarang = date('H:i:s');

                    if (strtotime($jamSekarang) > strtotime($jamMasukShift)) {
                        $selisihDetik = strtotime($jamSekarang) - strtotime($jamMasukShift);
                        $jamTerlambat = floor($selisihDetik / 3600);
                        $menitTerlambat = floor(($selisihDetik % 3600) / 60);

                        $model->is_terlambat = 1;
                        $model->lama_terlambat = "$jamTerlambat:$menitTerlambat:00";
                    } else {
                        Yii::$app->session->setFlash('success', 'Anda Tidak Terlambat');
                    }
                }
            }
        } else {
            $JamKerja = JadwalKerja::find()->where(['id_jam_kerja' => $jamKerjaKaryawan['id_jam_kerja']])->one();
            if ($JamKerja && strtotime($model->jam_masuk) > strtotime($JamKerja->jam_masuk)) {
                $model->is_terlambat = 1;
                $lamaTerlambat = gmdate('H:i:s', strtotime($model->jam_masuk) - strtotime($JamKerja->jam_masuk));
                $model->lama_terlambat = $lamaTerlambat;
            }
        }

        if (!$isAda) {
            if ($setting_fr == 1) {


                $similarity = $this->calculateFaceSimilarity(
                    $karyawan['liveness_passed'],
                    $model['liveness_passed']
                );



                $similar = $similarity ? round($similarity) : 0;


                if ($similar >= Yii::$app->params['minimal_kemiripan_fr']) {
                    $model->similarity = $similar;

                    if ($model->save()) {
                        $absensiKeduaTerakhir = Absensi::find()
                            ->where(['id_karyawan' => $karyawan->id_karyawan])
                            ->orderBy(['tanggal' => SORT_DESC])
                            ->offset(1)
                            ->limit(1)
                            ->one();

                        if ($absensiKeduaTerakhir && !empty($absensiKeduaTerakhir->foto_masuk)) {
                            $absensiKeduaTerakhir->foto_masuk = null;
                            $absensiKeduaTerakhir->save(false);
                        }

                        Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil dengan kemiripan wajah sebesar ' . $similar . '%');
                        return true;
                    }
                } else {
                    if ($verificationFr == 1) {
                        $similarPercentage = $similar;
                        Yii::$app->session->setFlash(
                            'error',
                            'Absen masuk tidak berhasil. Tingkat kemiripan wajah Anda hanya ' . $similarPercentage . '%. ' .
                                'Silakan ulangi pemindaian wajah hingga mencapai nilai minimal '
                        );
                    } else {
                        $similarPercentage = $similar;
                        $model->similarity = $similarPercentage;

                        if ($model->save()) {
                            $absensiKeduaTerakhir = Absensi::find()
                                ->where(['id_karyawan' => $karyawan->id_karyawan])
                                ->orderBy(['tanggal' => SORT_DESC])
                                ->offset(1)
                                ->limit(1)
                                ->one();

                            if ($absensiKeduaTerakhir && !empty($absensiKeduaTerakhir->foto_masuk)) {
                                $absensiKeduaTerakhir->foto_masuk = null;
                                $absensiKeduaTerakhir->save(false);
                            }

                            Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil dengan kemiripan wajah sebesar ' . $similarPercentage . '%');
                            return true;
                        }
                    }
                }
            } else {
                $model->foto_masuk = null;
                $model->similarity = 0;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil');
                    return true;
                } else {
                    Yii::$app->session->setFlash('error', 'Absen Masuk tidak Berhasil');
                }
            }
        } else {
            Yii::$app->session->setFlash('success', 'Absen Masuk Anda Sudah Ada');
            return true;
        }

        return false;
    }




    private function handleAbsenView($model, $absensiToday, $dataProvider, $jamKerjaKaryawan, $dataJam, $jamKerjaToday, $masterLokasi, $isTerlambatActive, $isPulangCepat, $manual_shift, $setting_fr)
    {
        $tanggalHariIni = date('Y-m-d');

        // Cek lembur hari ini
        $adaLemburHariIni = false;
        if (!empty($dataJam['lembur'])) {
            foreach ($dataJam['lembur'] as $lembur) {
                if (isset($lembur['tanggal']) && $lembur['tanggal'] == $tanggalHariIni) {
                    $adaLemburHariIni = true;
                    break;
                }
            }
        }

        if ($adaLemburHariIni && !$jamKerjaToday) {
            return $this->render('absensi/absen-lembur', compact(
                'model',
                'absensiToday',
                'dataProvider',
                'jamKerjaKaryawan',
                'dataJam',
                'isPulangCepat',
                'manual_shift'
            ));
        }

        // Cek WFH
        $is_wfh = false;
        $wfhData = $this->wfhData($jamKerjaKaryawan->id_karyawan);
        foreach ($wfhData as $data) {
            $tanggalArray = json_decode($data['tanggal_array'], true);
            if (!empty($tanggalArray) && in_array($tanggalHariIni, $tanggalArray)) {
                $is_wfh = true;
                break;
            }
        }

        if ($is_wfh) {
            return $this->render('absensi/absen-wfh', compact(
                'model',
                'absensiToday',
                'dataProvider',
                'jamKerjaKaryawan',
                'dataJam',
                'isTerlambatActive',
                'isPulangCepat',
                'jamKerjaToday',
                'masterLokasi',
                'manual_shift'
            ));
        }

        // Default: cek face recognition aktif atau tidak
        $view = $setting_fr == 1 ? 'absensi/absen-masuk' : 'absensi/absen-masuk_old';

        return $this->render($view, compact(
            'model',
            'absensiToday',
            'dataProvider',
            'jamKerjaKaryawan',
            'dataJam',
            'isTerlambatActive',
            'isPulangCepat',
            'jamKerjaToday',
            'masterLokasi',
            'manual_shift'
        ));
    }


    public function actionAbsenTerlambat()
    {
        $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();
        $comporess = new CompressImagesHelper();
        $isAda = Absensi::find()->where(['id_karyawan' => $karyawan->id_karyawan, 'tanggal' => date('Y-m-d')])->one();
        $setting_fr = FaceRecognationHelper::cekFaceRecognation();
        $model = new Absensi();
        if ($this->request->isPost) {
            $model->id_karyawan = $karyawan->id_karyawan;
            $model->tanggal = date('Y-m-d');
            $model->kode_status_hadir = "H";
            $model->jam_masuk = date('H:i:s');
            $model->latitude = Yii::$app->request->post('Absensi')['latitude'];
            $model->longitude = Yii::$app->request->post('Absensi')['longitude'];
            $model->alasan_terlambat = Yii::$app->request->post('Absensi')['alasan_terlambat'];
            $base64Image = Yii::$app->request->post('Absensi')['foto_masuk'] ?? '-';
            $model->liveness_passed = Yii::$app->request->post('Absensi')['liveness_passed'] ?? null;
            $verificationFr = FaceRecognationHelper::cekVerificationFr();


            if ($base64Image) {
                $compressedImage = $comporess->compressBase64Image($base64Image);
                $model->foto_masuk = $compressedImage;
            }
            $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one();

            if ($jamKerjaKaryawan['is_shift'] == 1) {

                $tanggalHariIni = date('Y-m-d');
                $jadwalShiftHariIni = JadwalShift::find()
                    ->where(['id_karyawan' => $jamKerjaKaryawan['id_karyawan'], 'tanggal' => $tanggalHariIni])
                    ->asArray()
                    ->one();

                $shif = ShiftKerja::find()->where(['id_shift_kerja' => $jadwalShiftHariIni['id_shift_kerja']])->one();
                if ($shif) {
                    $model->id_shift = $shif->id_shift_kerja;
                }
                if (strtotime($model['jam_masuk']) > strtotime($shif->jam_masuk)) {
                    $model->is_terlambat = 1;
                    $lamaTerlambat = gmdate('H:i:s', strtotime($model->jam_masuk) - strtotime($shif['jam_masuk']));
                    $model->lama_terlambat = $lamaTerlambat;
                }
            } else {
                $JamKerja = JadwalKerja::find()->where(['nama_hari' => date('N'), 'id_jam_kerja' => $jamKerjaKaryawan['id_jam_kerja']])->one();
                if (strtotime($model['jam_masuk']) > strtotime($JamKerja->jam_masuk)) {

                    $model->is_terlambat = 1;
                    $lamaTerlambat = gmdate('H:i:s', strtotime($model->jam_masuk) - strtotime($JamKerja['jam_masuk']));
                    $model->lama_terlambat = $lamaTerlambat;
                }
            }

            if (!$isAda) {
                if ($setting_fr == 1) {

                    $similarity = $this->calculateFaceSimilarity(
                        $karyawan['liveness_passed'],
                        $model['liveness_passed']
                    );




                    // Yii::debug("Kemiripan wajah: " . $similarity . "%");
                    $similar = $similarity ? round($similarity) : 0;

                    // /jika mirip
                    if ($similar >= Yii::$app->params['minimal_kemiripan_fr']) {
                        // Hitung persentase kemiripan (dikalikan 100 dan dibulatkan)
                        $similarPercentage = round($similar);
                        $model->similarity = $similarPercentage;

                        if ($model->save()) {
                            $absensiKeduaTerakhir = Absensi::find()
                                ->where(['id_karyawan' => $karyawan->id_karyawan])
                                ->orderBy(['tanggal' => SORT_DESC])
                                ->offset(1) // Lewati yang paling terbaru
                                ->limit(1)  // Ambil 1 data (yaitu yang ke-2 terbaru)
                                ->one();


                            if ($absensiKeduaTerakhir && !empty($absensiKeduaTerakhir->foto_masuk)) {
                                $absensiKeduaTerakhir->foto_masuk = null;
                                $absensiKeduaTerakhir->save(false); // Simpan tanpa validasi
                            }

                            Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil  dengan    kemiripan wajah sebesar ' . $similarPercentage . '%');
                        }
                    } else {
                        // check verivikasi_fr
                        if ($verificationFr == 1) {
                            $similarPercentage = round($similar);
                            Yii::$app->session->setFlash(
                                'error',
                                'Absen masuk tidak berhasil. Tingkat kemiripan wajah Anda hanya ' . $similarPercentage . '%. '
                                    . 'Silakan ulangi pemindaian wajah hingga mencapai nilai minimal '
                            );
                        } else {
                            $similarPercentage = round($similar);
                            $model->similarity = $similarPercentage;

                            if ($model->save()) {
                                $absensiKeduaTerakhir = Absensi::find()
                                    ->where(['id_karyawan' => $karyawan->id_karyawan])
                                    ->orderBy(['tanggal' => SORT_DESC])
                                    ->offset(1) // Lewati yang paling terbaru
                                    ->limit(1)  // Ambil 1 data (yaitu yang ke-2 terbaru)
                                    ->one();


                                if ($absensiKeduaTerakhir && !empty($absensiKeduaTerakhir->foto_masuk)) {
                                    $absensiKeduaTerakhir->foto_masuk = null;
                                    $absensiKeduaTerakhir->save(false); // Simpan tanpa validasi
                                }

                                Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil  dengan    kemiripan wajah sebesar ' . $similarPercentage . '%');
                            }
                        }
                    }
                } else {
                    $model->foto_masuk = null;
                    $model->similarity = 0;
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil');
                    } else {
                        Yii::$app->session->setFlash('error', 'Absen Masuk tidak Berhasil');
                    }
                }
            } else {
                Yii::$app->session->setFlash('success', 'Absen Masuk Anda Sudah Ada');
            }
        }

        return $this->redirect(['absen-masuk']);
    }

    public function actionAbsenTerlalujauh()
    {
        $manual_shift = ManualSHiftHelper::isManual(); // nilai 0 atau 1
        $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();
        $isAda = Absensi::find()->where(['id_karyawan' => $karyawan->id_karyawan, 'tanggal' => date('Y-m-d')])->one();
        $comporess = new CompressImagesHelper();
        $verificationFr = FaceRecognationHelper::cekVerificationFr();

        $model = new Absensi();
        if ($this->request->isPost) {

            $base64Image = Yii::$app->request->post('Absensi')['foto_masuk'] ?? '-';
            if ($base64Image) {
                $compressedImage = $comporess->compressBase64Image($base64Image);
                $model->foto_masuk = $compressedImage;
            }
            $model->id_karyawan = $karyawan->id_karyawan;
            $model->tanggal = date('Y-m-d');
            $model->kode_status_hadir = "H";
            $model->jam_masuk = date('H:i:s');
            $model->latitude = Yii::$app->request->post('Absensi')['latitude'];
            $model->longitude = Yii::$app->request->post('Absensi')['longitude'];
            $model->alasan_terlalu_jauh = Yii::$app->request->post('Absensi')['alasan_terlalu_jauh'];
            $model->foto_masuk = Yii::$app->request->post('Absensi')['foto_masuk'] ?? null;
            $model->liveness_passed = Yii::$app->request->post('Absensi')['liveness_passed'] ?? null;

            $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one();

            if ($jamKerjaKaryawan && $jamKerjaKaryawan->is_shift == 1) {

                // Jika sistem pakai shift dan manual_shift aktif, baru cek keterlambatan
                if ($manual_shift == 1) {
                    $tanggalHariIni = date('Y-m-d');
                    $jadwalShiftHariIni = JadwalShift::find()
                        ->where([
                            'id_karyawan' => $jamKerjaKaryawan->id_karyawan,
                            'tanggal' => $tanggalHariIni
                        ])
                        ->asArray()
                        ->one();

                    if ($jadwalShiftHariIni) {
                        $shif = ShiftKerja::find()->where(['id_shift_kerja' => $jadwalShiftHariIni['id_shift_kerja']])->one();
                        if ($shif) {
                            $model->id_shift = $shif->id_shift_kerja;
                        }
                        if ($shif && strtotime($model->jam_masuk) > strtotime($shif->jam_masuk)) {
                            $model->is_terlambat = 1;
                            $lamaTerlambat = gmdate('H:i:s', strtotime($model->jam_masuk) - strtotime($shif->jam_masuk));
                            $model->lama_terlambat = $lamaTerlambat;
                        }
                    }
                } else {
                    $AbsensiPost = Yii::$app->request->post('Absensi');
                    $shiftMasuk = $AbsensiPost['id_shift'] ?? null;

                    if ($shiftMasuk) {
                        $dataShift = ShiftKerja::find()->where(['id_shift_kerja' => $shiftMasuk])->one();
                        if ($dataShift) {
                            $model->id_shift = $dataShift->id_shift_kerja;
                        }
                        $jamMasukShift = $dataShift['jam_masuk']; // Misalnya '15:00:00'

                        $jamSekarang = date('H:i:s'); // '14:21:00'

                        // Konversi ke timestamp untuk perbandingan
                        $timestampShift = strtotime($jamMasukShift);
                        $timestampSekarang = strtotime($jamSekarang);

                        if ($timestampSekarang > $timestampShift) {
                            // Hitung keterlambatan dalam detik
                            $selisihDetik = $timestampSekarang - $timestampShift;

                            // Konversi ke jam dan menit
                            $jamTerlambat = floor($selisihDetik / 3600);
                            $menitTerlambat = floor(($selisihDetik % 3600) / 60);

                            $model->is_terlambat = 1;
                            $model->lama_terlambat = "$jamTerlambat:$menitTerlambat:00";
                        } else {
                            Yii::$app->session->setFlash('success', 'Anda Tidak Terlambat');
                        }
                    }
                }
            } else {
                // Jika bukan shift, bisa langsung cek keterlambatan tanpa perlu cek manual_shift

                $JamKerja = JadwalKerja::find()->where(['id_jam_kerja' => $jamKerjaKaryawan['id_jam_kerja']])->one();
                if ($JamKerja && strtotime($model->jam_masuk) > strtotime($JamKerja->jam_masuk)) {
                    $model->is_terlambat = 1;
                    $lamaTerlambat = gmdate('H:i:s', strtotime($model->jam_masuk) - strtotime($JamKerja->jam_masuk));
                    $model->lama_terlambat = $lamaTerlambat;
                }
            }

            // Simpan data absensi jika belum ada
            if (!$isAda) {


                $setting_fr = FaceRecognationHelper::cekFaceRecognation();

                if ($setting_fr == 1) {

                    $similarity = $this->calculateFaceSimilarity(
                        $karyawan['liveness_passed'],
                        $model['liveness_passed']
                    );

                    // Yii::debug("Kemiripan wajah: " . $similarity . "%");
                    $similar = $similarity ? round($similarity) : 0;

                    // jika mirip
                    if ($similar >= Yii::$app->params['minimal_kemiripan_fr']) {
                        // Hitung persentase kemiripan (dikalikan 100 dan dibulatkan)
                        $similarPercentage = $similar;
                        $model->similarity = $similarPercentage;

                        if ($model->save()) {
                            $absensiKeduaTerakhir = Absensi::find()
                                ->where(['id_karyawan' => $karyawan->id_karyawan])
                                ->orderBy(['tanggal' => SORT_DESC])
                                ->offset(1) // Lewati yang paling terbaru
                                ->limit(1)  // Ambil 1 data (yaitu yang ke-2 terbaru)
                                ->one();


                            if ($absensiKeduaTerakhir && !empty($absensiKeduaTerakhir->foto_masuk)) {
                                $absensiKeduaTerakhir->foto_masuk = null;
                                $absensiKeduaTerakhir->save(false); // Simpan tanpa validasi
                            }

                            Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil  dengan    kemiripan wajah sebesar ' . $similarPercentage . '%');
                        }
                    } else {
                        // check verivikasi_fr
                        if ($verificationFr == 1) {
                            $similarPercentage = $similar;
                            Yii::$app->session->setFlash(
                                'error',
                                'Absen masuk tidak berhasil. Tingkat kemiripan wajah Anda hanya ' . $similarPercentage . '%. '
                                    . 'Silakan ulangi pemindaian wajah hingga mencapai nilai minimal '
                            );
                        } else {
                            $similarPercentage = $similar;
                            $model->similarity = $similarPercentage;

                            if ($model->save()) {
                                $absensiKeduaTerakhir = Absensi::find()
                                    ->where(['id_karyawan' => $karyawan->id_karyawan])
                                    ->orderBy(['tanggal' => SORT_DESC])
                                    ->offset(1) // Lewati yang paling terbaru
                                    ->limit(1)  // Ambil 1 data (yaitu yang ke-2 terbaru)
                                    ->one();


                                if ($absensiKeduaTerakhir && !empty($absensiKeduaTerakhir->foto_masuk)) {
                                    $absensiKeduaTerakhir->foto_masuk = null;
                                    $absensiKeduaTerakhir->save(false); // Simpan tanpa validasi
                                }

                                Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil  dengan    kemiripan wajah sebesar ' . $similarPercentage . '%');
                            }
                        }
                    }
                } else {
                    $model->foto_masuk = null;
                    $model->similarity = 0;
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil');
                    } else {
                        Yii::$app->session->setFlash('error', 'Absen Masuk tidak Berhasil');
                    }
                }
            } else {
                Yii::$app->session->setFlash('success', 'Absen Masuk Anda Sudah Ada');
            }
        }

        return $this->redirect(['absen-masuk']);
    }


    public function actionAbsenPulang()
    {
        $perhitungaLembur = SettinganUmum::find()->where(['kode_setting' => Yii::$app->params['kalkulasi_jam_lembur']])->asArray()->one();

        $karyawanId = Yii::$app->user->identity->id_karyawan;
        $karyawan = Karyawan::find()->select('id_karyawan')->where(['id_karyawan' => $karyawanId])->asArray()->one();
        $MinimumMinuteLembur = MasterKode::find()->where(['nama_group' => Yii::$app->params['minimum-menit-lembur']])->asArray()->one();
        // dd($MinimumMinuteLembur)?;
        if (!$karyawan) {
            return $this->redirect(['absen-masuk']);
        }

        $model = Absensi::find()->where(['id_karyawan' => $karyawan['id_karyawan'], 'tanggal' => date('Y-m-d')])->one();
        $settinganUmumlembur = SettinganUmum::find()->where(['kode_setting' => Yii::$app->params['ajukan_lembur']])->asArray()->one();

        // Check if overtime requests are allowed
        if ($settinganUmumlembur && $settinganUmumlembur['nilai_setting'] == 0) {
            $jamSekarang = date('H:i:s');
            $jamKerja = JamKerjaKaryawan::find()->select(['is_shift', 'id_jam_kerja'])->where(['id_karyawan' => $karyawan['id_karyawan']])->asArray()->one();

            if ($jamKerja) {
                $jam_pulang_seharusnya = $this->getJamPulangSeharusnya($model, $jamKerja);
                if ($jam_pulang_seharusnya && strtotime($jamSekarang) > strtotime($jam_pulang_seharusnya)) {
                    $kelebihanWaktu = $this->hitungKelebihanWaktu($jamSekarang, $jam_pulang_seharusnya);
                }
            }
        }

        // Handle post request to save the absence record
        if ($this->request->isPost) {
            $model->jam_pulang = date('H:i:s');
            $model->kelebihan_jam_pulang = $kelebihanWaktu ?? null;

            // Convert "03:00" to total minutes
            if (!empty($kelebihanWaktu)) {
                list($hours, $minutes) = explode(':', $kelebihanWaktu);
                $totalMinutes = ((int)$hours * 60) + (int)$minutes;
            } else {
                $totalMinutes = 0;
            }


            if ($totalMinutes >  (int) $MinimumMinuteLembur['nama_kode']) {
                $hasil = LemburService::getHitunganJamLembur($totalMinutes, $perhitungaLembur['nilai_setting']);
                $jamFloat = round($hasil / 60, 2); // dibulatkan 2 angka di belakang koma

                if ($jamFloat > 0) {
                    $RekapLembur = new RekapLembur();
                    $RekapLembur->id_karyawan = $karyawan['id_karyawan'];
                    $RekapLembur->tanggal = date('Y-m-d');
                    $RekapLembur->jam_total = (int) $jamFloat;
                    $RekapLembur->save();
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Absen Pulang Berhasil');
                return $this->redirect(['absen-masuk']);
            }
        } else {
            $model->loadDefaultValues();
        }


        return $this->redirect(['absen-masuk']);
    }



    //============================================END ABSEN REGULAR============================================


    protected function calculateFaceSimilarity($descriptor1, $descriptor2)
    {


        $apiUrl = 'http://face-recognation.profaskes.id/compare';

        // Format descriptor sebagai string comma-separated
        $postData = [
            'descriptor1' => is_array($descriptor1) ? implode(',', $descriptor1) : $descriptor1,
            'descriptor2' => is_array($descriptor2) ? implode(',', $descriptor2) : $descriptor2
        ];

        $jsonData = json_encode($postData);

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("API error: HTTP $httpCode");
        }

        $result = json_decode($response, true);

        if (!isset($result['similarity'])) {
            throw new Exception('Invalid API response');
        }

        return (float) $result['similarity'];
    }

    //============================================ABSENSI 24 JAM ============================================


    public function actionAbsen24jam()
    {

        $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();
        $isAda = Absensi::find()->where(['id_karyawan' => $karyawan->id_karyawan, 'tanggal' => date('Y-m-d')])->one();
        $model = new Absensi();
        if ($this->request->isPost) {
            $model->id_karyawan = $karyawan->id_karyawan;
            $model->tanggal = date('Y-m-d');
            $model->kode_status_hadir = "H";
            $model->jam_masuk = date('H:i:s');
            $model->latitude = Yii::$app->request->post('Absensi')['latitude'];
            $model->longitude = Yii::$app->request->post('Absensi')['longitude'];
            $model->is_24jam = 1;
            if (!$isAda) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil');
                }
            } else {
                Yii::$app->session->setFlash('success', 'Absen Masuk Anda Sudah Ada');
            }
        }

        return $this->redirect(['absen-masuk']);
    }


    public function actionAbsen24jamPulang()
    {
        $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();
        $absensiTerakhir = Absensi::find()
            ->where(['id_karyawan' => $karyawan->id_karyawan])
            ->orderBy(['tanggal' => SORT_DESC])
            ->one();            // $absens
        if ($this->request->isPost) {
            $absensiTerakhir->jam_pulang = date('H:i:s');
            $absensiTerakhir->tanggal_pulang = date('Y-m-d');
            if ($absensiTerakhir->save()) {
                return $this->redirect(['absen-masuk']);
            }
        } else {
            $absensiTerakhir->loadDefaultValues();
        }

        return $this->redirect(['absen-masuk']);
    }
    //============================================ABSENSI 24 JAM ============================================



    private function getJamPulangSeharusnya($model, $jamKerja)
    {
        if ($jamKerja['is_shift'] == 1 && $model['id_shift']) {
            $shiftKaryawanHariIni = ShiftKerja::find()->where(['id_shift_kerja' => $model['id_shift']])->one();
            return $shiftKaryawanHariIni ? $shiftKaryawanHariIni['jam_keluar'] : null;
        } else {
            $jadwalKerja = JadwalKerja::find()
                ->where(['id_jam_kerja' => $jamKerja['id_jam_kerja'], 'nama_hari' => date('N')])
                ->asArray()
                ->one();
            return $jadwalKerja ? $jadwalKerja['jam_keluar'] : null;
        }
    }

    private function hitungKelebihanWaktu($jamSekarang, $jamPulangSeharusnya)
    {
        $selisihDetik = strtotime($jamSekarang) - strtotime($jamPulangSeharusnya);
        $jam = floor($selisihDetik / 3600);
        $menit = floor(($selisihDetik % 3600) / 60);
        return sprintf('%02d:%02d', $jam, $menit);
    }

    public function actionCreate()
    {
        $model = new Absensi();

        if ($this->request->isPost) {
            $lampiranFile = UploadedFile::getInstance($model, 'lampiran');
            Yii::$app->request->post('keterangan');


            if ($model->load($this->request->post())) {
                $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();

                $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one();
                if ($jamKerjaKaryawan->jamKerja) {
                    $hariIni = date('w') == '0' ? 0 : date('w');
                    $jadwalKeja = JadwalKerja::find()->asArray()->where(['id_jam_kerja' => $jamKerjaKaryawan->id_jam_kerja, 'nama_hari' => $hariIni])->one();
                    if ($jadwalKeja) {
                        $model->jam_masuk = $jadwalKeja['jam_masuk'];
                        $model->jam_pulang = $jadwalKeja['jam_keluar'];
                    } else {
                        $model->jam_masuk = "00:00:00";
                        $model->jam_pulang = "00:00:00";;
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Jam Kerja Anda Belum di Set');
                }

                $model->id_karyawan = $karyawan->id_karyawan;
                $model->tanggal = date('Y-m-d');

                $model->kode_status_hadir = Yii::$app->request->post('statusHadir');
                $this->saveImage($model, $lampiranFile, 'lampiran');
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        $this->layout = 'mobile-main';
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionTidakHadir($id_karyawan)
    {
        $absensi = Absensi::find()->where(['id_karyawan' => $id_karyawan, 'tanggal' => date('Y-m-d')])->one();
        $this->layout = 'mobile-main';
        return $this->render('tidak-hadir/index', [
            'model' => $absensi
        ]);
    }

    public function actionPulangCepat()
    {
        $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();
        $model =  IzinPulangCepat::find()->where(['id_karyawan' => $karyawan->id_karyawan, 'tanggal' => date('Y-m-d')])->one();

        $this->layout = 'mobile-main';
        return $this->render('pulang-cepat/index', [
            'model' => $model
        ]);
    }
    public function actionPulangCepatCreate()
    {
        $model = new IzinPulangCepat();


        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one()['id_karyawan'];
                $model->id_karyawan = $karyawan;
                $model->tanggal = date('Y-m-d');
                if ($model->save()) {

                    $useMessage = new UseMessageHelper();
                    $adminUsers = $useMessage->getUserAtasanReceiver($model->id_karyawan);


                    $params = [
                        'judul' => 'Pengajuan   pulang cepat',
                        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan WFH.',
                        'nama_transaksi' => "/panel/tanggapan/pulang-cepat-view?id",
                        'id_transaksi' => $model['id_izin_pulang_cepat'],
                    ];

                    $this->sendNotif($params, $model, $adminUsers, "Pengajuan pulang cepat Baru Dari " . $model->karyawan->nama);


                    Yii::$app->session->setFlash('success', 'Berhasil Mengajuakan Pengajuan  Pulang Cepat, Menunggu Konfirmasi Admin');
                    return $this->redirect(['absen-masuk']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Mengajuakan Pengajuan  Pulang Cepat');
                    return $this->redirect(['absen-masuk']);
                }
            }
        }


        $this->layout = 'mobile-main';
        return $this->render('pulang-cepat/create', [
            'model' => $model
        ]);
    }


    public function actionLihatShift($id_karyawan, $params = null)
    {
        if ($params != null) {
            $bulan = $params['bulan'];
            $tahun = $params['tahun'];
        } else {

            $bulan = date('m');
            $tahun = date('Y');
        }
        $this->layout = 'mobile-main';
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, 1, $tahun));
        $lastDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, date('t', mktime(0, 0, 0, $bulan, 1, $tahun)), $tahun));

        $model = JadwalShift::find()
            ->where(['id_karyawan' => $id_karyawan])
            ->andWhere(['between', 'tanggal', $firstDayOfMonth, $lastDayOfMonth])
            ->all();
        return $this->render('lihat-shift/index', [
            'model' => $model
        ]);
    }


    // change shidt

    public function actionChangeShift($id_karyawan)
    {
        $this->layout = 'mobile-main';

        $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $id_karyawan])->one();
        $allDataShift = ShiftKerja::find()->asArray()->all();
        $currentShift = [];
        if ($jamKerjaKaryawan && $jamKerjaKaryawan['is_shift'] == 1) {
            $tanggalHariIni = date('Y-m-d');
            $jadwalShiftHariIni = JadwalShift::find()
                ->where(['id_karyawan' => $jamKerjaKaryawan['id_karyawan'], 'tanggal' => $tanggalHariIni])
                ->one();


            if ($jadwalShiftHariIni) {

                $currentShift = ShiftKerja::find()
                    ->where(['id_shift_kerja' => $jadwalShiftHariIni['id_shift_kerja']])
                    ->one();
            }
        }


        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $selectedShift = $post['shift_kerja']; // dari form
            $tanggalMulai = $post['tanggal_awal']; // dari form
            $tanggalSelesai = $post['tanggal_akhir']; // dari form

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
                    'id_karyawan' => $id_karyawan,
                    'tanggal' => $tanggal
                ]);

                if (!$jadwal) {
                    $jadwal = new JadwalShift();
                    $jadwal->id_karyawan = $id_karyawan;
                    $jadwal->tanggal = $tanggal;
                }

                $jadwal->id_shift_kerja = $selectedShift;

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

            return $this->redirect(['index', 'id_karyawan' => $id_karyawan]);
        }

        return $this->render('shift/change-shift/index', [
            'currentShift' => $currentShift,
            'allDataShift' => $allDataShift,
            'id_karyawan' => $id_karyawan,

        ]);
    }
    public function actionPengajuanShift()
    {
        $this->layout = 'mobile-main';
        $allDataShift = ShiftKerja::find()->asArray()->all();

        $model = new PengajuanShift();


        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model->load($post);
            $model->id_karyawan = Yii::$app->user->identity->id_karyawan;
            $model->diajukan_pada = date('Y-m-d');
            $model->status = 0;
            if ($model->save()) {

                $useMessage = new UseMessageHelper();
                $adminUsers = $useMessage->getUserAtasanReceiver($model->id_karyawan);


                $params = [
                    'judul' => 'Pengajuan  Shift',
                    'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan Shift.',
                    'nama_transaksi' => "/panel/tanggapan/shift-view?id_pengajuan_shift",
                    'id_transaksi' => $model['id_pengajuan_shift'],
                ];

                $this->sendNotif($params, $model, $adminUsers, "Pengajuan shift Baru Dari " . $model->karyawan->nama);


                Yii::$app->session->setFlash('success', 'Pengajuan Shift berhasil dikirim.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal mengirim pengajuan shift.');
            }

            return $this->redirect(['pengajuan-shift']);
        }
        return $this->render('shift/pengajuan-shift/index', [
            'model' => $model,
            'allDataShift' => $allDataShift,
            'id_karyawan' => Yii::$app->user->identity->id_karyawan,

        ]);
    }



    // ?================================Expirience`
    public function actionExpirience()
    {
        $this->layout = 'mobile-main';
        $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();
        $pengalamanKerja = PengalamanKerja::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $riwayatPendidikan = RiwayatPendidikan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $keluarga = DataKeluarga::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $RiwayatPelatihan = RiwayatPelatihan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $RiwayatKesehatan = RiwayatKesehatan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $model = new karyawan();



        return $this->render('expirience/index', compact('karyawan', 'pengalamanKerja', 'riwayatPendidikan', 'keluarga', 'RiwayatPelatihan', 'RiwayatKesehatan', 'model'));
    }

    // ! pekerjaan
    public function actionExpiriencePekerjaanCreate()
    {
        $this->layout = 'mobile-main';

        $model = new PengalamanKerja();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();

                $model->id_karyawan = $karyawan->id_karyawan;

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Menyimpa Data Pengalaman Kerja');
                    return $this->redirect(['expirience']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Menyimpa Data Pengalaman Kerja');
                    return $this->redirect(['expirience']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('expirience/data-pekerjaan/create', [
            'model' => $model,
        ]);
    }

    public function actionExpiriencePekerjaanUpdate($id)
    {
        $this->layout = 'mobile-main';
        $model = PengalamanKerja::findOne($id);
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->save();
                return $this->redirect(['expirience']);
            }
        }

        return $this->render('expirience/data-pekerjaan/update', [
            'model' => $model
        ]);
    }

    public function actionExpiriencePekerjaanDelete()
    {

        $this->layout = 'mobile-main';
        $id = Yii::$app->request->post('id');
        $model = PengalamanKerja::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data Pengalaman Kerja');
        return $this->redirect(['expirience']);
    }

    // !pendidikan
    public function actionExpiriencePendidikanCreate()
    {

        $this->layout = 'mobile-main';
        $model = new RiwayatPendidikan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();

                $model->id_karyawan = $karyawan->id_karyawan;
                if ($model->save()) {
                    return $this->redirect(['expirience']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('expirience/data-pendidikan/create', [
            'model' => $model,
        ]);
    }

    public function actionExpiriencePendidikanUpdate($id)
    {
        $this->layout = 'mobile-main';
        $model = RiwayatPendidikan::findOne($id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Mengubah Data Pendidikan');
                    return $this->redirect(['expirience']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Mengubah Data Pendidikan');
                    return $this->redirect(['expirience']);
                }
            }
        }

        return $this->render('expirience/data-pendidikan/update', [
            'model' => $model
        ]);
    }

    public function actionExpiriencePendidikanDelete()
    {

        $this->layout = 'mobile-main';
        $id = Yii::$app->request->post('id');
        $model = RiwayatPendidikan::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data Pendidikan');
        return $this->redirect(['expirience']);
    }

    //! data keluarga


    public function actionDataKeluargaCreate()
    {

        $this->layout = 'mobile-main';
        $model = new DataKeluarga();

        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {
                $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();

                $model->id_karyawan = $karyawan->id_karyawan;

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Menyimpa Data Keluarga');
                    return $this->redirect(['expirience']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Menyimpa Data Keluarga');
                    return $this->redirect(['expirience']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }


        return $this->render('expirience/data-keluarga/create', compact('model'));
    }


    public function actionDataKeluargaUpdate($id)
    {
        $this->layout = 'mobile-main';

        $model = DataKeluarga::findOne($id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Mengubah Data Keluarga');
                    return $this->redirect(['expirience']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Mengubah Data Keluarga');
                    return $this->redirect(['expirience']);
                }
            }
        }

        return $this->render('expirience/data-keluarga/update', [
            'model' => $model
        ]);
    }
    public function actionDataKeluargaDelete()
    {

        $this->layout = 'mobile-main';
        $id = Yii::$app->request->post('id');
        $model = DataKeluarga::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data Keluarga');
        return $this->redirect(['expirience']);
    }


    //! Pelatihan
    public function actionRiwayatPelatihanCreate()
    {

        $this->layout = 'mobile-main';
        $model = new RiwayatPelatihan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $lampiranFile = UploadedFile::getInstance($model, 'sertifikat');
                $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();

                $model->id_karyawan = $karyawan->id_karyawan;
                $this->saveImage($model, $lampiranFile, 'sertifikat');

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Menyimpa Data Riwayat Pelatihan');
                    return $this->redirect(['expirience']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Menyimpa Data Riwayat Pelatihan');
                    return $this->redirect(['expirience']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }


        return $this->render('expirience/riwayat-pelatihan/create', compact('model'));
    }


    public function actionRiwayatPelatihanUpdate($id)
    {
        $this->layout = 'mobile-main';

        $model = RiwayatPelatihan::findOne($id);
        $oldThumbnail = $model->sertifikat;
        if ($this->request->isPost) {
            $lampiranFile = UploadedFile::getInstance($model, 'sertifikat');
            if ($model->load($this->request->post())) {
                if (!$lampiranFile == null) {
                    $this->saveImage($model, $lampiranFile, 'sertifikat');
                    $this->deleteImage($oldThumbnail);
                } else {
                    $model->surat_dokter = $oldThumbnail;
                }

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Mengubah Data Riwayat Pelatihan');
                    return $this->redirect(['expirience']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Mengubah Data Riwayat Pelatihan');
                    return $this->redirect(['expirience']);
                }
            }
        }

        return $this->render('expirience/riwayat-pelatihan/update', [
            'model' => $model
        ]);
    }
    public function actionRiwayatPelatihanDelete()
    {

        $this->layout = 'mobile-main';
        $id = Yii::$app->request->post('id');
        $model = RiwayatPelatihan::findOne($id);
        $this->deleteImage($model->sertifikat);
        $model->delete();
        Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data Riwayat Pelatihan');
        return $this->redirect(['expirience']);
    }


    //! Kesehatan
    public function actionRiwayatKesehatanCreate()
    {

        $this->layout = 'mobile-main';
        $model = new RiwayatKesehatan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $lampiranFile = UploadedFile::getInstance($model, 'surat_dokter');

                $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();

                $model->id_karyawan = $karyawan->id_karyawan;
                $this->saveImage($model, $lampiranFile, 'surat_dokter');

                if ($model->save()) {

                    Yii::$app->session->setFlash('success', 'Berhasil Menyimpa Data Riwayat Kesehatan');
                    return $this->redirect(['expirience']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Menyimpa Data Riwayat Kesehatan');
                    return $this->redirect(['expirience']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }


        return $this->render('expirience/riwayat-kesehatan/create', compact('model'));
    }


    public function actionRiwayatKesehatanUpdate($id)
    {
        $this->layout = 'mobile-main';

        $model = RiwayatKesehatan::findOne($id);
        $oldThumbnail = $model->surat_dokter;
        if ($this->request->isPost) {
            $lampiranFile = UploadedFile::getInstance($model, 'surat_dokter');
            if ($model->load($this->request->post())) {
                if (!$lampiranFile == null) {
                    $this->saveImage($model, $lampiranFile, 'surat_dokter');
                    $this->deleteImage($oldThumbnail);
                } else {
                    $model->surat_dokter = $oldThumbnail;
                }
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Mengubah Data Riwayat Kesehatan');
                    return $this->redirect(['expirience']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Mengubah Data Riwayat Kesehatan');
                    return $this->redirect(['expirience']);
                }
            }
        }

        return $this->render('expirience/riwayat-kesehatan/update', [
            'model' => $model
        ]);
    }
    public function actionRiwayatKesehatanDelete()
    {

        $this->layout = 'mobile-main';
        $id = Yii::$app->request->post('id');
        $model = RiwayatKesehatan::findOne($id);
        $this->deleteImage($model->surat_dokter);
        $model->delete();

        Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data Riwayat Kesehatan');
        return $this->redirect(['expirience']);
    }

    //?========YOUR LOCATIONS
    public function actionYourLocation()
    {

        $this->layout = 'mobile-main';
        return $this->render('lokasi/index');
    }

    // ?========pengumuman
    public function actionPengumuman()
    {
        $pengumuman = Pengumuman::find()->orderBy(['dibuat_pada' => SORT_DESC])->all();
        $this->layout = 'mobile-main';
        return $this->render('pengumuman/index', compact('pengumuman'));
    }
    public function actionPengumumanView($id_pengumuman)
    {
        $pengumuman = Pengumuman::findOne($id_pengumuman);
        $this->layout = 'mobile-main';
        return $this->render('pengumuman/view', compact('pengumuman'));
    }



    // inbox
    public function actionInbox()
    {
        $this->layout = 'mobile-main';
        $messages = Message::find()
            ->select(['message.*', 'message_receiver.is_open'])
            ->innerJoin('message_receiver', 'message.id_message = message_receiver.message_id')
            ->where(['message_receiver.receiver_id' => Yii::$app->user->id])
            ->orderBy(['message.create_at' => SORT_DESC])
            ->asArray()
            ->all();

        return $this->render('inbox/index', [
            'messages' => $messages,
        ]);
    }


    public function actionOpenMessage()
    {
        if (Yii::$app->request->isPost) {
            $messageId = Yii::$app->request->post('messageId');
            $nama_transaksi = Yii::$app->request->post('nama_transaksi'); // Ambil messageId dari POST
            $id_transaksi = Yii::$app->request->post('id_transaksi'); // Ambil messageId dari POST
            $receiverId = Yii::$app->user->id; // Ambil ID penerima dari session
            $messageReceiver = MessageReceiver::find()
                ->where(['message_id' => $messageId, 'receiver_id' => $receiverId])
                ->one();

            if ($messageReceiver) {
                $messageReceiver->is_open = 1;
                $messageReceiver->open_at = new \yii\db\Expression('NOW()');
                // dd($nama_transaksi);
                if ($messageReceiver->save()) {
                    return $this->redirect($nama_transaksi . ($id_transaksi ? '=' . $id_transaksi : ''));
                }
            }
        }

        // Jika tidak berhasil, arahkan ke halaman error atau kembali ke halaman sebelumnya
        return $this->redirect(['/panel']); // Ganti dengan URL yang sesuai
    }



    // ?==============helper
    protected function findModel($id_absensi)
    {
        if (($model = Absensi::findOne(['id_absensi' => $id_absensi])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function saveImage($model, $uploadedFile, $type)
    {
        $uploadsDir =  Yii::getAlias('@webroot/uploads/' . $type . '/');
        if ($uploadedFile) {
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);
            }
            $fileName = $uploadsDir  . uniqid() . '.' . $uploadedFile->extension;
            if ($uploadedFile->saveAs($fileName)) {
                $model->{$type} = "uploads/{$type}/" . basename($fileName);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save the uploaded file.');
            }
        }
    }

    public function deleteImage($oldThumbnail)
    {
        $filePath = Yii::getAlias('@webroot') . '/' . $oldThumbnail;
        if ($oldThumbnail && file_exists($filePath)) {
            unlink($filePath);
            return true;
        } else {
            return true;
        }
    }

    public function wfhData($id_karyawan)
    {
        $wfhData = PengajuanWfh::find()->asArray()->where(['id_karyawan' => $id_karyawan, 'status' => 1])->all();
        $today = date('Y-m-d'); // Ambil tanggal hari ini

        $result = [];

        foreach ($wfhData as $data) {
            // Decode tanggal_array
            $tanggalArray = json_decode($data['tanggal_array'], true);

            // Pastikan tanggal_array tidak kosong
            if (!empty($tanggalArray)) {
                // Cek apakah ada tanggal yang lebih besar atau sama dengan hari ini
                foreach ($tanggalArray as $tanggal) {
                    if ($tanggal >= $today) {
                        $result[] = $data; // Simpan data yang memenuhi syarat
                        break; // Keluar dari loop setelah menemukan tanggal yang sesuai
                    }
                }
            }
        }

        return $result;
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
