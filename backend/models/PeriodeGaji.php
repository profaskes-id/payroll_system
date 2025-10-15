<?php

namespace backend\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "periode_gaji".
 *
 * @property int $bulan
 * @property int $tahun
 * @property string $tanggal_awal
 * @property string $tanggal_akhir
 * @property string|null $terima
 *
 * @property TransaksiGaji[] $transaksiGajis
 */
class PeriodeGaji extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periode_gaji';
    }

    /**
     * {@inheritdoc}
     */

    public $tanggal_set;
    public function rules()
    {
        return [
            [['bulan', 'tahun', 'tanggal_awal', 'tanggal_akhir'], 'required'],
            [['bulan', 'tahun', 'tanggal_set'], 'integer'],
            [['tanggal_awal', 'tanggal_akhir', 'terima', 'tanggal_set'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_periode_gaji' => 'Id Periode Gaji',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'tanggal_awal' => 'Tanggal Awal',
            'tanggal_akhir' => 'Tanggal Akhir',
            'terima' => 'Terima',
        ];
    }

    function generateDateRanges($tahun, $tanggal_set)
    {
        $result = [];

        for ($i = 1; $i <= 12; $i++) {
            // Menentukan tahun dan bulan untuk tanggal_awal (bulan sebelumnya)
            $prev_month = $i - 1;
            $prev_year = $tahun;

            if ($prev_month === 0) {
                $prev_month = 12;
                $prev_year = $tahun - 1;
            }

            // Tanggal awal = tanggal_set bulan sebelumnya
            $tanggal_awal = sprintf('%04d-%02d-%02d', $prev_year, $prev_month, $tanggal_set);

            // Tanggal akhir = tanggal_set - 1 di bulan ini
            $tanggal_akhir = sprintf('%04d-%02d-%02d', $tahun, $i, $tanggal_set - 1);

            // Tanggal terima = tanggal_akhir + 1 hari
            $tanggal_terima = date('Y-m-d', strtotime($tanggal_akhir . ' +1 day'));

            // Hitung hari per bulan
            $start_date = new DateTime($tanggal_awal);
            $end_date = new DateTime($tanggal_akhir);

            $periode_start = clone $start_date;
            $periode_end = clone $end_date;

            $hari_di_bulan_awal = 0;
            $hari_di_bulan_berikutnya = 0;

            while ($periode_start <= $periode_end) {
                if ((int)$periode_start->format('n') == $prev_month) {
                    $hari_di_bulan_awal++;
                } else {
                    $hari_di_bulan_berikutnya++;
                }
                $periode_start->modify('+1 day');
            }

            // Tentukan bulan dominan (bulan gaji)
            if ($hari_di_bulan_awal > $hari_di_bulan_berikutnya) {
                $bulan_gaji = $prev_month;
            } else {
                $bulan_gaji = $i;
            }

            $result[] = [
                'bulan' => $bulan_gaji,
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'tanggal_terima' => $tanggal_terima,
                'hari_di_bulan_awal' => $hari_di_bulan_awal,
                'hari_di_bulan_berikutnya' => $hari_di_bulan_berikutnya,
            ];
        }

        return $result;
    }
}
