<?php

namespace app\modules\v1\controllers;

use backend\models\IzinPulangCepat as IzinPulangCepat;
use yii\rest\ActiveController;

class IzinPulangCepatController extends ActiveController
{
    public $modelClass = IzinPulangCepat::class;


    public function actionGetByKaryawanToday($id_karyawan)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $izin = $this->modelClass::find()
            ->where(['id_karyawan' => $id_karyawan, 'tanggal' => date('Y-m-d')])
            ->one();
    
        if ($izin === null) {
            return [
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ];
        }
    
        return $izin; // Atau format lain jika diperlukan
    }
}
