<?php

namespace backend\controllers;

use backend\models\Karyawan;
use backend\models\PengajuanCuti;
use Yii;

class PengajuanController extends \yii\web\Controller
{
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
        $pengajuanCuti = PengajuanCuti::find()->where(['id_karyawan' => $karyawan->id_karyawan])->orderBy(['status' => SORT_ASC, 'tanggal_pengajuan' => SORT_DESC])->all();

        return $this->render('/home/pengajuan/cuti/index', compact('pengajuanCuti'));
    }

    public function actionCutiCreate()
    {

        $this->layout = 'mobile-main';
        $model = new PengajuanCuti();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $karyawan = Karyawan::find()->select('id_karyawan')->where(['email' => Yii::$app->user->identity->email])->one();
                $model->id_karyawan = $karyawan->id_karyawan;
                $model->tanggal_pengajuan = date('Y-m-d');
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Membuat Pengajuan');
                    return $this->redirect(['/pengajuan/cuti']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/pengajuan/cuti']);
                }
            }
        }

        return $this->render('home/pengajuan/cuti/create', compact('model'));
    }

    public function actionCutiDetail($id)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanCuti::find()->where(['id_pengajuan_cuti' => $id])->one();
        return $this->render('home/pengajuan/cuti/detail', compact('model'));
    }
}
