<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "riwayat_pelatihan".
 *
 * @property int $id_riwayat_pelatihan
 * @property int $id_karyawan
 * @property string $judul_pelatihan
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string $penyelenggara
 * @property string|null $deskripsi
 * @property string|null $sertifikat
 *
 * @property Karyawan $karyawan
 */
class RiwayatPelatihan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'riwayat_pelatihan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'judul_pelatihan', 'tanggal_mulai', 'tanggal_selesai', 'penyelenggara'], 'required'],
            [['id_karyawan'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['deskripsi'], 'string'],
            [['judul_pelatihan', 'penyelenggara', 'sertifikat'], 'string', 'max' => 255],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_riwayat_pelatihan' => 'Id Riwayat Pelatihan',
            'id_karyawan' => 'Id Karyawan',
            'judul_pelatihan' => 'Judul Pelatihan',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'penyelenggara' => 'Penyelenggara',
            'deskripsi' => 'Deskripsi',
            'sertifikat' => 'Sertifikat',
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
