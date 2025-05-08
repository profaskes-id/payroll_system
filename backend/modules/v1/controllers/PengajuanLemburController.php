<?php

namespace app\modules\v1\controllers;

use amnah\yii2\user\models\User;
use backend\models\AtasanKaryawan;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\MobileNotificationHelper;
use Yii;
use backend\models\helpers\NotificationHelper;
use backend\models\PengajuanLembur as PengajuanLemburModel;
use backend\models\SettinganUmum;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class PengajuanLemburController extends ActiveController
{
  public $modelClass = PengajuanLemburModel::class;


  public function actions()
  {
    $actions = parent::actions();
    unset($actions['create']);
    return $actions;
  }

  public function actionCreate()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $request = Yii::$app->request;

    $requiredFields = [
      'id_karyawan' => 'Karyawan tidak boleh kosong.',
      'pekerjaan' => 'Pekerjaan tidak boleh kosong.',
      'tanggal' => 'Tanggal tidak boleh kosong.',
      'jam_mulai' => 'Jam mulai tidak boleh kosong.',
      'jam_selesai' => 'Jam selesai tidak boleh kosong.',
    ];

    $errors = [];
    foreach ($requiredFields as $field => $message) {
      if (!$request->post($field)) {
        $errors[] = ['field' => $field, 'message' => $message];
      }
    }

    if (!empty($errors)) {
      Yii::$app->response->statusCode = 400;
      return [
        'status' => 'error',
        'errors' => $errors,
      ];
    }

    $jamMulai = strtotime($request->post('jam_mulai'));
    $jamSelesai = strtotime($request->post('jam_selesai'));
    if ($jamSelesai < $jamMulai) {
      $jamSelesai += 24 * 60 * 60;
    }

    $selisihDetik = $jamSelesai - $jamMulai;
    $durasiMenit = $selisihDetik / 60;
    $durasiJam = floor($durasiMenit / 60);
    $durasiMenitSisa = $durasiMenit % 60;

    // Ambil settingan kalkulasi_jam_lembur
    $settingKalkulasi = SettinganUmum::find()
      ->where(['kode_setting' => 'kalkulasi_jam_lembur'])
      ->one();

    $hitunganLembur = 0;

    if ($settingKalkulasi !== null && intval($settingKalkulasi->nilai_setting) === 0) {
      // Versi pengali 1.5 dan 2.0
      if ($durasiJam >= 1) {
        $hitunganLembur += 1.5;
        $durasiJam -= 1;
      }
      if ($durasiJam > 0) {
        $hitunganLembur += $durasiJam * 2;
      }
      if ($durasiMenitSisa > 0) {
        $hitunganLembur += ($durasiMenitSisa >= 30) ? 1 : 0.5;
      }
    } else {
      // Versi 1:1 (normal)
      $hitunganLembur = round($durasiMenit / 60, 2);
    }

    $model = new PengajuanLemburModel();
    $model->id_karyawan = $request->post('id_karyawan');
    $model->pekerjaan = $request->post('pekerjaan');
    $model->tanggal = $request->post('tanggal');
    $model->jam_mulai = $request->post('jam_mulai');
    $model->jam_selesai = $request->post('jam_selesai');
    $model->durasi = gmdate('H:i', $selisihDetik);
    $model->hitungan_jam = $hitunganLembur;
    $model->status = $request->post('status', 0);

    if ($model->save()) {
      $atasan = $this->getAtasanKaryawan($model->id_karyawan);
      if ($atasan != null) {
        $adminUsers = User::find()->select(['id', 'email', 'role_id'])->where(['id_karyawan' => $atasan['id_atasan']])->all();
      } else {
        $adminUsers = User::find()->select(['id', 'email', 'role_id'])->where(['role_id' => [1, 3]])->all();
      }

      $params = [
        'judul' => 'Pengajuan lembur',
        'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah membuat pengajuan lembur.',
        'nama_transaksi' => "lembur",
        'id_transaksi' => $model['id_pengajuan_lembur'],
      ];

      $sender = User::find()->select(['id', 'email', 'role_id'])->where(['id_karyawan' => $model->id_karyawan])->one();
      $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan lembur Baru Dari " . $model->karyawan->nama);

      return [
        'status' => 'success',
        'message' => 'Data berhasil disimpan.',
        'data' => $model,
      ];
    } else {
      Yii::$app->response->statusCode = 500;
      return [
        'status' => 'error',
        'message' => 'Gagal menyimpan data.',
        'errors' => $model->getErrors(),
      ];
    }
  }


  public function actionCustomUpdate($id)
  {
    // Set response format to JSON
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    try {
      // Cari model berdasarkan ID
      $model = PengajuanLemburModel::findOne($id);
      if (!$model) {
        Yii::$app->response->statusCode = 404; // Not Found
        return [
          'status' => 'error',
          'message' => 'Data tidak ditemukan.',
        ];
      }

      // Ambil body parameters
      $rawBody = Yii::$app->request->getRawBody();
      $bodyParams = json_decode($rawBody, true);

      // Validasi apakah ada data yang dikirim
      if (empty($bodyParams)) {
        Yii::$app->response->statusCode = 400; // Bad Request
        return [
          'status' => 'error',
          'message' => 'Tidak ada data untuk diupdate.',
        ];
      }

      // Validasi field yang diperlukan
      $requiredFields = [
        'id_karyawan' => 'Karyawan tidak boleh kosong.',
        'pekerjaan' => 'Pekerjaan tidak boleh kosong.',
        'tanggal' => 'Tanggal tidak boleh kosong.',
        'jam_mulai' => 'Jam mulai tidak boleh kosong.',
        'jam_selesai' => 'Jam selesai tidak boleh kosong.',
      ];

      $errors = [];
      foreach ($requiredFields as $field => $message) {
        if (!isset($bodyParams[$field]) || $bodyParams[$field] === null) {
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

      // Update atribut model
      foreach ($bodyParams as $key => $value) {
        if ($model->hasAttribute($key)) {
          $model->setAttribute($key, $value);
        }
      }

      // Perhitungan durasi lembur
      if (isset($bodyParams['jam_mulai']) && isset($bodyParams['jam_selesai'])) {
        $jamMulai = strtotime($bodyParams['jam_mulai']);
        $jamSelesai = strtotime($bodyParams['jam_selesai']);

        // Menghitung selisih waktu dalam detik
        $selisihDetik = $jamSelesai - $jamMulai;

        // Mengkonversi selisih waktu ke dalam format jam:menit
        $durasiMenit = $selisihDetik / 60; // Durasi dalam menit
        $durasiJam = floor($durasiMenit / 60); // Durasi dalam jam (dibulatkan ke bawah)
        $durasiMenitSisa = $durasiMenit % 60; // Sisa menit setelah dibagi jam

        // Menghitung jumlah jam lembur sesuai dengan aturan yang diberikan
        $hitunganLembur = 0;

        // Perhitungan durasi lembur
        if (isset($bodyParams['jam_mulai']) && isset($bodyParams['jam_selesai'])) {
          $jamMulai = strtotime($bodyParams['jam_mulai']);
          $jamSelesai = strtotime($bodyParams['jam_selesai']);

          // Jika jam selesai lebih kecil dari jam mulai, berarti sudah beda hari
          if ($jamSelesai < $jamMulai) {
            $jamSelesai += 24 * 60 * 60;
          }

          $selisihDetik = $jamSelesai - $jamMulai;
          $durasiMenit = $selisihDetik / 60;
          $durasiJam = floor($durasiMenit / 60);
          $durasiMenitSisa = $durasiMenit % 60;

          // Ambil setting kalkulasi jam lembur
          $settingKalkulasi = SettinganUmum::find()
            ->where(['kode_setting' => 'kalkulasi_jam_lembur'])
            ->one();

          $hitunganLembur = 0;

          if ($settingKalkulasi !== null && intval($settingKalkulasi->nilai_setting) === 0) {
            // Perhitungan versi pengali: 1.5 dan 2
            if ($durasiJam >= 1) {
              $hitunganLembur += 1.5;
              $durasiJam -= 1;
            }

            if ($durasiJam > 0) {
              $hitunganLembur += $durasiJam * 2;
            }

            if ($durasiMenitSisa > 0) {
              $hitunganLembur += ($durasiMenitSisa >= 30) ? 1 : 0.5;
            }
          } else {
            // Perhitungan 1:1
            $hitunganLembur = round($durasiMenit / 60, 2);
          }

          $model->durasi = gmdate('H:i', $selisihDetik);
          $model->hitungan_jam = $hitunganLembur;
        }


        // Update durasi dan hitungan lembur
        $model->durasi = gmdate('H:i', $selisihDetik); // Format durasi dalam jam:menit
        $model->hitungan_jam = $hitunganLembur; // Simpan hasil perhitungan jam lembur
      }

      // Simpan model ke database
      if ($model->save()) {
        // KIRIM NOTIFIKASI
        $atasan = $this->getAtasanKaryawan($model->id_karyawan);
        if ($atasan != null) {
          $adminUsers = User::find()->select(['id', 'email', 'role_id'])->where(['id' => $atasan['id_atasan']])->all();
        } else {
          $adminUsers = User::find()->select(['id', 'email', 'role_id'])->where(['role_id' => [1, 3]])->all();
        }

        $params = [
          'judul' => 'Edit Pengajuan lembur',
          'deskripsi' => 'Karyawan ' . $model->karyawan->nama . ' telah melakukan edit pada pengajuan lembur.',
          'nama_transaksi' => "lembur",
          'id_transaksi' => $model['id_pengajuan_lembur'],
        ];

        $sender = User::find()->select(['id', 'email', 'role_id'])->where(['id_karyawan' => $model->id_karyawan])->one();
        $this->sendNotif($params, $sender, $model, $adminUsers, "Edit Pengajuan lembur " . $model->karyawan->nama);

        foreach ($adminUsers as $admin) {
          if ($admin['fcm_token']) {
            $token = $admin['fcm_token'];
            $title = 'Pengajuan Lembur';
            $body = 'Pengajuan Lembur Dari ' . $model->karyawan->nama ?? 'karyawan';
            $data = ['url' => '/'];

            try {
              $result = MobileNotificationHelper::sendNotification($token, $title, $body, $data);
              echo "Status Code: " . $result['statusCode'] . "\n";
              echo "Response: " . print_r($result['response'], true) . "\n";
            } catch (\Exception $e) {
              echo 'Error: ' . $e->getMessage();
            }
          }
        }

        return [
          'status' => 'success',
          'message' => 'Data berhasil diperbarui.',
          'data' => $model,
        ];
      } else {
        Yii::$app->response->statusCode = 500; // Internal Server Error
        return [
          'status' => 'error',
          'message' => 'Gagal memperbarui data.',
          'errors' => $model->getErrors(),
        ];
      }
    } catch (\Exception $e) {
      // Tangkap exception yang tidak terduga
      Yii::error('Update Error: ' . $e->getMessage());
      Yii::$app->response->statusCode = 500; // Internal Server Error
      return [
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
      ];
    }
  }


  // Metode untuk mendapatkan detail pengajuan lembur berdasarkan ID
  public function actionGetByPengajuan($id_pengajuan_lembur)
  {
    $model = $this->modelClass::find()
      ->select(['pengajuan_lembur.*', 'user.username as disetujui_oleh',])
      ->leftJoin('user', 'user.id  = pengajuan_lembur.disetujui_oleh')
      ->where(['id_pengajuan_lembur' => $id_pengajuan_lembur])
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
      throw new NotFoundHttpException('Pengajuan Lembur tidak ditemukan.');
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
