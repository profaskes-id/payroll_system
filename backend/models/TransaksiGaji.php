<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "transaksi_gaji".
 *
 * @property int $id_transaksi_gaji
 * @property string $nomer_identitas
 * @property string $nama
 * @property string $bagian
 * @property string $jabatan
 * @property int $jam_kerja
 * @property string $status_karyawan
 * @property int $periode_gaji_bulan
 * @property int $periode_gaji_tahun
 * @property int $jumlah_hari_kerja
 * @property int $jumlah_hadir
 * @property int $jumlah_sakit
 * @property int $jumlah_wfh
 * @property int $jumlah_cuti
 * @property float $gaji_pokok
 * @property string $jumlah_jam_lembur
 * @property float $lembur_perjam
 * @property float $total_lembur
 * @property float $jumlah_tunjangan
 * @property float $jumlah_potongan
 * @property float $potongan_wfh_hari
 * @property float $jumlah_potongan_wfh
 * @property float|null $gaji_diterima
 *
 * @property PeriodeGaji $periodeGajiBulan
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
            [['nomer_identitas', 'nama', 'bagian', 'jabatan', 'jam_kerja', 'status_karyawan', 'periode_gaji_bulan', 'periode_gaji_tahun', 'jumlah_hari_kerja', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_wfh', 'jumlah_cuti', 'gaji_pokok', 'jumlah_jam_lembur', 'lembur_perjam', 'total_lembur', 'jumlah_tunjangan', 'jumlah_potongan', 'potongan_wfh_hari', 'jumlah_potongan_wfh'], 'required'],
            [['jam_kerja', 'periode_gaji_bulan', 'periode_gaji_tahun', 'jumlah_hari_kerja', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_wfh', 'jumlah_cuti'], 'integer'],
            [['gaji_pokok', 'lembur_perjam', 'total_lembur', 'jumlah_tunjangan', 'jumlah_potongan', 'potongan_wfh_hari', 'jumlah_potongan_wfh', 'gaji_diterima'], 'number'],
            [['jumlah_jam_lembur'], 'safe'],
            [['nomer_identitas', 'nama', 'bagian', 'jabatan', 'status_karyawan'], 'string', 'max' => 255],
            [['periode_gaji_bulan', 'periode_gaji_tahun'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodeGaji::class, 'targetAttribute' => ['periode_gaji_bulan' => 'bulan', 'periode_gaji_tahun' => 'tahun']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_transaksi_gaji' => 'Id Transaksi Gaji',
            'nomer_identitas' => 'Nomer Identitas',
            'nama' => 'Nama',
            'bagian' => 'Bagian',
            'jabatan' => 'Jabatan',
            'jam_kerja' => 'Jam Kerja',
            'status_karyawan' => 'Status Karyawan',
            'periode_gaji_bulan' => 'Periode Gaji Bulan',
            'periode_gaji_tahun' => 'Periode Gaji Tahun',
            'jumlah_hari_kerja' => 'Jumlah Hari Kerja',
            'jumlah_hadir' => 'Jumlah Hadir',
            'jumlah_sakit' => 'Jumlah Sakit',
            'jumlah_wfh' => 'Jumlah Wfh',
            'jumlah_cuti' => 'Jumlah Cuti',
            'gaji_pokok' => 'Gaji Pokok',
            'jumlah_jam_lembur' => 'Jumlah Jam Lembur',
            'lembur_perjam' => 'Lembur Perjam',
            'total_lembur' => 'Total Lembur',
            'jumlah_tunjangan' => 'Jumlah Tunjangan',
            'jumlah_potongan' => 'Jumlah Potongan',
            'potongan_wfh_hari' => 'Potongan Wfh Hari',
            'jumlah_potongan_wfh' => 'Jumlah Potongan Wfh',
            'gaji_diterima' => 'Gaji Diterima',
        ];
    }

    /**
     * Gets query for [[PeriodeGajiBulan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodeGajiBulan()
    {
        return $this->hasOne(PeriodeGaji::class, ['bulan' => 'periode_gaji_bulan', 'tahun' => 'periode_gaji_tahun']);
    }
}
