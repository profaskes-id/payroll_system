<?php

namespace backend\service;

use backend\models\Absensi;
use backend\models\Karyawan;
use DateTime;

class RekapService
{
    public static function RekapData($params = null)
    {
        $model  = new Absensi();
        $karyawan = new Karyawan();


        $firstDayOfMonth = $params['tanggal_awal'];
        $lastDayOfMonth = $params['tanggal_akhir'];

        // ! Get total karyawan
        $karyawanTotal = $karyawan::find()->where(['is_aktif' => 1])->count();
        //! mendapatkan seluruh data absensi karyawan,jam-karyawan dari firstDayOfMonth - lastDayOfMonth
        $absensi = $model->getAllAbsensiFromFirstAndLastMonth($model, $firstDayOfMonth, $lastDayOfMonth);


        //    ! get all data dari tanggal awal dan akhir bulan
        $tanggal_bulanan = $model->getTanggalFromFirstAndLastMonth($firstDayOfMonth, $lastDayOfMonth);
        $dataKaryawan = $model->getAllDetailDataKaryawan($karyawan);
        // memasukan absensi ke masing masing data karyawan
        $absensiAndTelat = $model->getIncludeKaryawanAndAbsenData($dataKaryawan, $absensi, $firstDayOfMonth, $lastDayOfMonth, $tanggal_bulanan);

        $keterlambatanPerTanggal = $absensiAndTelat['keterlambatanPerTanggal'];

        $rekapanAbsensi = [];
        $tanggalBulan = $tanggal_bulanan;
        $firstDayOfMonth = $params['tanggal_awal'];  // "2025-01-27"
        $lastDayOfMonth = $params['tanggal_akhir'];  // "2025-02-26"

        // Ambil data absensi
        $dataAbsensiHadir = $model->getAbsnesiDataWereHadir($model, $firstDayOfMonth, $lastDayOfMonth);

        // Siapkan tanggal bulanan (semua tanggal dari awal ke akhir)
        $tanggalBulan = [];
        $start = new DateTime($firstDayOfMonth);
        $end = new DateTime($lastDayOfMonth);
        while ($start <= $end) {
            $tanggalBulan[] = $start->format('Y-m-d');
            $start->modify('+1 day');
        }

        // Hitung jumlah absensi hadir per tanggal
        $rekapanAbsensi = [];
        foreach ($dataAbsensiHadir as $absensi) {
            $tanggal = $absensi['tanggal'];
            $rekapanAbsensi[$tanggal] = isset($rekapanAbsensi[$tanggal]) ? $rekapanAbsensi[$tanggal] + 1 : 1;
        }

        // Pastikan setiap tanggal ada, kalau tidak, isi 0
        foreach ($tanggalBulan as $tanggal) {
            if (!isset($rekapanAbsensi[$tanggal])) {
                $rekapanAbsensi[$tanggal] = 0;
            }
        }

        // Urutkan berdasarkan tanggal
        ksort($rekapanAbsensi);

        return [
            'tanggal_bulanan' => $tanggal_bulanan,
            'hasil' => $absensiAndTelat['hasil'],
            'rekapanAbsensi' => $rekapanAbsensi,
            'karyawanTotal' => $karyawanTotal,
            'keterlambatanPerTanggal' => $keterlambatanPerTanggal,

        ];
    }
}
