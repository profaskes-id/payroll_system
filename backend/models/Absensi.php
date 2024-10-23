<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "absensi".
 *
 * @property int $id_absensi
 * @property int $id_karyawan
 * @property string $tanggal
 * @property string|null $jam_masuk
 * @property string|null $jam_pulang
 * @property int $kode_status_hadir
 * @property string|null $keterangan
 * @property string|null $lampiran
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $alasan_terlambat
 *
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
            [['id_karyawan', 'tanggal', 'kode_status_hadir'], 'required'],
            [['id_karyawan', 'is_lembur'], 'integer'],
            [['tanggal', 'jam_masuk', 'jam_pulang'], 'safe'],
            [['keterangan', 'alasan_terlambat', 'alasan_terlalu_jauh'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['lampiran'], 'string', 'max' => 255],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['lampiran'], 'file', 'extensions' => 'png, jpg, jpeg, pdf, webp, avif', 'maxSize' => 1024 * 1024 * 2],

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
            'tanggal' => 'Tanggal',
            'jam_masuk' => 'Jam Masuk',
            'jam_pulang' => 'Jam Pulang',
            'kode_status_hadir' => 'Kode Status Hadir',
            'keterangan' => 'Keterangan',
            'lampiran' => 'Lampiran',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'alasan_terlambat' => 'Alasan Terlambat',
            'is_lembur' => 'Apakah Lembur',
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

    public function getStatusHadir()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'kode_status_hadir'])->onCondition(['nama_group' => 'status-hadir']);
    }
}
