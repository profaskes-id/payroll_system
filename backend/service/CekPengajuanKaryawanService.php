<?php

namespace backend\service;

use backend\models\IzinPulangCepat;
use backend\models\PengajuanLembur;
use yii\web\NotFoundHttpException;

class CekPengajuanKaryawanService
{

    public static function CekLemburService($id_karyawan)
    {
        $lembur = PengajuanLembur::find()->asArray()->where(['id_karyawan' => $id_karyawan])->all();
        $hasilLembur = [];

        if ($lembur) {
            foreach ($lembur as $l) {
                if (isset($l['tanggal']) && $l['tanggal'] == date('Y-m-d') && $l['status'] == '1') {
                    $hasilLembur[] = $l;
                }
            }
        }
        return $hasilLembur;
    }



    public static function CekIzinPulangCepatHariIniService($id_karyawan)
    {
        return IzinPulangCepat::find()->where(['id_karyawan' => $id_karyawan, 'tanggal' => date('Y-m-d')])->one();
    }
}
