<?php

namespace app\modules\v1\controllers;

use amnah\yii2\user\models\User;
use backend\models\AtasanKaryawan;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\NotificationHelper;
use Yii;
use backend\models\PengajuanDinas;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

use backend\models\PengajuanDinas as PengajuanDinasModel;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class PengajuanDinasController extends ActiveController
{

  public $modelClass = PengajuanDinasModel::class;

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
      'keterangan_perjalanan' => 'Keterangan perjalanan tidak boleh kosong.',
      'estimasi_biaya' => 'Estimasi biaya tidak boleh kosong.',
      'tanggal_mulai' => 'Tanggal mulai tidak boleh kosong.',
      'tanggal_selesai' => 'Tanggal selesai tidak boleh kosong.',
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
    $model = new PengajuanDinasModel();
    $model->id_karyawan = $request->post('id_karyawan');
    $model->status = $request->post('status', 0); // Default status = 0 jika tidak ada
    $model->keterangan_perjalanan = $request->post('keterangan_perjalanan');
    $model->estimasi_biaya = $request->post('estimasi_biaya');
    $model->tanggal_mulai = $request->post('tanggal_mulai');
    $model->tanggal_selesai = $request->post('tanggal_selesai');

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
        'judul' => 'Pengajuan dinas',
        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan dinas.',
        'nama_transaksi' => "/panel/pengajuan-dinas/view?id_pengajuan_dinas=",
        'id_transaksi' => $model['id_pengajuan_dinas'],
      ];
      $sender = User::find()->select(['id', 'email', 'role_id',])->where(['id_karyawan' => $model->id_karyawan])->one();
      $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan dinas Baru Dari " . $model->karyawan->nama);

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


  // Metode untuk mendapatkan detail pengajuan dinas berdasarkan ID
  public function actionGetByPengajuan($id_pengajuan_dinas)
  {
    $model = $this->modelClass::find()
      ->select(['pengajuan_dinas.*', 'user.username as disetujui_oleh',])
      ->leftJoin('user', 'user.id  = pengajuan_dinas.disetujui_oleh')
      ->where(['id_pengajuan_dinas' => $id_pengajuan_dinas])
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



  public function actionUploadDokumentasi($id_karyawan)
  {
    // Set response format JSON
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    // Inisiasi model
    $model = new PengajuanDinas();

    // Ambil file upload
    $model->dokumentasi = UploadedFile::getInstanceByName('dokumentasi');

    if ($model->dokumentasi) {
      // Tentukan path upload
      $uploadPath = \Yii::getAlias('@webroot/uploads/dokumentasi/');

      // Buat direktori jika belum ada
      FileHelper::createDirectory($uploadPath, 0775, true);

      // Generate nama file unik
      $fileName = $this->generateUniqueFileName($model->dokumentasi);
      $fullPath = $uploadPath . $fileName;

      // Simpan file
      if ($model->dokumentasi->saveAs($fullPath)) {
        // Simpan path relatif ke database
        $model->id_karyawan = $id_karyawan;
        $model->dokumentasi = "uploads/dokumentasi/" . $fileName;

        if ($model->save()) {
          return [
            'success' => true,
            'message' => 'Upload berhasil',
            'filePath' => $model->dokumentasi
          ];
        }
      }
    }

    return [
      'success' => false,
      'message' => 'Upload gagal'
    ];
  }

  // Fungsi generate nama file unik
  private function generateUniqueFileName($uploadedFile)
  {
    return uniqid() . '_' . time() . '.' . $uploadedFile->extension;
  }

  // Helper method untuk mencari model dengan error handling
  protected function findModel($id)
  {
    $model = $this->modelClass::findOne($id);

    if ($model === null) {
      throw new NotFoundHttpException('Pengajuan Dinas tidak ditemukan.');
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
