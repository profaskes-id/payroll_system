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
 * @property string $mulai_istirahat
 * @property string $berakhir_istirahat
 * @property int $jumlah_jam
 * @property int $is_24jam
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
    public $shift_sehari;
    public function rules()
    {
        return [
            [['id_jam_kerja'], 'required'],
            [['id_jam_kerja', 'id_shift_kerja', 'is_24jam'], 'integer'],
            [['jam_masuk', 'jam_keluar', 'mulai_istirahat', 'berakhir_istirahat', 'shift_sehari'], 'safe'],
            [['jumlah_jam'], 'number'],
            [['nama_hari'], 'string', 'max' => 255],
            [['id_jam_kerja'], 'exist', 'skipOnError' => true, 'targetClass' => JamKerja::class, 'targetAttribute' => ['id_jam_kerja' => 'id_jam_kerja']],
            [['id_shift_kerja'], 'exist', 'skipOnError' => true, 'targetClass' => ShiftKerja::class, 'targetAttribute' => ['id_shift_kerja' => 'id_shift_kerja']],
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
            'mulai_istirahat' => 'Mulai Istirahat',
            'berakhir_istirahat' => 'Berakhir Istirahat',
            'jumlah_jam' => 'Jumlah Jam',
            'id_shift_kerja' => 'Id Shift Kerja',
            'is_24jam' => 'Is 24jam',
        ];
    }


    public function getJamKerja()
    {
        return $this->hasOne(JamKerja::class, ['id_jam_kerja' => 'id_jam_kerja']);
    }


    public function getShiftKerja()
    {
        return $this->hasOne(ShiftKerja::class, ['id_shift_kerja' => 'id_shift_kerja']);
    }

    public function getNamaHari($params)
    {
        $hari = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];
        return $hari[$params];
    }
}
