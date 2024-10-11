<?php

namespace backend\models;

class Terbilang
{
    public static function toTerbilang($angka)
    {
        $terbilang = [
            0 => '',
            1 => 'Satu',
            2 => 'Dua',
            3 => 'Tiga',
            4 => 'Empat',
            5 => 'Lima',
            6 => 'Enam',
            7 => 'Tujuh',
            8 => 'Delapan',
            9 => 'Sembilan',
            10 => 'Sepuluh',
            11 => 'Sebelas',
            12 => 'Duabelas',
            13 => 'Tigabelas',
            14 => 'Empatbelas',
            15 => 'Limabelas',
            16 => 'Enambelas',
            17 => 'Tujuhbelas',
            18 => 'Delapanbelas',
            19 => 'Sembilanbelas'
        ];

        $satuan = ['', 'Ribu', 'Juta', 'Miliar', 'Triliun'];

        if ($angka < 20) {
            return $terbilang[$angka];
        } elseif ($angka < 100) {
            return $terbilang[intval($angka / 10)] . ' Puluh ' . self::toTerbilang($angka % 10);
        } elseif ($angka < 200) {
            return 'Seratus ' . self::toTerbilang($angka - 100);
        } elseif ($angka < 1000) {
            return $terbilang[intval($angka / 100)] . ' Ratus ' . self::toTerbilang($angka % 100);
        } else {
            $posisi = 0;
            $hasil = '';

            while ($angka > 0) {
                $ratusan = $angka % 1000;
                $angka = intval($angka / 1000);
                if ($ratusan > 0) {
                    $hasil = self::toTerbilang($ratusan) . ' ' . $satuan[$posisi] . ' ' . $hasil;
                }
                $posisi++;
            }

            return $hasil;
        }
    }
}
