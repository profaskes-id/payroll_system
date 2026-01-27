<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "transaksi_gaji".
 *
 * @property int $id_transaksi_gaji
 * @property int $id_karyawan
 * @property string $nama
 * @property int $id_bagian
 * @property string $nama_bagian
 * @property string $jabatan
 * @property int $bulan
 * @property int $tahun
 * @property string $tanggal_awal
 * @property string $tanggal_akhir
 * @property int $total_absensi
 * @property string $terlambat
 * @property int $total_alfa_range
 * @property float $nominal_gaji
 * @property float $gaji_perhari
 * @property float $tunjangan_karyawan
 * @property float $potongan_karyawan
 * @property float $potongan_terlambat
 * @property float $potongan_absensi
 * @property int $jam_lembur
 * @property float $total_pendapatan_lembur
 * @property float $dinas_luar_belum_terbayar
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class TransaksiGaji extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaksi_gaji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_bagian', 'nama_bagian', 'jabatan', 'bulan', 'tahun', 'tanggal_awal', 'tanggal_akhir', 'total_absensi', 'terlambat', 'total_alfa_range', 'nominal_gaji', 'gaji_perhari', 'tunjangan_karyawan', 'potongan_karyawan', 'potongan_kasbon', 'potongan_terlambat', 'potongan_absensi', 'jam_lembur', 'total_pendapatan_lembur', 'dinas_luar_belum_terbayar', 'created_by', 'updated_by', 'nama_bank', 'nomer_rekening', 'pendapatan_lainnya', 'potongan_lainnya'], 'default', 'value' => null],
            [['hari_kerja_efektif', 'gaji_diterima', 'status_pekerjaan'], 'default', 'value' => 0],
            // [['id_karyawan', 'nama'], 'required'],
            [['id_karyawan', 'id_bagian', 'bulan', 'tahun', 'total_absensi', 'total_alfa_range', 'jam_lembur', 'created_by', 'updated_by', 'status', 'hari_kerja_efektif'], 'integer'],
            [['tanggal_awal', 'tanggal_akhir', 'terlambat', 'created_at', 'updated_at', 'nama_bank', 'nomer_rekening'], 'safe'],
            [['nominal_gaji', 'gaji_perhari', 'tunjangan_karyawan', 'potongan_karyawan', 'potongan_kasbon', 'potongan_terlambat', 'potongan_absensi', 'total_pendapatan_lembur', 'dinas_luar_belum_terbayar', 'pendapatan_lainnya', 'potongan_lainnya'], 'number'],
            [['nama', 'nama_bagian', 'jabatan', 'nama_bank', 'nomer_rekening'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_transaksi_gaji' => 'Id Transaksi Gaji',
            'id_karyawan' => 'Id Karyawan',
            'nama' => 'Nama',
            'id_bagian' => 'Id Bagian',
            'status_pekerjaan' => 'Status Pekerjaan',
            'nama_bagian' => 'Nama Bagian',
            'jabatan' => 'Jabatan',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'tanggal_awal' => 'Tanggal Awal',
            'tanggal_akhir' => 'Tanggal Akhir',
            'total_absensi' => 'Total Absensi',
            'terlambat' => 'Terlambat',
            'total_alfa_range' => 'Total Alfa Range',
            'nominal_gaji' => 'Nominal Gaji',
            'gaji_perhari' => 'Gaji Perhari',
            'tunjangan_karyawan' => 'Tunjangan Karyawan',
            'potongan_karyawan' => 'Potongan Karyawan',
            'potongan_terlambat' => 'Potongan Terlambat',
            'potongan_absensi' => 'Potongan Absensi',
            'jam_lembur' => 'Jam Lembur',
            'total_pendapatan_lembur' => 'Total Pendapatan Lembur',
            'dinas_luar_belum_terbayar' => 'Dinas Luar Belum Terbayar',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'status' => 'status',
            'hari_kerja_efektif' => 'Hari Kerja Efektif',
            'nama_bank' => 'Nama Bank',
            'nomer_rekening' => 'Nomer Rekening',
            'pendapatan_lainnya' => 'Pendapatan Lainnya',
            'potongan_lainnya' => 'Potongan Lainnya',
            'gaji_diterima' => 'Gaji Diterima',

        ];
    }
}
