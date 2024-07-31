<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jam_kerja".
 *
 * @property int $id_jam_kerja
 * @property string $nama_jam_kerja
 *
 * @property Absensi[] $absensis
 * @property JadwalKerja[] $jadwalKerjas
 * @property JamKerjaKaryawan[] $jamKerjaKaryawans
 */
class JamKerja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jam_kerja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_jam_kerja'], 'required'],
            [['nama_jam_kerja'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_jam_kerja' => 'Id Jam Kerja',
            'nama_jam_kerja' => 'Nama Jam Kerja',
        ];
    }

    /**
     * Gets query for [[Absensis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbsensis()
    {
        return $this->hasMany(Absensi::class, ['id_jam_kerja' => 'id_jam_kerja']);
    }

    /**
     * Gets query for [[JadwalKerjas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJadwalKerjas()
    {
        return $this->hasMany(JadwalKerja::class, ['id_jam_kerja' => 'id_jam_kerja']);
    }

    /**
     * Gets query for [[JamKerjaKaryawans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJamKerjaKaryawans()
    {
        return $this->hasMany(JamKerjaKaryawan::class, ['id_jam_kerja' => 'id_jam_kerja']);
    }
}
