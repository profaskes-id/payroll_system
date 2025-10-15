<?php

namespace backend\service;

use backend\models\JadwalShift;
use backend\models\MasterHaribesar;
use backend\models\ShiftKerja;

/**
 * @return array|null
 */

class JamKerjaKaryawanService
{
    public static function getJamKerjaKaryawanHariIni($jamKerjaKaryawan, $jamKerjaHari, $hariIni, $manual_shift)
    {
        $tanggalHariIni = date('Y-m-d');
        // Jika shift
        if (isset($jamKerjaKaryawan) && $jamKerjaKaryawan['is_shift'] == 1) {
            $filtered = array_filter($jamKerjaHari, fn($item) => $item->nama_hari == $hariIni);

            $jadwalKerjaKaryawan = reset($filtered);
            if ($jadwalKerjaKaryawan === false) {
                return [];
            }

            if ($manual_shift == 1) {
                $jadwalShiftHariIni = JadwalShift::find()
                    ->where([
                        'id_karyawan' => $jamKerjaKaryawan['id_karyawan'],
                        'tanggal' => $tanggalHariIni
                    ])
                    ->asArray()
                    ->one();

                return  !empty($jadwalShiftHariIni)
                    ? ShiftKerja::find()
                    ->where(['id_shift_kerja' => $jadwalShiftHariIni['id_shift_kerja']])
                    ->asArray()
                    ->one() ?? []
                    : [];
            } else {
                $isAdaDataToday = $jadwalKerjaKaryawan ? true : false;

                return $isAdaDataToday;
            }
        }

        // Jika non-shift
        $isHariBesar = MasterHaribesar::find()
            ->select('tanggal')
            ->where(['tanggal' => $tanggalHariIni])
            ->exists();

        if ($isHariBesar) {
            return null;
        }

        $filteredJamKerja = array_filter($jamKerjaHari, fn($item) => $item->nama_hari == $hariIni);
        return reset($filteredJamKerja) ?: null;
    }
}
