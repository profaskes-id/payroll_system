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
use backend\models\helpers\EmailHelper;
use backend\models\helpers\NotificationHelper;
use backend\models\helpers\UseMessageHelper;
use backend\models\MasterKode;
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
        $id_karyawan = Yii::$app->user->identity->id_karyawan; // Default 3 hari jika tidak ditemukan
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
        $batas_deviasi_absensi = MasterKode::find()->where(['nama_group' => Yii::$app->params['batas-deviasi-absensi']])->asArray()->one();
        $batas_hari = $batas_deviasi_absensi['nama_kode'] ?? 3; // Default 3 hari jika tidak ditemukan
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }


        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {

                // Validasi batas waktu pengajuan
                $tanggal_absen = new \DateTime($model->tanggal_absen);
                $tanggal_sekarang = new \DateTime();
                $selisih_hari = $tanggal_sekarang->diff($tanggal_absen)->days;

                // Cek apakah pengajuan melebihi batas hari
                if ($selisih_hari > $batas_hari && $model->kode_status_hadir == 'H') {
                    Yii::$app->session->setFlash('error', "Pengajuan absensi tidak dapat dilakukan. Maksimal pengajuan adalah $batas_hari hari dari tanggal absen.");

                    $this->layout = 'mobile-main';
                    return $this->render('/home/absensi-tertinggal/create', [
                        'model' => $model,
                        'batas_hari' => $batas_hari
                    ]);
                }

                // dd($model, $batas_deviasi_absensi);
                if ($model->save()) {

                    $useMessage = new UseMessageHelper();
                    $adminUsers = $useMessage->getUserAtasanReceiver($model->id_karyawan);

                    $params = [
                        'judul' => 'Pengajuan  Deviasi Absensi',
                        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan absensi.',
                        'nama_transaksi' => "/panel/tanggapan/absensi-view?id",
                        'id_transaksi' => $model['id'],
                    ];

                    $this->sendNotif($params, $model, $adminUsers, "Pengajuan Absensi Baru Dari " . $model->karyawan->nama);

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
            'batas_hari' => $batas_hari
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $batas_deviasi_absensi = MasterKode::find()->where(['nama_group' => Yii::$app->params['batas-deviasi-absensi']])->asArray()->one();
        $batas_hari = $batas_deviasi_absensi['nama_kode'] ?? 3;
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
            'batas_hari' => $batas_hari
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
