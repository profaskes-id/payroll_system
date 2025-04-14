<?php

namespace app\modules\v1\controllers;

use amnah\yii2\user\models\User;
use backend\models\AtasanKaryawan;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\MobileNotificationHelper;
use Yii;
use backend\models\helpers\NotificationHelper;
use backend\models\PengajuanLembur as PengajuanLemburModel;
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

    // Ambil data dari request
    $request = Yii::$app->request;

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

    // Menghitung durasi lembur
    $jamMulai = strtotime($request->post('jam_mulai'));
    $jamSelesai = strtotime($request->post('jam_selesai'));

    // Jika jam selesai lebih kecil dari jam mulai, berarti sudah berbeda hari
    if ($jamSelesai < $jamMulai) {
      $jamSelesai += 24 * 60 * 60; // Tambahkan 24 jam dalam detik
    }

    // Menghitung selisih waktu dalam detik
    $selisihDetik = $jamSelesai - $jamMulai;

    // Mengkonversi selisih waktu ke dalam format jam:menit
    $model = new PengajuanLemburModel();
    $model->durasi = gmdate('H:i', $selisihDetik); // Format durasi dalam jam:menit

    // Menghitung durasi dalam menit
    $durasiMenit = $selisihDetik / 60; // Durasi dalam menit
    $durasiJam = floor($durasiMenit / 60); // Durasi dalam jam (dibulatkan ke bawah)
    $durasiMenitSisa = $durasiMenit % 60; // Sisa menit setelah dibagi jam

    // Menghitung jumlah jam lembur sesuai dengan aturan yang diberikan
    $hitunganLembur = 0;

    // Hitung jam pertama (selalu 1.5 jam untuk 1 jam pertama)
    if ($durasiJam >= 1) {
      $hitunganLembur += 1.5; // 1 jam pertama dihitung 1.5 jam
      $durasiJam -= 1; // Kurangi jam pertama
    }

    // Hitung jam berikutnya (jam kedua dan seterusnya dihitung 2 jam per jam)
    if ($durasiJam > 0) {
      $hitunganLembur += $durasiJam * 2; // Setiap jam setelah jam pertama dihitung 2 jam
    }

    // Menambahkan waktu sisa menit, jika ada (pembulatan ke bawah)
    if ($durasiMenitSisa > 0) {
      // Pembulatan ke bawah:
      if ($durasiMenitSisa >= 30) {
        // Jika sisa menit lebih dari atau sama dengan 30 menit, hitung setengah jam tambahan
        $hitunganLembur += 1; // Tambah 1 jam untuk 30 menit atau lebih
      } else {
        // Jika sisa menit kurang dari 30 menit, hitung 0,5 jam (30 menit)
        $hitunganLembur += 0.5; // Tambah 0.5 jam
      }
    }

    // Menyimpan hasil hitungan lembur
    $model->id_karyawan = $request->post('id_karyawan');
    $model->pekerjaan = $request->post('pekerjaan'); // Simpan sebagai JSON string
    $model->tanggal = $request->post('tanggal'); // Format: YYYY-MM-DD
    $model->jam_mulai = $request->post('jam_mulai');
    $model->jam_selesai = $request->post('jam_selesai');
    $model->hitungan_jam = $hitunganLembur;
    $model->status = $request->post('status', 0); // Default status = 0 jika tidak ada

    // Simpan model ke database
    dd($model);
    if ($model->save()) {
      // Kirim notifikasi
      $atasan = $this->getAtasanKaryawan($model->id_karyawan);
      $adminUsers = null;
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
      Yii::$app->response->statusCode = 500; // Internal Server Error
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

        // Hitung jam pertama (selalu 1.5 jam untuk 1 jam pertama)
        if ($durasiJam >= 1) {
          $hitunganLembur += 1.5; // 1 jam pertama dihitung 1.5 jam
          $durasiJam -= 1; // Kurangi jam pertama
        }

        // Hitung jam berikutnya (jam kedua dan seterusnya dihitung 2 jam per jam)
        if ($durasiJam > 0) {
          $hitunganLembur += $durasiJam * 2; // Setiap jam setelah jam pertama dihitung 2 jam
        }

        // Menambahkan waktu sisa menit, jika ada (pembulatan ke bawah)
        if ($durasiMenitSisa > 0) {
          // Pembulatan ke bawah:
          if ($durasiMenitSisa >= 30) {
            // Jika sisa menit lebih dari atau sama dengan 30 menit, hitung setengah jam tambahan
            $hitunganLembur += 1; // Tambah 1 jam untuk 30 menit atau lebih
          } else {
            // Jika sisa menit kurang dari 30 menit, hitung 0,5 jam (30 menit)
            $hitunganLembur += 0.5; // Tambah 0.5 jam
          }
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
