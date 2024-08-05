<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "riwayat_pendidikan".
 *
 * @property int $id_riwayat_pendidikan
 * @property int $id_karyawan
 * @property int $jenjang_pendidikan
 * @property string $institusi
 * @property int $tahun_masuk
 * @property int $tahun_keluar
 *
 * @property Karyawan $karyawan
 */
class RiwayatPendidikan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'riwayat_pendidikan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'jenjang_pendidikan', 'institusi', 'tahun_masuk', 'tahun_keluar'], 'required'],
            [['id_karyawan', 'jenjang_pendidikan', 'tahun_masuk', 'tahun_keluar'], 'integer'],
            [['institusi'], 'string', 'max' => 255],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_riwayat_pendidikan' => 'Id Riwayat Pendidikan',
            'id_karyawan' => 'Id Karyawan',
            'jenjang_pendidikan' => 'Jenjang Pendidikan',
            'institusi' => 'Institusi',
            'tahun_masuk' => 'Tahun Masuk',
            'tahun_keluar' => 'Tahun Keluar',
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
