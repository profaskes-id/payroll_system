<?php

namespace backend\models;

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
            $bulan = $i;
            $tanggal_awal = sprintf('%04d-%02d-%02d', $tahun, $i, $tanggal_set);

            // Menghitung tanggal akhir
            if ($i == 12) { // Jika bulan Desember
                $tanggal_akhir = sprintf('%04d-%02d-%02d', $tahun + 1, 1, $tanggal_set - 1);
            } else {
                $tanggal_akhir = sprintf('%04d-%02d-%02d', $tahun, $i + 1, $tanggal_set - 1);
            }

            // Menghitung tanggal terima (tanggal akhir + 1 hari)
            $tanggal_terima = date('Y-m-d', strtotime($tanggal_akhir . ' +1 day'));

            $result[] = [
                'bulan' => $bulan,
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'tanggal_terima' => $tanggal_terima,
            ];
        }

        return $result;
    }
}
