<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cetak".
 *
 * @property int $id_cetak
 * @property int $id_karyawan
 * @property int $id_data_pekerjaan
 * @property string $nomor_surat
 * @property string $tempat_dan_tanggal_surat
 * @property string $nama_penanda_tangan
 * @property string $jabatan_penanda_tangan
 * @property string|null $deskripsi_perusahaan
 * @property int $status
 * @property string|null $surat_upload
 *
 * @property DataPekerjaan $dataPekerjaan
 * @property Karyawan $karyawan
 */
class Cetak extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cetak';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'id_data_pekerjaan', 'nomor_surat', 'tempat_dan_tanggal_surat', 'nama_penanda_tangan', 'jabatan_penanda_tangan'], 'required'],
            [['id_karyawan', 'id_data_pekerjaan', 'status'], 'integer'],
            [['nomor_surat', 'tempat_dan_tanggal_surat', 'nama_penanda_tangan', 'jabatan_penanda_tangan', 'surat_upload'], 'string', 'max' => 255],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['id_data_pekerjaan'], 'exist', 'skipOnError' => true, 'targetClass' => DataPekerjaan::class, 'targetAttribute' => ['id_data_pekerjaan' => 'id_data_pekerjaan']],
            [['surat_upload'], 'file', 'extensions' => 'png, jpg, jpeg, pdf', 'maxSize' => 1024 * 1024 * 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cetak' => 'Id Cetak',
            'id_karyawan' => 'Id Karyawan',
            'id_data_pekerjaan' => 'Id Data Pekerjaan',
            'nomor_surat' => 'Nomor Surat',
            'tempat_dan_tanggal_surat' => 'Tempat Dan Tanggal Surat',
            'nama_penanda_tangan' => 'Nama Penanda Tangan',
            'jabatan_penanda_tangan' => 'Jabatan Penanda Tangan',
            'status' => 'Status',
            'surat_upload' => 'Surat Upload',
        ];
    }

    /**
     * Gets query for [[DataPekerjaan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDataPekerjaan()
    {
        return $this->hasOne(DataPekerjaan::class, ['id_data_pekerjaan' => 'id_data_pekerjaan']);
    }

    /**
     * Gets query for [[Karyawan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKaryawan()
    {
        return $this->hasOne(Karyawan::class, ['id_karyawan' => 'id_karyawan']);
    }
}
