<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tunjangan_rekap_gaji".
 *
 * @property int $id_tunjangan_rekap_gaji
 * @property int $id_karyawan
 * @property int|null $bulan
 * @property int|null $tahun
 * @property int $id_tunjangan
 * @property string $nama_tunjangan
 * @property float $jumlah
 */
class TunjanganRekapGaji extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tunjangan_rekap_gaji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan', 'tahun'], 'default', 'value' => null],
            [['id_karyawan', 'id_tunjangan', 'nama_tunjangan', 'jumlah'], 'required'],
            [['id_karyawan', 'bulan', 'tahun', 'id_tunjangan'], 'integer'],
            [['jumlah'], 'number'],
            [['nama_tunjangan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tunjangan_rekap_gaji' => 'Id Tunjangan Rekap Gaji',
            'id_karyawan' => 'Id Karyawan',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'id_tunjangan' => 'Tunjangan',
            'nama_tunjangan' => 'Nama Tunjangan',
            'jumlah' => 'Jumlah',
        ];
    }
}
