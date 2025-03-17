<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\models\AtasanKaryawan;
use backend\models\PengajuanCuti;
use backend\models\PengajuanDinas;
use backend\models\PengajuanLembur;
use backend\models\PengajuanWfh;
use DateTime;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

class TanggapanController extends Controller
{

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
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                $user = Yii::$app->user;

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
        if ($action->id == 'wfh-delete' || $action->id == 'view' || $action->id == 'expirience-pekerjaan-delete' || $action->id == 'expirience-pendidikan-delete' || $action->id == 'data-keluarga-delete' || $action->id == 'riwayat-pelatihan-delete' || $action->id == 'riwayat-kesehatan-delete') {

            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }



    public function actionWfh()
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();
        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');
        $pengajuanWfhList = PengajuanWfh::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->all();

        $this->layout = 'mobile-main';

        return $this->render('/home/tanggapan/wfh/index', compact('pengajuanWfhList'));
    }

    public function actionWfhCreate()
    {
        $this->layout = 'mobile-main';
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $model = new PengajuanWfh();

        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {
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


                    return $this->redirect(['/tanggapan/wfh']);
                } else {

                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/tanggapan/wfh']);
                }
            }
        }


        return $this->render('/home/tanggapan/wfh/create', compact('model', 'karyawanBawahanAdmin'));
    }

    public function actionWfhView($id_pengajuan_wfh)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanWfh::find()->where(['id_pengajuan_wfh' => $id_pengajuan_wfh])->one();
        return $this->render('/home/tanggapan/wfh/view', compact('model'));
    }
    public function actionWfhUpdate($id)
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $pengajuanWfh = PengajuanWfh::find()->where(['id_pengajuan_wfh' => $id])->one();

        if ($pengajuanWfh) {
            $tanggal_array = json_decode($pengajuanWfh['tanggal_array']);
            $tanggal_awal = $tanggal_array[0] ?? null;
            $tanggal_akhir = end($tanggal_array) ?? null;
        }
        if ($this->request->isPost) {
            if ($pengajuanWfh->load($this->request->post())) {
                if ($pengajuanWfh->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Mengubah Pengajuan');
                    return $this->redirect(['/tanggapan/wfh']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal , Pastikan data yang anda masukkan benar');
                }
            }
        }

        $this->layout = 'mobile-main';
        return $this->render('/home/tanggapan/wfh/update', [
            'model' => $pengajuanWfh,
            'karyawanBawahanAdmin' => $karyawanBawahanAdmin,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ]);
    }


    public function actionWfhDelete()
    {
        $id = Yii::$app->request->get('id_pengajuan_wfh');
        $model = PengajuanWfh::find()->where(['id_pengajuan_wfh' => $id])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Pengajuan');
            return $this->redirect(['/tanggapan/wfh']);
        }

        Yii::$app->session->setFlash('error', 'Gagal Menghapus Pengajuan');
        return $this->redirect(['/tanggapan/wfh']);
    }



    // Lembur
    public function actionLembur()
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;

        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();
        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');
        $pengajuanLemburList = PengajuanLembur::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->all();

        $this->layout = 'mobile-main';

        return $this->render('/home/tanggapan/lembur/index', compact('pengajuanLemburList'));
    }

    public function actionLemburCreate()
    {
        $this->layout = 'mobile-main';
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $model = new PengajuanLembur();

        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {
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


                    return $this->redirect(['/tanggapan/lembur']);
                } else {

                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/tanggapan/lembur']);
                }
            }
        }


        return $this->render('/home/tanggapan/lembur/create', compact('model', 'karyawanBawahanAdmin'));
    }


    // Cuti
    public function actionCuti()
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;

        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();
        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');
        $pengajuanCutiList = PengajuanCuti::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->all();

        $this->layout = 'mobile-main';

        return $this->render('/home/tanggapan/cuti/index', compact('pengajuanCutiList'));
    }

    public function actionCutiCreate()
    {
        $this->layout = 'mobile-main';
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $model = new PengajuanCuti();

        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {
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


                    return $this->redirect(['/tanggapan/cuti']);
                } else {

                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/tanggapan/cuti']);
                }
            }
        }


        return $this->render('/home/tanggapan/cuti/create', compact('model', 'karyawanBawahanAdmin'));
    }




    // Dinas
    public function actionDinas()
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;

        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();
        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');
        $pengajuanDinasList = PengajuanDinas::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->all();

        $this->layout = 'mobile-main';

        return $this->render('/home/tanggapan/dinas/index', compact('pengajuanDinasList'));
    }

    public function actionDinasCreate()
    {
        $this->layout = 'mobile-main';
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $model = new PengajuanDinas();

        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {
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


                    return $this->redirect(['/tanggapan/dinas']);
                } else {

                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/tanggapan/dinas']);
                }
            }
        }


        return $this->render('/home/tanggapan/dinas/create', compact('model', 'karyawanBawahanAdmin'));
    }
}
