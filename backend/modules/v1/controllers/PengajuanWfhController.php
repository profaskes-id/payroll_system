<?php

namespace app\modules\v1\controllers;

use amnah\yii2\user\models\User;
use backend\models\AtasanKaryawan;
use Yii;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\NotificationHelper;
use backend\models\PengajuanWfh as PengajuanWfhModel;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class PengajuanWfhController extends ActiveController
{
  public $modelClass = PengajuanWfhModel::class;


  public function actions()
  {
    $actions = parent::actions();
    unset($actions['create']);
    return $actions;
  }

  public function actionCreate()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    // Ambil data dari request
    $request = Yii::$app->request;

    // Validasi field yang diperlukan
    $requiredFields = [
      'id_karyawan' => 'Karyawan tidak boleh kosong.',
      'alasan' => 'Alasan tidak boleh kosong.',
      'longitude' => 'Longitude tidak boleh kosong.',
      'latitude' => 'Latitude tidak boleh kosong.',
      'tanggal_array' => 'Tanggal tidak boleh kosong.', // Tambahkan validasi untuk tanggal_array
    ];

    $errors = [];
    foreach ($requiredFields as $field => $message) {
      if (!$request->post($field)) {
        $errors[] = [
          'field' => $field,
          'message' => $message,
        ];
      }
    }

    // Jika ada error, kembalikan response error
    if (!empty($errors)) {
      Yii::$app->response->statusCode = 400; // Bad Request
      return [
        'status' => 'error',
        'errors' => $errors,
      ];
    }

    // Jika tidak ada error, proses data
    $model = new PengajuanWfhModel();
    $model->id_karyawan = $request->post('id_karyawan');
    $model->alasan = $request->post('alasan');
    $model->lokasi = $request->post('lokasi', ''); // Default ke string kosong jika tidak ada
    $model->alamat = $request->post('alamat', ''); // Default ke string kosong jika tidak ada
    $model->longitude = $request->post('longitude');
    $model->latitude = $request->post('latitude');
    $model->tanggal_array = $request->post('tanggal_array'); // Simpan sebagai JSON string

    // Simpan model ke database
    if ($model->save()) {
      // ? KIRIM NOTIFIKASI
      $atasan = $this->getAtasanKaryawan($model->id_karyawan);
      if ($atasan != null) {
        $adminUsers = User::find()->select(['id', 'email', 'role_id',])->where(['id' => $atasan['id_atasan']])->all();
      } else {
        $adminUsers = User::find()->select(['id', 'email', 'role_id',])->where(['role_id' => [1, 3]])->all();
      }
      $params = [
        'judul' => 'Pengajuan WFH',
        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan WFH.',
        'nama_transaksi' => "/panel/pengajuan-wfh/view?id_pengajuan_wfh=",
        'id_transaksi' => $model['id_pengajuan_wfh'],
      ];
      $sender = User::find()->select(['id', 'email', 'role_id',])->where(['id_karyawan' => $model->id_karyawan])->one();
      $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan WFH Baru Dari " . $model->karyawan->nama);

      return [
        'status' => 'success',
        'message' => 'Data berhasil disimpan.',
        'data' => $model,
      ];
    } else {
      Yii::$app->response->statusCode = 500; // Internal Server Error
      return [
        'status' => 'error',
        'message' => 'Gagal menyimpan data.',
        'errors' => $model->getErrors(),
      ];
    }
  }
  // Metode untuk mendapatkan detail pengajuan WFH berdasarkan ID
  public function actionGetByPengajuan($id_pengajuan_wfh)
  {
    $model = $this->modelClass::find()
      ->select(['pengajuan_wfh.*', 'user.username as disetujui_oleh',])
      ->leftJoin('user', 'user.id  = pengajuan_wfh.disetujui_oleh')
      ->where(['id_pengajuan_wfh' => $id_pengajuan_wfh])
      ->asArray()
      ->one();
    return $model;
  }

  // Metode untuk mendapatkan pengajuan berdasarkan ID karyawan
  public function actionByKaryawan($id_karyawan)
  {
    return $this->modelClass::findAll(['id_karyawan' => $id_karyawan]);
  }

  // Metode untuk mendapatkan pengajuan berdasarkan status dan ID karyawan
  public function actionByStatusAndKaryawan($status, $id_karyawan)
  {
    return $this->modelClass::findAll([
      'status' => $status,
      'id_karyawan' => $id_karyawan
    ]);
  }

  // Helper method untuk mencari model dengan error handling
  protected function findModel($id)
  {
    $model = $this->modelClass::findOne($id);

    if ($model === null) {
      throw new NotFoundHttpException('Pengajuan WFH tidak ditemukan.');
    }

    return $model;
  }


  public function sendNotif($params, $sender,  $model, $adminUsers, $subject = "Pengajuan Di Tanggapi")
  {
    try {
      NotificationHelper::sendNotification($params, $adminUsers, $sender);
    } catch (\InvalidArgumentException $e) {
      // Handle invalid argument exception
      Yii::error("Invalid argument: " . $e->getMessage());
    } catch (\RuntimeException $e) {
      // Handle runtime exception
      Yii::error("Runtime error: " . $e->getMessage());
    }

    // return $this->renderPartial('@backend/views/home/pengajuan/email', compact('model', 'adminUsers', 'subject'));
    $msgToCheck = $this->renderPartial('@backend/views/home/pengajuan/email_user', compact('model', 'params'));

    // $msgToCheck = $this->renderPartial('@backend/views/home/pengajuan/email');
    // Mengirim email ke setiap pengguna
    foreach ($adminUsers as $user) {
      $to = $user->email;
      if (EmailHelper::sendEmail($to, $subject, $msgToCheck)) {
        Yii::$app->session->setFlash('success', 'Email berhasil dikirim ke ' . $to);
      } else {
        Yii::$app->session->setFlash('error', 'Email gagal dikirim ke ' . $to);
      }
    }
  }

  public function getAtasanKaryawan($id_karyawan)
  {
    $atasan = AtasanKaryawan::find()->where(['id_karyawan' => $id_karyawan])->asArray()->one();
    return $atasan;
  }
}
