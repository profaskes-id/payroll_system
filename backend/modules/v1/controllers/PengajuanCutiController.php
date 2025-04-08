<?php

namespace app\modules\v1\controllers;

use amnah\yii2\user\models\User;
use backend\models\AtasanKaryawan;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\MobileNotificationHelper;
use backend\models\helpers\NotificationHelper;
use backend\models\Karyawan;
use backend\models\MasterCuti;
use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use backend\models\PengajuanCuti as PengajuanCutiModel;
use backend\models\RekapCuti;

class PengajuanCutiController extends ActiveController
{
  public $modelClass = PengajuanCutiModel::class;

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
      'jenis_cuti' => 'Jenis cuti tidak boleh kosong.',
      'alasan_cuti' => 'Alasan cuti tidak boleh kosong.',
      'tanggal_mulai' => 'Tanggal mulai cuti tidak boleh kosong.',
      'tanggal_selesai' => 'Tanggal selesai cuti tidak boleh kosong.',
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
    $model = new PengajuanCutiModel();
    $model->id_karyawan = $request->post('id_karyawan');
    $model->jenis_cuti = $request->post('jenis_cuti');
    $model->alasan_cuti = $request->post('alasan_cuti');
    $model->tanggal_pengajuan = $request->post('tanggal_pengajuan', date('Y-m-d')); // Default ke tanggal hari ini jika tidak ada
    $model->tanggal_mulai = $request->post('tanggal_mulai');
    $model->tanggal_selesai = $request->post('tanggal_selesai');
    $model->status = $request->post('status', 0); // Default status = 0 jika tidak ada
    $model->sisa_hari = $request->post('sisa_hari', 10); // Default sisa_hari = 10 jika tidak ada

    // Simpan model ke database
    if ($model->save()) {
      // ? KIRIM NOTIFIKASI
      $atasan = $this->getAtasanKaryawan($model->id_karyawan);
      $adminUsers = null;
      if ($atasan != null) {
        $adminUsers = User::find()->select(['id', 'email', 'role_id',])->where(['id_karyawan' => $atasan['id_atasan']])->all();
      } else {
        $adminUsers = User::find()->select(['id', 'email', 'role_id',])->where(['role_id' => [1, 3]])->all();
      }
      $params = [
        'judul' => 'Pengajuan cuti',
        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan cuti.',
        'nama_transaksi' => "cuti",
        'id_transaksi' => $model['id_pengajuan_cuti'],
      ];
      $sender = User::find()->select(['id', 'email', 'role_id',])->where(['id_karyawan' => $model->id_karyawan])->one();
      $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan cuti Baru Dari " . $model->karyawan->nama);

      foreach ($adminUsers as $admin) {
        if ($admin['fcm_token']) {

          $token = $admin['fcm_token'];
          $title = 'Pengajuan Cuti';
          $body = 'Pengajuan Cuti Dari ' . $model->karyawan->nama ?? 'karyawan';
          $data = ['url' => '/'];

          try {
            $result = MobileNotificationHelper::sendNotification($token, $title, $body, $data);
            echo "Status Code: " . $result['statusCode'] . "\n";
            echo "Response: " . print_r($result['response'], true) . "\n";
          } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
          }
        }
      };



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


  public function actionGetInitBefore($id_karyawan)
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $karyawan = Karyawan::find()
      ->select(['id_karyawan', 'kode_jenis_kelamin'])
      ->where(['id_karyawan' => $id_karyawan])
      ->asArray()
      ->one();
    // Ambil seluruh data jenis cuti dengan status aktif
    $jenisCuti = MasterCuti::find()
      ->where(['status' => 1])
      ->orderBy(['jenis_cuti' => SORT_ASC])
      ->asArray()
      ->all();

    // Filter jenis cuti berdasarkan kode jenis kelamin
    if ($karyawan['kode_jenis_kelamin'] == 'L') { // Laki-laki
      $jenisCuti = array_filter($jenisCuti, function ($cuti) {
        return $cuti['jenis_cuti'] !== 'Cuti Hamil';
      });
    }

    $rekapCuti = RekapCuti::find()->where(['id_karyawan' => $id_karyawan, 'tahun' => date('Y')])->asArray()->all();

    return ['karyawan' => $karyawan, 'jenis_cuti' => $jenisCuti, 'rekap_cuti' => $rekapCuti];
  }

  // Metode untuk mendapatkan detail pengajuan cuti berdasarkan ID
  public function actionGetByPengajuan($id_pengajuan_cuti)
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $model = $this->modelClass::find()
      ->select(['pengajuan_cuti.*', 'master_cuti.jenis_cuti as jenis_cuti', 'user.username as ditanggapi_oleh',])
      ->leftJoin('user', 'user.id  = pengajuan_cuti.ditanggapi_oleh')
      ->leftJoin('master_cuti', 'master_cuti.id_master_cuti  = pengajuan_cuti.jenis_cuti')
      ->where(['id_pengajuan_cuti' => $id_pengajuan_cuti])
      ->asArray()
      ->one();
    if (!$model) {
      return ['error' => 'Data not found.'];
    }

    return $model;
  }

  // Metode untuk mendapatkan pengajuan berdasarkan ID karyawan
  public function actionByKaryawan($id_karyawan)
  {
    Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
    $data = $this->modelClass::find()
      ->select(['pengajuan_cuti.*', 'master_cuti.jenis_cuti as jenis_cuti'])
      ->leftJoin('master_cuti', 'pengajuan_cuti.jenis_cuti = master_cuti.id_master_cuti')
      ->where(['id_karyawan' => $id_karyawan])->asArray()->all();
    return $data;
  }

  // Metode untuk mendapatkan pengajuan berdasarkan status dan ID karyawan
  public function actionByStatusAndKaryawan($status, $id_karyawan)
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
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
      throw new NotFoundHttpException('Pengajuan Cuti tidak ditemukan.');
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
