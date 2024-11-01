<?php

namespace backend\controllers;

use backend\models\Karyawan;
use backend\models\MasterCuti;
use backend\models\MasterKode;
use backend\models\PengajuanCuti;
use backend\models\PengajuanDinas;
use backend\models\PengajuanLembur;
use backend\models\PengajuanWfh;
use backend\models\RekapCuti;
use DateTime;
use Yii;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class PengajuanController extends \yii\web\Controller
{



    public function beforeAction($action)
    {
        if ($action->id == 'lembur-delete') {
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
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['?'], // Allow guests (unauthenticated users)
                        ],
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
    public function getViewPath()
    {
        return Yii::getAlias('@backend/views/');
    }



    public function actionIndex()
    {
        return $this->redirect(['/home']);
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
                $model->id_karyawan = $karyawan->id_karyawan;
                $model->tanggal_pengajuan = date('Y-m-d H:i:s');
                $model->jenis_cuti = Yii::$app->request->post('jenis_cuti');
                $model->sisa_hari = 90;
                if ($model->save()) {
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
        return $this->render('home/pengajuan/cuti/detail', compact('model'));
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

                $selisihDetik = $jamSelesai - $jamMulai;
                $durasi = gmdate('H:i', $selisihDetik);

                $model->durasi = $durasi;
                if ($model->save()) {
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
        $poinArray = json_decode($model->pekerjaan);
        return $this->render('home/pengajuan/lembur/detail', compact('model', 'poinArray'));
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
        return $this->render('home/pengajuan/dinas/detail', compact('model'));
    }
    public function actionUploadDokumentasi()
    {
        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
        $model = PengajuanDinas::find()->where(['id_karyawan' => $karyawan->id_karyawan])->one();
        $files = $model->files;
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
                $model->files = json_encode($filePaths); // Simpan dalam format JSON


                // dd($model->save());
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

        if ($model->files) {
            $data = json_decode($model->files, true);
            foreach ($data as $key => $item) {
                if (file_exists(Yii::getAlias('@webroot') . '/' . $item)) {
                    unlink(Yii::getAlias('@webroot') . '/' . $item);
                }
            }
        }
        $model->files = null;
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
        // $poinArray = json_decode($model->pekerjaan);
        return $this->render('home/pengajuan/wfh/detail', compact('model',));
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
}
