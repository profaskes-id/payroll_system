<?php

namespace app\modules\v1\controllers;

use backend\models\Karyawan;
use backend\models\RiwayatPelatihan as RiwayatPelatihanModel;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

class RiwayatPelatihanController extends ActiveController
{
    public $modelClass = RiwayatPelatihanModel::class;


    // Tambahkan method options untuk CORS
    public function actionOptions()
    {
        \Yii::$app->response->statusCode = 200;
        return true;
    }
    
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate()
{
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    // Nonaktifkan CSRF validation untuk API
    $this->enableCsrfValidation = false;

    // Validasi keberadaan data karyawan (jika diperlukan)
    $id_karyawan = Yii::$app->request->post('id_karyawan');
    if (!$id_karyawan) {
        return [
            'success' => false,
            'message' => 'ID Karyawan tidak ditemukan'
        ];
    }

    // Validasi karyawan (opsional)
    $karyawan = Karyawan::findOne($id_karyawan);
    if (!$karyawan) {
        return [
            'success' => false,
            'message' => 'Karyawan tidak ditemukan'
        ];
    }

    $model = new RiwayatPelatihanModel();

    // Ambil data dari berbagai sumber
    $postData = Yii::$app->request->post();

    // Jika data kosong, coba ambil dari raw input
    if (empty($postData)) {
        $rawInput = Yii::$app->request->getRawBody();
        $postData = json_decode($rawInput, true);
    }

    // Debug informasi input
    \Yii::info([
        'POST' => Yii::$app->request->post(),
        'FILES' => $_FILES,
        'RawInput' => $postData
    ], 'riwayat_pelatihan_debug');

    // Load data ke model
    if (!$model->load($postData, '')) {
        return [
            'success' => false,
            'message' => 'Gagal memuat data',
            'errors' => $model->errors
        ];
    }

    // Proses upload sertifikat
    $uploadedFile = null;
    
    // Coba ambil file dari beberapa sumber
    if (isset($_FILES['sertifikat'])) {
        // Dari $_FILES (multipart form-data)
        $uploadedFile = UploadedFile::getInstanceByName('sertifikat');
    } elseif (Yii::$app->request->post('sertifikat')) {
        // Dari POST data (base64)
        $base64File = Yii::$app->request->post('sertifikat');
        $uploadedFile = $this->base64ToUploadedFile($base64File);
    }

    // Proses upload file
    if ($uploadedFile) {
        // Tentukan direktori upload
        $uploadPath = \Yii::getAlias('@webroot/uploads/sertifikat/');
        
        // Pastikan direktori ada
        FileHelper::createDirectory($uploadPath, 0775, true);

        // Generate nama file unik
        $fileName = $this->generateUniqueFileName($uploadedFile);
        $fullPath = $uploadPath . $fileName;

        // Simpan file
        if ($uploadedFile->saveAs($fullPath)) {
            // Set nama file ke model
            $model->sertifikat = "uploads/sertifikat/" . $fileName;
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menyimpan sertifikat'
            ];
        }
    }

    // Set ID karyawan
    $model->id_karyawan = $id_karyawan;
    // Simpan model
    if ($model->save()) {
        return [
            'success' => true,
            'message' => 'Berhasil Menambahkan Data Riwayat Pelatihan',
            'data' => $model
        ];
    } else {
        // Hapus file yang sudah diupload jika model gagal disimpan
        if (isset($fullPath) && file_exists($fullPath)) {
            unlink($fullPath);
        }

        return [
            'success' => false,
            'message' => 'Gagal Menambahkan Data Riwayat Pelatihan',
            'errors' => $model->errors
        ];
    }
}

// Metode helper untuk mengkonversi base64 ke UploadedFile
protected function base64ToUploadedFile($base64String)
{
    // Dekode base64
    $fileData = base64_decode($base64String);
    
    // Buat nama file sementara
    $tmpFilePath = \Yii::getAlias('@runtime/') . uniqid() . '.tmp';
    
    // Simpan file sementara
    file_put_contents($tmpFilePath, $fileData);
    
    // Buat UploadedFile
    return new UploadedFile([
        'name' => basename($tmpFilePath),
        'tempName' => $tmpFilePath,
        'type' => mime_content_type($tmpFilePath),
        'size' => filesize($tmpFilePath),
        'error' => UPLOAD_ERR_OK
    ]);
}

// Metode helper untuk generate nama file unik
protected function generateUniqueFileName($uploadedFile)
{
    return uniqid() . '_' . time() . '.' . $uploadedFile->extension;
}

    public function actionByKaryawan($id_karyawan)
    {
        return $this->modelClass::findAll(['id_karyawan' => $id_karyawan]);
    }

    public function actionCostumeUpdate($id)
    {
        // Set response format to JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            // Cari model berdasarkan ID
            $model = $this->modelClass::findOne($id);

            // Jika model tidak ditemukan
            if (!$model) {
                throw new NotFoundHttpException("Data keluarga dengan ID $id tidak ditemukan");
            }

            // Ambil body parameters
            $rawBody = Yii::$app->request->getRawBody();
            $bodyParams = json_decode($rawBody, true);


            // Validasi apakah ada data yang dikirim
            if (empty($bodyParams)) {
                return [
                    'status' => 'error',
                    'message' => 'Tidak ada data untuk diupdate'
                ];
            }

            // Debug: Tampilkan parameter yang diterima
            // Nonaktifkan validasi
            $model->scenario = 'default';

            // Update atribut secara manual
            foreach ($bodyParams as $key => $value) {
                if ($model->hasAttribute($key)) {
                    $model->setAttribute($key, $value);
                }
            }

            // Simpan model tanpa validasi
            $result = $model->save(false);

            if ($result) {
                return [
                    'status' => 'success',
                    'message' => 'Data berhasil diupdate',
                    'data' => $model
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Gagal menyimpan data'
                ];
            }
        } catch (\Exception $e) {
            // Tangkap exception yang tidak terduga
            Yii::error('Update Error: ' . $e->getMessage());

            return [
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Konfigurasi response format
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        // Konfigurasi verb filter
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::class,
            'actions' => [
                'costume-update' => ['PUT', 'PATCH'],
                'create' => ['POST'],
            ],
        ];

        // Nonaktifkan authenticator jika ada
        unset($behaviors['authenticator']);

        return $behaviors;
    }
}
