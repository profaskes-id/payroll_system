<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "riwayat_kesehatan".
 *
 * @property int $id_riwayat_kesehatan
 * @property int $id_karyawan
 * @property string $nama_pengecekan
 * @property string|null $keterangan
 * @property string|null $surat_dokter
 * @property string $tanggal
 *
 * @property Karyawan $karyawan
 */
class RiwayatKesehatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'riwayat_kesehatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'nama_pengecekan', 'tanggal'], 'required'],
            [['id_karyawan'], 'integer'],
            [['keterangan'], 'string'],
            [['tanggal'], 'safe'],
            [['nama_pengecekan', 'surat_dokter'], 'string', 'max' => 255],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_riwayat_kesehatan' => 'Id Riwayat Kesehatan',
            'id_karyawan' => 'Id Karyawan',
            'nama_pengecekan' => 'Nama Pengecekan',
            'keterangan' => 'Keterangan',
            'surat_dokter' => 'Surat Dokter',
            'tanggal' => 'Tanggal',
        ];
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
