<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "potongan_alfa_and_wfh_penggajian".
 *
 * @property int $id_alfa_wfh
 * @property int $id_karyawan
 * @property string $nama
 * @property int $bulan
 * @property int $tahun
 * @property int $jumlah_alfa
 * @property float $total_potongan_alfa
 * @property int $jumlah_wfh
 * @property float $persen_potongan_wfh
 * @property float $total_potongan_wfh
 * @property float $gaji_perhari
 */
class PotonganAlfaAndWfhPenggajian extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'potongan_alfa_and_wfh_penggajian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gaji_perhari'], 'default', 'value' => 0],
            [['id_karyawan', 'nama', 'bulan', 'tahun'], 'required'],
            [['id_karyawan', 'bulan', 'tahun', 'jumlah_alfa', 'jumlah_wfh'], 'integer'],
            [['total_potongan_alfa', 'persen_potongan_wfh', 'total_potongan_wfh', 'gaji_perhari'], 'number'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_alfa_wfh' => 'Id Alfa Wfh',
            'id_karyawan' => 'Id Karyawan',
            'nama' => 'Nama',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'jumlah_alfa' => 'Jumlah Alfa',
            'total_potongan_alfa' => 'Total Potongan Alfa',
            'jumlah_wfh' => 'Jumlah Wfh',
            'persen_potongan_wfh' => 'Persen Potongan Wfh',
            'total_potongan_wfh' => 'Total Potongan Wfh',
            'gaji_perhari' => 'Gaji Perhari',
        ];
    }

}
