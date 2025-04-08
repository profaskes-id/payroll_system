<?php

namespace app\modules\v1\controllers;

use amnah\yii2\user\models\User as User;
use yii\rest\ActiveController;
use yii\web\Response;
use Yii;

class UserController extends ActiveController
{
    public $modelClass = User::class;


    public function actionSaveToken($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Ambil token dari raw body request
        $rawBody = Yii::$app->request->getRawBody();

        // Misalnya token yang diterima berbentuk JSON {"fcm_token": "token_string"}
        $data = json_decode($rawBody, true);

        // Validasi jika token tidak ada atau kosong
        if (!isset($data['fcm_token']) || empty($data['fcm_token'])) {
            return [
                'status' => 'error',
                'message' => 'Token tidak ditemukan atau kosong.',
            ];
        }

        $token = $data['fcm_token'];

        // Cari user berdasarkan id_karyawan
        $user = User::findOne(['id' => $id]);

        if (!$user) {
            return [
                'status' => 'error',
                'message' => 'User dengan ID karyawan ' . $id . ' tidak ditemukan.',
            ];
        }

        // Update token FCM pada user yang ditemukan
        $user->fcm_token = $token;
        if ($user->save()) {
            return [
                'status' => 'success',
                'message' => 'Token berhasil disimpan/diupdate untuk ID ' . $id,
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal menyimpan atau mengupdate token.',
                'errors' => $user->errors,
            ];
        }
    }
}
