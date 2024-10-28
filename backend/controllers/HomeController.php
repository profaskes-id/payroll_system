<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\models\Absensi;
use backend\models\AtasanKaryawan;
use backend\models\DataKeluarga;
use backend\models\DataPekerjaan;
use backend\models\IzinPulangCepat;
use backend\models\JadwalKerja;
use backend\models\JamKerja;
use backend\models\JamKerjaKaryawan;
use backend\models\Karyawan;
use backend\models\MasterHaribesar;
use backend\models\PengajuanCuti;
use backend\models\PengajuanLembur;
use backend\models\PengajuanWfh;
use backend\models\PengalamanKerja;
use backend\models\Pengumuman;
use backend\models\RiwayatKesehatan;
use backend\models\RiwayatPelatihan;
use backend\models\RiwayatPendidikan;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\i18n\Formatter;
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
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [

                        [
                            'allow' => true,
                            'roles' => ['@'], // Allow authenticated users
                            'matchCallback' => function ($rule, $action) {
                                $user = Yii::$app->user;
                                // Check if the user does not have the 'admin' or 'super admin' role
                                return !$user->can('admin') && !$user->can('super_admin');
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    public function beforeAction($action)
    {
        if ($action->id == 'view' || $action->id == 'expirience-pekerjaan-delete' || $action->id == 'expirience-pendidikan-delete' || $action->id == 'data-keluarga-delete' || $action->id == 'riwayat-pelatihan-delete' || $action->id == 'riwayat-kesehatan-delete') {
            // Menonaktifkan CSRF verification untuk aksi 'view'
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }



    // ?========================Base

    public function actionIndex()
    {


        $this->layout = 'mobile-main';
        $karyawan = Karyawan::find()->where(['email' => Yii::$app->user->identity->email, 'is_aktif' => 1])->one();
        if (!$karyawan) {
            return "anda tidak terdaftar, silahkan hubungi administrator";
        }

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



        return $this->render('index', compact('karyawan', 'pengumuman', 'absensi', 'lama_kerja'));
    }

    public function actionView($id_user)
    {

        $this->layout = 'mobile-main';

        if ($this->request->isPost) {

            $tanggal_searh = Yii::$app->request->post('tanggal_searh');
            $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
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
            ->select(['tanggal', 'jam_masuk', 'jam_pulang', 'keterangan', 'kode_status_hadir', 'is_lembur', 'is_wfh'])
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
        $this->layout = 'mobile-main';
        $model = new Absensi();
        $isTerlambatActive = false;
        if ($this->request->isPost) {

            $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
            $model->id_karyawan = $karyawan->id_karyawan;
            $model->tanggal = date('Y-m-d');
            $model->kode_status_hadir = "H";
            $model->jam_masuk = date('H:i:s');
            $model->is_lembur = Yii::$app->request->post('Absensi')['is_lembur'] ?? 0;
            $model->is_wfh = Yii::$app->request->post('Absensi')['is_wfh'] ?? 0;
            $model->keterangan = $model->is_lembur ? 'Lembur' : 'Hadir';
            $model->latitude = Yii::$app->request->post('Absensi')['latitude'];
            $model->longitude = Yii::$app->request->post('Absensi')['longitude'];
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil');
                $isTerlambatActive = true;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Absensi::find(),
        ]);


        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email, 'is_aktif' => 1])->one();
        $atasanPenempatan = AtasanKaryawan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one();
        if ($atasanPenempatan) {
            $masterLokasi = $atasanPenempatan->masterLokasi;
        } else {
            Yii::$app->session->setFlash('error', 'Anda Belum Memasukkan Atasan Penempatan, Silahkan Hubungi Admin');
            return $this->redirect(['/panel']);
        }


        $absensiToday = Absensi::find()->where(['tanggal' => date('Y-m-d'), 'id_karyawan' => $karyawan->id_karyawan])->all();

        $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one();

        $lembur = PengajuanLembur::find()->asArray()->where(['id_karyawan' => $karyawan->id_karyawan])->all();

        $hasilLembur = [];

        if ($lembur) {
            foreach ($lembur as $l) {

                if (isset($l['tanggal']) && $l['tanggal'] >= date('Y-m-d')) {
                    $hasilLembur[] = $l;
                }
            }
        }

        if (!$jamKerjaKaryawan) {
            throw new NotFoundHttpException('Admin Belum Melakukan Settingan Jam Kerja Pada Akun Anda');
        }
        $jamKerjaHari = $jamKerjaKaryawan->jamKerja->jadwalKerjas;

        $hariIni = date('w') == '0' ? 0 : date('w');
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
                $jamKerjaToday = $jamKerjaHari[$hariIni - 1] ?? null;
            }
        } else {
            $jamKerjaToday = null;
        }

        $dataJam = [
            'karyawan' => $jamKerjaKaryawan,
            'today' => $jamKerjaToday,
            'lembur' => $hasilLembur,
        ];
        $isPulangCepat = IzinPulangCepat::find()->where(['id_karyawan' => $karyawan->id_karyawan, 'tanggal' => date('Y-m-d')])->one();

        if (isset($dataJam['lembur'])) {


            $tanggalHariIni = date('Y-m-d'); // Format tanggal hari ini (asumsi format tanggal dalam array sama)
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
                    // 'isTerlambatActive' => $isTerlambatActive,
                    'isPulangCepat' => $isPulangCepat,
                    // 'jamKerjaToday' => $jamKerjaToday,
                    // 'masterLokasi' => $masterLokasi,
                ]);
            }
        }


        // wfh
        $wfhData = $this->wfhData($karyawan->id_karyawan);
        $today = date('Y-m-d'); // Ambil tanggal hari ini
        $is_wfh = false; // Inisialisasi is_wfh


        foreach ($wfhData  as $data) {
            $tanggalArray = json_decode($data['tanggal_array'], true);
            if (!empty($tanggalArray)) {
                if (in_array($today, $tanggalArray)) {
                    $is_wfh = true;
                    break;
                }
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
            ]);
        }


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
        ]);



        return $this->redirect(['absen-masuk']);
    }
    public function actionAbsenTerlambat()
    {
        $model = new Absensi();
        if ($this->request->isPost) {
            $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
            $model->id_karyawan = $karyawan->id_karyawan;
            $model->tanggal = date('Y-m-d');
            $model->kode_status_hadir = "H";
            $model->jam_masuk = date('H:i:s');
            $model->latitude = Yii::$app->request->post('Absensi')['latitude'];
            $model->longitude = Yii::$app->request->post('Absensi')['longitude'];
            $model->alasan_terlambat = Yii::$app->request->post('Absensi')['alasan_terlambat'];
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil, Anda Terlambat');
            }
        }

        return $this->redirect(['absen-masuk']);
    }
    public function actionAbsenTerlalujauh()
    {
        $model = new Absensi();
        if ($this->request->isPost) {
            $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
            $model->id_karyawan = $karyawan->id_karyawan;
            $model->tanggal = date('Y-m-d');
            $model->kode_status_hadir = "H";
            $model->jam_masuk = date('H:i:s');
            $model->latitude = Yii::$app->request->post('Absensi')['latitude'];
            $model->longitude = Yii::$app->request->post('Absensi')['longitude'];
            $model->alasan_terlambat = Yii::$app->request->post('Absensi')['alasan_terlalu_jauh'];
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Absen Masuk Berhasil, Anda Terlambat');
            }
        }

        return $this->redirect(['absen-masuk']);
    }


    public function actionAbsenPulang()
    {


        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
        $model = Absensi::find()->where(['id_karyawan' => $karyawan->id_karyawan, 'tanggal' => date('Y-m-d')])->one();
        if ($this->request->isPost) {
            $model->jam_pulang = date('H:i:s');
            if ($model->save()) {
                return $this->redirect(['absen-masuk']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->redirect(['absen-masuk']);
    }

    public function actionCreate()
    {
        $model = new Absensi();

        if ($this->request->isPost) {
            $lampiranFile = UploadedFile::getInstance($model, 'lampiran');
            Yii::$app->request->post('keterangan');


            if ($model->load($this->request->post())) {
                $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
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
        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->select('id_karyawan')->one();

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
                $karyawan = Karyawan::findOne(['email' => Yii::$app->user->identity->email])->id_karyawan;
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


    // ?================================Expirience`
    public function actionExpirience()
    {
        $this->layout = 'mobile-main';
        $karyawan = Karyawan::find()->where(['email' => Yii::$app->user->identity->email])->one();
        $pengalamanKerja = PengalamanKerja::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $riwayatPendidikan = RiwayatPendidikan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $keluarga = DataKeluarga::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $RiwayatPelatihan = RiwayatPelatihan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $RiwayatKesehatan = RiwayatKesehatan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();


        return $this->render('expirience/index', compact('karyawan', 'pengalamanKerja', 'riwayatPendidikan', 'keluarga', 'RiwayatPelatihan', 'RiwayatKesehatan'));
    }

    // ! pekerjaan
    public function actionExpiriencePekerjaanCreate()
    {
        $this->layout = 'mobile-main';

        $model = new PengalamanKerja();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
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
                $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
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
                $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
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
                $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
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

                $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
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
    public function actionPengumuman($id_pengumuman)
    {
        $pengumuman = Pengumuman::findOne($id_pengumuman);
        $this->layout = 'mobile-main';
        return $this->render('pengumuman/index', compact('pengumuman'));
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
