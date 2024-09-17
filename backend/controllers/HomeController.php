<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\models\Absensi;
use backend\models\DataKeluarga;
use backend\models\DataPekerjaan;
use backend\models\JamKerjaKaryawan;
use backend\models\Karyawan;
use backend\models\PengajuanCuti;
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
        $karyawan = Karyawan::findOne(['email' => Yii::$app->user->identity->email]);
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
        $karyawan = Karyawan::find()->where(['email' => $user->email])->one();
        $absensi = Absensi::find()->where(['id_karyawan' => $karyawan->id_karyawan])->orderBy(['tanggal' => SORT_DESC])->all();
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
        $model = new Absensi();
        $isTerlambatActive = false;
        if ($this->request->isPost) {

            $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
            $model->id_karyawan = $karyawan->id_karyawan;
            $model->tanggal = date('Y-m-d');
            $model->kode_status_hadir = 1;
            $model->jam_masuk = date('H:i:s');
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
        $model = new Absensi();
        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
        $absensiToday = Absensi::find()->where(['tanggal' => date('Y-m-d'), 'id_karyawan' => $karyawan->id_karyawan])->all();

        $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one();
        $jamKerjaHari = $jamKerjaKaryawan->jamKerja->jadwalKerjas;
        $hariIni = date('w') == '0' ? 0 : date('w') - 1;

        // dd($jamKerjaHari, $hariIni);
        $jamKerjaToday = $jamKerjaHari[$hariIni];

        // dd($jamKerjaHari);
        // if ($jamKerjaHari == null) {
        // }

        $dataJam = [
            'karyawan' => $jamKerjaKaryawan,
            'today' => $jamKerjaToday
        ];

        $this->layout = 'mobile-main';
        return $this->render('absen-masuk', [
            'model' => $model,
            'absensiToday' => $absensiToday,
            'dataProvider' => $dataProvider,
            'jamKerjaKaryawan' => $jamKerjaKaryawan,
            'dataJam' => $dataJam,
            'isTerlambatActive' => $isTerlambatActive
        ]);


        return $this->redirect(['absen-masuk']);
    }

    public function actionTerlambat()
    {
        if ($this->request->isPost) {
        }
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
                $model->id_karyawan = $karyawan->id_karyawan;
                $model->tanggal = date('Y-m-d');
                $model->jam_masuk = date('H:i:s');
                $model->jam_pulang = date('H:i:s');
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

    // ?================================Expirience`
    public function actionExpirience()
    {
        $this->layout = 'mobile-main';
        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
        $pengalamanKerja = PengalamanKerja::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $riwayatPendidikan = RiwayatPendidikan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $keluarga = DataKeluarga::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $RiwayatPelatihan = RiwayatPelatihan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
        $RiwayatKesehatan = RiwayatKesehatan::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();


        return $this->render('expirience/index', compact('pengalamanKerja', 'riwayatPendidikan', 'keluarga', 'RiwayatPelatihan', 'RiwayatKesehatan'));
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
                // dd($model);

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
        // dd($oldThumbnail);
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
}
