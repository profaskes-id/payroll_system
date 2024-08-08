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
            [['id_karyawan', 'kode_status_hadir'], 'integer'],
            [['tanggal', 'jam_masuk', 'jam_pulang', 'lampiran', 'keterangan'], 'safe'],
            [['keterangan'], 'string'],
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
