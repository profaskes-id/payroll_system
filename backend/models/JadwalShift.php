<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "jadwal_shift".
 *
 * @property int $id_jadwal_shift
 * @property int $id_karyawan
 * @property string $tanggal
 * @property int $id_shift_kerja
 *
 * @property Karyawan $karyawan
 * @property ShiftKerja $shiftKerja
 */
class JadwalShift extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jadwal_shift';
    }

    /**
     * {@inheritdoc}
     */
    public $id;
    public $tanggal_awal;
    public $tanggal_akhir;
    public function rules()
    {
        return [
            [['id_karyawan', 'tanggal', 'id_shift_kerja'], 'required'],
            [['id_karyawan', 'id_shift_kerja'], 'integer'],
            [['tanggal', 'taggal_awal', 'tanggal_akhir', 'id'], 'safe'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['id_shift_kerja'], 'exist', 'skipOnError' => true, 'targetClass' => ShiftKerja::class, 'targetAttribute' => ['id_shift_kerja' => 'id_shift_kerja']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_jadwal_shift' => ' Jadwal Shift',
            'id_karyawan' => 'Karyawan',
            'tanggal' => 'Tanggal',
            'id_shift_kerja' => 'Id Shift Kerja',
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

    /**
     * Gets query for [[ShiftKerja]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShiftKerja()
    {
        return $this->hasOne(ShiftKerja::class, ['id_shift_kerja' => 'id_shift_kerja']);
    }
}
