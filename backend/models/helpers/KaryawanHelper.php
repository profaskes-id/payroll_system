<?php

namespace backend\models\helpers;

use backend\models\Karyawan;


class KaryawanHelper extends Karyawan
{
    public static function getKaryawanData()
    {
        return Karyawan::find()->select(['id_karyawan', 'nama'])->where(['is_aktif' => 1])->asArray()->all();
    }

    public static function getIdKaryawan($kode_karyawan)
    {
        return Karyawan::find()->select(['id_karyawan',])->where(['kode_karyawan' => $kode_karyawan])->asArray()->one();
    }
    public static function getKaryawanById($id_karyawan)
    {
        $data =  Karyawan::find()->select(['id_karyawan', 'nama'])->where(['id_karyawan' => $id_karyawan])->asArray()->all();
        return $data;
    }
}
