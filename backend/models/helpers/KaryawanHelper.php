<?php

namespace backend\models\helpers;

use backend\models\Karyawan;


class KaryawanHelper extends Karyawan
{
    public static function getKaryawanData()
    {
        return Karyawan::find()->select(['id_karyawan', 'nama'])->where(['is_aktif' => 1])->asArray()->all();
    }
}
