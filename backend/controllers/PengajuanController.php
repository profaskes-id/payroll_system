<?php

namespace backend\controllers;

use backend\models\Karyawan;
use backend\models\MasterCuti;
use backend\models\MasterKode;
use backend\models\PengajuanCuti;
use backend\models\PengajuanDinas;
use backend\models\PengajuanLembur;
use backend\models\RekapCuti;
use Yii;
use yii\filters\VerbFilter;

class PengajuanController extends \yii\web\Controller
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

        $jenisCuti = MasterCuti::find()->where(['status' => 1])->orderBy(['jenis_cuti' => SORT_ASC])->all();

        $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
        $rekapCuti = RekapCuti::find()->where(['id_karyawan' => $karyawan->id_karyawan])->all();
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
                $model->status = 0;
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

    public function actionLemburDetail($id)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanLembur::find()->where(['id_pengajuan_lembur' => $id])->one();
        $poinArray = json_decode($model->pekerjaan);
        return $this->render('home/pengajuan/lembur/detail', compact('model', 'poinArray'));
    }


    //pengajuan dinas
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
}
