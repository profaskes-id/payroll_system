<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "potongan_rekap_gaji".
 *
 * @property int $id_potongan_rekap_gaji
 * @property int $id_karyawan
 * @property int|null $bulan
 * @property int|null $tahun
 * @property int $id_potongan
 * @property string $nama_potongan
 * @property float $jumlah
 */
class PotonganRekapGaji extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'potongan_rekap_gaji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan', 'tahun'], 'default', 'value' => null],
            [['id_karyawan', 'id_potongan', 'nama_potongan', 'jumlah'], 'required'],
            [['id_karyawan', 'bulan', 'tahun', 'id_potongan'], 'integer'],
            [['jumlah'], 'number'],
            [['nama_potongan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_potongan_rekap_gaji' => 'Id Potongan Rekap Gaji',
            'id_karyawan' => 'Id Karyawan',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'id_potongan' => 'Id Potongan',
            'nama_potongan' => 'Nama Potongan',
            'jumlah' => 'Jumlah',
        ];
    }

}
