<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\models\Absensi;
use backend\models\AtasanKaryawan;
use backend\models\DataKeluarga;
use backend\models\helpers\CompressImagesHelper;
use backend\models\helpers\FaceRecognationHelper;
use backend\models\helpers\ManualSHiftHelper;
use backend\models\IzinPulangCepat;
use backend\models\JadwalKerja;
use backend\models\JadwalShift;

use backend\models\JamKerjaKaryawan;
use backend\models\Karyawan;
use backend\models\MasterHaribesar;
use backend\models\Message;
use backend\models\MessageReceiver;

use backend\models\PengajuanLembur;
use backend\models\PengajuanShift;
use backend\models\PengajuanWfh;
use backend\models\PengalamanKerja;
use backend\models\Pengumuman;
use backend\models\RiwayatKesehatan;
use backend\models\RiwayatPelatihan;
use backend\models\RiwayatPendidikan;
use backend\models\SettinganUmum;
use backend\models\ShiftKerja;
use PhpParser\Node\Expr\AssignOp\ShiftLeft;
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
        $deviasiAbsensi = SettinganUmum::find()->where(['kode_setting' => Yii::$app->params['absensi-tertinggal']])->one();
        return $this->render('index', compact('is_ada_notif', 'karyawan', 'pengumuman', 'absensi', 'lama_kerja', 'jamKerjaToday', 'dataShift', 'manual_shift' , 'deviasiAbsensi'));
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

    public function actionAbsenMasuk()
    {
        $manual_shift = ManualSHiftHelper::isManual();
        $comporess = new CompressImagesHelper();
        $setting_fr = FaceRecognationHelper::cekFaceRecognation();
        $verificationFr = FaceRecognationHelper::cekVerificationFr(); 
        $this->layout = 'mobile-main';
        $model = new Absensi();
        $verificationFr = FaceRecognationHelper::cekVerificationFr(); 

        $isTerlambatActive = false;
        if ($this->request->isPost) {
            $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->one();
            $isAda = Absensi::find()->where(['id_karyawan' => $karyawan->id_karyawan, 'tanggal' => date('Y-m-d')])->one();

            $base64Image = Yii::$app->request->post('Absensi')['foto_masuk'];
            if ($base64Image) {
                $compressedImage = $comporess->compressBase64Image($base64Image);
                $model->foto_masuk = $compressedImage;
            }

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


            if (!$isAda) {

                if ($setting_fr == 1) {

                    $similar = 0;
                    $url = Yii::$app->params['api_url_fr'] . '/core/compare';
                    $data = [
                        "cluster" => Yii::$app->params['cluster_fr'],
                        "user" => $model['karyawan']['nama'],
                        "userID" => "{$model->id_karyawan}",
                        "data" => $model->foto_masuk, // Tidak perlu quotes tambahan jika sudah string
                    ];

                    $jsonData = json_encode($data);

                    $ch = curl_init();

                    // Set cURL options
                    curl_setopt_array($ch, [
                        CURLOPT_URL => $url,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $jsonData,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPHEADER => [
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($jsonData)
                        ],
                        CURLOPT_TIMEOUT => 30, // Timeout in seconds
                    ]);

                    // Execute the request
                    $response = curl_exec($ch);
                    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    // Check for errors
                    if (curl_errno($ch)) {
                        $error_msg = curl_error($ch);
                        // Handle error (log or throw exception)
                        throw new \Exception("cURL Error: " . $error_msg);
                    }

                    // Close cURL session
                    curl_close($ch);

                    if (empty($response)) {
                        throw new \Exception("API mengembalikan response kosong");
                    }

                    $responseArray = json_decode($response, true);

                    if (isset($responseArray['message']) == "OK") {
                        // Request berhasil
                        $similar  = $responseArray['data']['similarity'];
                    } else {
                        //flash error
                        Yii::$app->session->setFlash('error', 'API Error: ');
                    }


                    //jika mirip
                    if ($similar * 100 >= Yii::$app->params['minimal_kemiripan_fr']) {
                        // Hitung persentase kemiripan (dikalikan 100 dan dibulatkan)
                        $similarPercentage = round($similar * 100);
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
                        if($verificationFr == 1){
                            $similarPercentage = round($similar * 100);
                            Yii::$app->session->setFlash(
                                'error',
                                'Absen masuk tidak berhasil. Tingkat kemiripan wajah Anda hanya ' . $similarPercentage . '%. '
                                    . 'Silakan ulangi pemindaian wajah hingga mencapai minimal ' . $similarPercentage . '%.'
                            );
                        }else{
                             $similarPercentage = round($similar * 100);
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

        $dataProvider = new ActiveDataProvider([
            'query' => Absensi::find(),
        ]);

        $karyawan = Karyawan::find()->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan, 'is_aktif' => 1])->one();
        $atasanPenempatan = AtasanKaryawan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one();

        // Mengambil data atasan karyawan
        if ($atasanPenempatan) {
            $masterLokasi = $atasanPenempatan->masterLokasi;
        } else {
            Yii::$app->session->setFlash('error', 'Data Atasan Dan Penempatan Kerja belum ada, Silahkan Hubungi Admin');
            return $this->redirect(['/']);
        }

        // Data absensi
        $absensiToday = Absensi::find()->where(['tanggal' => date('Y-m-d'), 'id_karyawan' => $karyawan->id_karyawan])->all();
        $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one();

        // Pengajuan lembur dan izin pulang cepat
        $lembur = PengajuanLembur::find()->asArray()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $isPulangCepat = IzinPulangCepat::find()->where(['id_karyawan' => $karyawan->id_karyawan, 'tanggal' => date('Y-m-d')])->one();
        $hasilLembur = [];

        if ($lembur) {
            foreach ($lembur as $l) {
                if (isset($l['tanggal']) && $l['tanggal'] >= date('Y-m-d') && $l['status'] == '1') {
                    $hasilLembur[] = $l;
                }
            }
        }

        if (!$jamKerjaKaryawan) {
            throw new NotFoundHttpException('Admin Belum Melakukan Settingan Jam Kerja Pada Akun Anda');
        }

        $hariIni = date('w') == '0' ? 0 : date('w');

        // Mengambil jam kerja karyawan minggu ini
        $jamKerjaHari = $jamKerjaKaryawan->jamKerja->jadwalKerjas;

        // Cek apakah 24 jam?
        foreach ($jamKerjaHari as $key => $value) {
            if ($value['nama_hari'] == date("N") && $value['is_24jam'] == 1) {
                $absensiTerakhir = Absensi::find()
                    ->where(['id_karyawan' => $karyawan->id_karyawan])
                    ->orderBy(['tanggal' => SORT_DESC])
                    ->one();

                return $this->render('absensi/absen-24jam', [
                    'model' => $model,
                    'jamKerjaKaryawan' => $value,
                    'absensiTerakhir' => $absensiTerakhir,
                    'isPulangCepat' => $isPulangCepat,
                    'masterLokasi' => $masterLokasi,
                ]);
            }
        }

        $jamKerjaToday = null;


        if ($jamKerjaKaryawan['is_shift'] == 1) {

            $jadwalKerjaKaryawan = JadwalKerja::find()->asArray()->where(['id_jam_kerja' => $jamKerjaKaryawan['id_jam_kerja'], 'nama_hari' => date('N')])->one();

            if ($jadwalKerjaKaryawan !== null) {
                $tanggalHariIni = date('Y-m-d');


                $jadwalShiftHariIni = JadwalShift::find()
                    ->where(['id_karyawan' => $jamKerjaKaryawan['id_karyawan'], 'tanggal' => $tanggalHariIni])
                    ->asArray()
                    ->one();

                if ($jadwalShiftHariIni) {
                    $shifKerja = ShiftKerja::find()->asArray()->where(['id_shift_kerja' => $jadwalShiftHariIni['id_shift_kerja']])->one();
                } else {
                    $shifKerja = [];
                }

                if (!empty($shifKerja)) {
                    $jamKerjaToday = $shifKerja;
                } else {
                    $jamKerjaToday = [];
                }
            } else {
                $jamKerjaToday = [];
            }
        } else {

            // dd($jamKerjaKaryawan->jamKerja);
            $jamKerjaHari = $jamKerjaKaryawan->jamKerja->jadwalKerjas;
            if ($hariIni != 0) {
                $hariBesar = MasterHaribesar::find()->select('tanggal')->asArray()->all();

                // Ambil tanggal hari ini
                $tanggalHariIni = date('Y-m-d');
                $adaHariBesar = false;
                foreach ($hariBesar as $hari) {
                    if ($hari['tanggal'] === $tanggalHariIni) {
                        $adaHariBesar = true;
                        break;
                    }
                }

                if ($adaHariBesar) {
                    $jamKerjaToday = null;
                } else {
                    $filteredJamKerja = array_filter($jamKerjaHari, function ($item) use ($hariIni) {
                        return $item['nama_hari'] == $hariIni;
                    });

                    $jamKerjaToday = reset($filteredJamKerja);
                }
            } else {
                $jamKerjaToday = null;
            }
        }




        $dataJam = [
            'karyawan' => $jamKerjaKaryawan,
            'today' => $jamKerjaToday,
            'lembur' => $hasilLembur,
            'manual_shift' => $manual_shift
        ];


        if (isset($dataJam['lembur'])) {
            $tanggalHariIni = date('Y-m-d');
            $adaLemburHariIni = false;

            foreach ($dataJam['lembur'] as $lembur) {
                if (isset($lembur['tanggal']) && $lembur['tanggal'] == $tanggalHariIni) {
                    $adaLemburHariIni = true;
                    break;
                }
            }

            if ($adaLemburHariIni && !$jamKerjaToday) {
                return $this->render('absensi/absen-lembur', [
                    'model' => $model,
                    'absensiToday' => $absensiToday,
                    'dataProvider' => $dataProvider,
                    'jamKerjaKaryawan' => $jamKerjaKaryawan,
                    'dataJam' => $dataJam,
                    'isPulangCepat' => $isPulangCepat,
                    'manual_shift' => $manual_shift
                ]);
            }
        }

        // WFH
        $wfhData = $this->wfhData($karyawan->id_karyawan);
        $today = date('Y-m-d');
        $is_wfh = false;

        foreach ($wfhData as $data) {
            $tanggalArray = json_decode($data['tanggal_array'], true);
            if (!empty($tanggalArray) && in_array($today, $tanggalArray)) {
                $is_wfh = true;
                break;
            }
        }

        if ($is_wfh) {
            return $this->render('absensi/absen-wfh', [
                'model' => $model,
                'absensiToday' => $absensiToday,
                'dataProvider' => $dataProvider,
                'jamKerjaKaryawan' => $jamKerjaKaryawan,
                'dataJam' => $dataJam,
                'isTerlambatActive' => $isTerlambatActive,
                'isPulangCepat' => $isPulangCepat,
                'jamKerjaToday' => $jamKerjaToday,
                'masterLokasi' => $masterLokasi,
                'manual_shift' => $manual_shift
            ]);
        }




        if ($setting_fr == 1) {
            return $this->render('absensi/absen-masuk', [
                'model' => $model,
                'absensiToday' => $absensiToday,
                'dataProvider' => $dataProvider,
                'jamKerjaKaryawan' => $jamKerjaKaryawan,
                'dataJam' => $dataJam,
                'isTerlambatActive' => $isTerlambatActive,
                'isPulangCepat' => $isPulangCepat,
                'jamKerjaToday' => $jamKerjaToday,
                'masterLokasi' => $masterLokasi,
                'manual_shift' => $manual_shift
            ]);
        } else {
            return $this->render('absensi/absen-masuk_old', [
                'model' => $model,
                'absensiToday' => $absensiToday,
                'dataProvider' => $dataProvider,
                'jamKerjaKaryawan' => $jamKerjaKaryawan,
                'dataJam' => $dataJam,
                'isTerlambatActive' => $isTerlambatActive,
                'isPulangCepat' => $isPulangCepat,
                'jamKerjaToday' => $jamKerjaToday,
                'masterLokasi' => $masterLokasi,
                'manual_shift' => $manual_shift
            ]);
        }
    }


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
            $base64Image = Yii::$app->request->post('Absensi')['foto_masuk'];
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

                    $similar = 0;
                    $url = Yii::$app->params['api_url_fr'] . '/core/compare';
                    $data = [
                        "cluster" => Yii::$app->params['cluster_fr'],
                        "user" => $model['karyawan']['nama'],

                        "userID" => "{$model->id_karyawan}",
                        "data" => $model->foto_masuk, // Tidak perlu quotes tambahan jika sudah string
                    ];

                    $jsonData = json_encode($data);

                    $ch = curl_init();

                    // Set cURL options
                    curl_setopt_array($ch, [
                        CURLOPT_URL => $url,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $jsonData,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPHEADER => [
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($jsonData)
                        ],
                        CURLOPT_TIMEOUT => 30, // Timeout in seconds
                    ]);

                    // Execute the request
                    $response = curl_exec($ch);
                    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    // Check for errors
                    if (curl_errno($ch)) {
                        $error_msg = curl_error($ch);
                        // Handle error (log or throw exception)
                        throw new \Exception("cURL Error: " . $error_msg);
                    }

                    // Close cURL session
                    curl_close($ch);

                    if (empty($response)) {
                        throw new \Exception("API mengembalikan response kosong");
                    }

                    $responseArray = json_decode($response, true);

                    if (isset($responseArray['message']) == "OK") {
                        // Request berhasil
                        $similar  = $responseArray['data']['similarity'];
                    } else {
                        //flash error
                        Yii::$app->session->setFlash('error', 'API Error: ');
                    }

                    // /jika mirip
                   if ($similar * 100 >= Yii::$app->params['minimal_kemiripan_fr']) {
                        // Hitung persentase kemiripan (dikalikan 100 dan dibulatkan)
                        $similarPercentage = round($similar * 100);
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
                        if($verificationFr == 1){
                            $similarPercentage = round($similar * 100);
                            Yii::$app->session->setFlash(
                                'error',
                                'Absen masuk tidak berhasil. Tingkat kemiripan wajah Anda hanya ' . $similarPercentage . '%. '
                                    . 'Silakan ulangi pemindaian wajah hingga mencapai minimal ' . $similarPercentage . '%.'
                            );
                        }else{
                             $similarPercentage = round($similar * 100);
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

            $base64Image = Yii::$app->request->post('Absensi')['foto_masuk'];
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

            // Hanya lakukan pengecekan keterlambatan jika manual_shift == 1
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

                    $similar = 0;
                    $url = Yii::$app->params['api_url_fr'] . '/core/compare';
                    $data = [
                        "cluster" => Yii::$app->params['cluster_fr'],
                        "user" => $model['karyawan']['nama'],
                        // "user" => 'syaid',
                        "userID" => "{$model->id_karyawan}",
                        // "userID" => "18",
                        "data" => $model->foto_masuk, // Tidak perlu quotes tambahan jika sudah string
                    ];

                    $jsonData = json_encode($data);

                    $ch = curl_init();

                    // Set cURL options
                    curl_setopt_array($ch, [
                        CURLOPT_URL => $url,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $jsonData,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPHEADER => [
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($jsonData)
                        ],
                        CURLOPT_TIMEOUT => 30, // Timeout in seconds
                    ]);

                    // Execute the request
                    $response = curl_exec($ch);
                    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    // Check for errors
                    if (curl_errno($ch)) {
                        $error_msg = curl_error($ch);
                        // Handle error (log or throw exception)
                        throw new \Exception("cURL Error: " . $error_msg);
                    }

                    // Close cURL session
                    curl_close($ch);

                    if (empty($response)) {
                        throw new \Exception("API mengembalikan response kosong");
                    }

                    $responseArray = json_decode($response, true);

                    if (isset($responseArray['message']) == "OK") {
                        // Request berhasil
                        $similar  = $responseArray['data']['similarity'];
                    } else {
                        //flash error
                        Yii::$app->session->setFlash('error', 'API Error: ');
                    }

                    // jika mirip
                            if ($similar * 100 >= Yii::$app->params['minimal_kemiripan_fr']) {
                        // Hitung persentase kemiripan (dikalikan 100 dan dibulatkan)
                        $similarPercentage = round($similar * 100);
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
                        if($verificationFr == 1){
                            $similarPercentage = round($similar * 100);
                            Yii::$app->session->setFlash(
                                'error',
                                'Absen masuk tidak berhasil. Tingkat kemiripan wajah Anda hanya ' . $similarPercentage . '%. '
                                    . 'Silakan ulangi pemindaian wajah hingga mencapai nilai minimal '
                            );
                        }else{
                             $similarPercentage = round($similar * 100);
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
        $karyawanId = Yii::$app->user->identity->id_karyawan;
        $karyawan = Karyawan::find()->select('id_karyawan')->where(['id_karyawan' => $karyawanId])->asArray()->one();

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
            $model->kelebihan_jam_pulang = $kelebihanWaktu ?? null; // Use null if not set

            if ($model->save()) {
                return $this->redirect(['absen-masuk']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->redirect(['absen-masuk']);
    }

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

        $nowShift = JamKerjaKaryawan::find()->where(['id_karyawan' => $id_karyawan])->one();
        $allDataShift = ShiftKerja::find()->asArray()->all();
        $currentShift = [];
        if ($nowShift && $nowShift['is_shift'] == 1) {
            $tanggalHariIni = date('Y-m-d');
            $jadwalShiftHariIni = JadwalShift::find()
                ->where(['id_karyawan' => $nowShift['id_karyawan'], 'tanggal' => $tanggalHariIni])
                ->one();
            $currentShift = ShiftKerja::find()
                ->where(['id_shift_kerja' => $jadwalShiftHariIni['id_shift_kerja']])
                ->one();
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
                if ($messageReceiver->save()) {

                    if ($nama_transaksi == 'wfh') {
                        return $this->redirect(['/pengajuan/wfh-detail?id=' . $id_transaksi]);
                    } elseif ($nama_transaksi == 'lembur') {
                        return $this->redirect(['/pengajuan/lembur-detail?id=' . $id_transaksi]);
                    } elseif ($nama_transaksi == 'dinas') {
                        return $this->redirect(['/pengajuan/dinas-detail?id=' . $id_transaksi]);
                    } elseif ($nama_transaksi == 'cuti') {
                        return $this->redirect(['/pengajuan/cuti-detail?id=' . $id_transaksi]);
                    } else {
                        return $this->redirect(['/']);
                    }
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
}
