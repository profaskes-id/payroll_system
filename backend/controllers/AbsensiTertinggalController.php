<?php

namespace backend\controllers;

use backend\models\PengajuanAbsensi;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use amnah\yii2\user\models\User;
use backend\models\Absensi;
use yii\widgets\ActiveForm;

class AbsensiTertinggalController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $id_karyawan = Yii::$app->user->identity->id_karyawan;
        $data = [];
        if ($id_karyawan) {
            $data = PengajuanAbsensi::find()->where(['id_karyawan' => $id_karyawan])->orderBy(['tanggal_pengajuan' => SORT_DESC])->all();
        } else {
            return $this->redirect(['site/login']);
        }

        $this->layout = 'mobile-main';
        return $this->render('/home/absensi-tertinggal/index', compact('data'));
    }

    public function actionCreate()
    {
        $model = new PengajuanAbsensi();
        $model->id_karyawan = Yii::$app->user->identity->id_karyawan;
        $model->status = 0; // Default status pending
        $model->tanggal_pengajuan = date('Y-m-d H:i:s'); // Current timestamp

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Pengajuan absensi berhasil dibuat.');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal menyimpan pengajuan absensi.');
                }
            }
        }

        $this->layout = 'mobile-main';
        return $this->render('/home/absensi-tertinggal/create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Check if the record belongs to the current user
        if ($model->id_karyawan != Yii::$app->user->identity->id_karyawan) {
            throw new \yii\web\ForbiddenHttpException('Anda tidak memiliki akses untuk mengubah data ini.');
        }



        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {



            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Pengajuan absensi berhasil diperbarui.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal memperbarui pengajuan absensi.');
            }
        }

        $this->layout = 'mobile-main';
        return $this->render('/home/absensi-tertinggal/update', [
            'model' => $model,
        ]);
    }

    public function actionDetail($id)
    {
        $model = $this->findModel($id);

        // Check if the record belongs to the current user
        if ($model->id_karyawan != Yii::$app->user->identity->id_karyawan) {
            throw new \yii\web\ForbiddenHttpException('Anda tidak memiliki akses untuk melihat data ini.');
        }

        $this->layout = 'mobile-main';
        return $this->render('/home/absensi-tertinggal/detail', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = PengajuanAbsensi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Data yang diminta tidak ditemukan.');
    }
}
