<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "absensi".
 *
 * @property int $id_absensi
 * @property int $id_karyawan
 * @property int $id_jam_kerja
 * @property string $tanggal
 * @property string $hari
 * @property string|null $jam_masuk
 * @property string|null $jam_pulang
 * @property string $kode_status_hadir
 *
 * @property JamKerja $jamKerja
 * @property Karyawan $karyawan
 */
class Absensi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'absensi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'id_jam_kerja', 'tanggal', 'hari', 'kode_status_hadir'], 'required'],
            [['id_karyawan', 'id_jam_kerja'], 'integer'],
            [['tanggal', 'jam_masuk', 'jam_pulang'], 'safe'],
            [['hari', 'kode_status_hadir'], 'string', 'max' => 255],
            [['id_jam_kerja'], 'exist', 'skipOnError' => true, 'targetClass' => JamKerja::class, 'targetAttribute' => ['id_jam_kerja' => 'id_jam_kerja']],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_absensi' => 'Id Absensi',
            'id_karyawan' => 'Id Karyawan',
            'id_jam_kerja' => 'Id Jam Kerja',
            'tanggal' => 'Tanggal',
            'hari' => 'Hari',
            'jam_masuk' => 'Jam Masuk',
            'jam_pulang' => 'Jam Pulang',
            'kode_status_hadir' => 'Kode Status Hadir',
        ];
    }

    /**
     * Gets query for [[JamKerja]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJamKerja()
    {
        return $this->hasOne(JamKerja::class, ['id_jam_kerja' => 'id_jam_kerja']);
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
