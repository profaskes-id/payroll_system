<?php

namespace backend\service;

class LemburService
{
    /**
     * Menghitung lembur berdasarkan konfigurasi kalkulasi.
     *
     * @param int $menit Total menit lembur (misalnya 120)
     * @param int $kalkulasi_jam_lembur 1 = normal, 0 = pakai aturan khusus
     * @return int Total menit lembur setelah dikalkulasi
     */
    public static function getHitunganJamLembur(int $menit, int $kalkulasi_jam_lembur): int
    {
        if ($menit <= 0) {
            return 0;
        }

        if ($kalkulasi_jam_lembur == 1) {
            // Tidak ada perubahan, return seperti input
            return $menit;
        }

        // Konversi menit ke jam desimal
        $jam = $menit / 60;

        if ($jam <= 1) {
            // Jam pertama dihitung 1.5 jam
            $totalJam = 1.5;
        } else {
            // 1 jam pertama = 1.5 jam, sisanya = 2 jam per jam
            $totalJam = 1.5 + (($jam - 1) * 2);
        }

        // Konversi kembali ke menit
        return (int) round($totalJam * 60);
    }
}
