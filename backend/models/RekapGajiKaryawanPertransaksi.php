<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rekap_gaji_karyawan_pertransaksi".
 *
 * @property int $id_rekap_gaji_karyawan
 * @property int $id_karyawan
 * @property string $nama_karyawan
 * @property string $nama_bagian
 * @property string $jabatan
 * @property int $bulan
 * @property int $tahun
 * @property float $gaji_perbulan
 * @property float $gaji_perhari
 * @property float $gaji_perjam
 */
class RekapGajiKaryawanPertransaksi extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rekap_gaji_karyawan_pertransaksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'nama_karyawan', 'nama_bagian', 'jabatan', 'bulan', 'tahun', 'gaji_perbulan', 'gaji_perhari', 'gaji_perjam'], 'required'],
            [['id_karyawan', 'bulan', 'tahun'], 'integer'],
            [['gaji_perbulan', 'gaji_perhari', 'gaji_perjam'], 'number'],
            [['nama_karyawan', 'nama_bagian', 'jabatan'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_rekap_gaji_karyawan' => 'Id Rekap Gaji Karyawan',
            'id_karyawan' => 'Id Karyawan',
            'nama_karyawan' => 'Nama Karyawan',
            'nama_bagian' => 'Nama Bagian',
            'jabatan' => 'Jabatan',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'gaji_perbulan' => 'Gaji Perbulan',
            'gaji_perhari' => 'Gaji Perhari',
            'gaji_perjam' => 'Gaji Perjam',
        ];
    }

}
