<?php

namespace backend\models;

use Yii;

class Tanggal extends \yii\db\ActiveRecord
{
    public function getBulan($bulan)
    {
        $namabulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        return $namabulan[$bulan - 1];
    }

    public function getIndonesiaFormatLong($datetime)
    {
        $hari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $bulan = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $tanggal = date('l, d F Y', strtotime($datetime));
        $tanggal = str_replace(array_keys($hari), array_values($hari), $tanggal);
        $tanggal = str_replace(array_keys($bulan), array_values($bulan), $tanggal);

        return $tanggal;
    }
    public function getIndonesiaFormatTanggal($datetime)
    {

        $bulan = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];
        $tanggal = date('d F Y', strtotime($datetime));
        $tanggal = str_replace(array_keys($bulan), array_values($bulan), $tanggal);

        return $tanggal;
    }
}
