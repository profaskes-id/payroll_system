<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "jadwal_kerja".
 *
 * @property int $id_jadwal_kerja
 * @property int $id_jam_kerja
 * @property string $nama_hari
 * @property string $jam_masuk
 * @property string $jam_keluar
 * @property int $lama_istirahat
 * @property int $jumlah_jam
 *
 * @property JamKerja $jamKerja
 */
class JadwalKerja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jadwal_kerja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jam_kerja', 'nama_hari', 'jam_masuk', 'jam_keluar', 'lama_istirahat', 'jumlah_jam'], 'required'],
            [['id_jam_kerja', 'lama_istirahat', 'jumlah_jam'], 'integer'],
            [['jam_masuk', 'jam_keluar'], 'safe'],
            [['nama_hari'], 'string', 'max' => 255],
            [['id_jam_kerja'], 'exist', 'skipOnError' => true, 'targetClass' => JamKerja::class, 'targetAttribute' => ['id_jam_kerja' => 'id_jam_kerja']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_jadwal_kerja' => 'Id Jadwal Kerja',
            'id_jam_kerja' => 'Id Jam Kerja',
            'nama_hari' => 'Nama Hari',
            'jam_masuk' => 'Jam Masuk',
            'jam_keluar' => 'Jam Keluar',
            'lama_istirahat' => 'Lama Istirahat',
            'jumlah_jam' => 'Jumlah Jam',
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
}
