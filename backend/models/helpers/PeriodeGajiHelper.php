<?php

namespace backend\models\helpers;

use backend\models\MasterKode;
use backend\models\PeriodeGaji;
use backend\models\Tanggal;

class PeriodeGajiHelper extends MasterKode
{
    public static function getPeriodeGaji($id_periode_gaji)
    {
        return PeriodeGaji::findOne($id_periode_gaji);
    }

    public static function getPeriodeGajiByBulanTahun($bulan, $tahun)
    {
        return PeriodeGaji::findOne(['bulan' => $bulan, 'tahun' => $tahun]);
    }

    public static function getPeriodeGajiBulanIni($bulan = null, $tahun = null)
    {
        if ($bulan == null) {
            $bulan = date('m');
        }
        if ($tahun == null) {
            $tahun = date('Y');
        }
        $data =  PeriodeGaji::findOne(['bulan' => $bulan, 'tahun' => $tahun]);
        return $data;
    }
}
