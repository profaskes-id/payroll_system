<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rekap_terlambat_transaksi_gaji".
 *
 * @property int $id_rekap_terlambat
 * @property int $id_karyawan
 * @property int|null $bulan
 * @property int|null $tahun
 * @property string $nama
 * @property string $tanggal
 * @property string $lama_terlambat
 * @property float $potongan_permenit
 */
class RekapTerlambatTransaksiGaji extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rekap_terlambat_transaksi_gaji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan', 'tahun'], 'default', 'value' => null],
            [['id_karyawan', 'nama', 'tanggal', 'lama_terlambat', 'potongan_permenit'], 'required'],
            [['id_karyawan', 'bulan', 'tahun'], 'integer'],
            [['tanggal'], 'safe'],
            [['potongan_permenit'], 'number'],
            [['nama'], 'string', 'max' => 255],
            [['lama_terlambat'], 'string', 'max' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_rekap_terlambat' => 'Id Rekap Terlambat',
            'id_karyawan' => 'Id Karyawan',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'nama' => 'Nama',
            'tanggal' => 'Tanggal',
            'lama_terlambat' => 'Lama Terlambat',
            'potongan_permenit' => 'Potongan Permenit',
        ];
    }

}
