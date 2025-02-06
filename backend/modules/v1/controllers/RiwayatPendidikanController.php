<?php

namespace app\modules\v1\controllers;

use backend\models\MasterKode;
use backend\models\RiwayatPendidikan as RiwayatPendidikanModel;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class RiwayatPendidikanController extends ActiveController
{
    public $modelClass = RiwayatPendidikanModel::class;


    public function actionByKaryawan($id_karyawan)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = $this->modelClass::find()
            ->select(['riwayat_pendidikan.*', 'master_kode.nama_kode as jenjang_pendidikan'])
            ->leftJoin('master_kode', '  master_kode.nama_group = "jenjang-pendidikan" and riwayat_pendidikan.jenjang_pendidikan = master_kode.kode')
            ->where(['id_karyawan' => $id_karyawan])->asArray()->all();
        return $data;
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


    public function actionJenjangPendidikan()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = MasterKode::find()->where(['nama_group' => Yii::$app->params['jenjang-pendidikan']])->asArray()->all();
        // dd($data);
        return $data;
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
            ],
        ];

        // Nonaktifkan authenticator jika ada
        unset($behaviors['authenticator']);

        return $behaviors;
    }
}
