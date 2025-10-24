<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pengajuan_shift".
 *
 * @property int $id_pengajuan_shift
 * @property int $id_karyawan
 * @property int $id_shift_kerja
 * @property string $diajukan_pada
 * @property string $tanggal_awal
 * @property string $tanggal_akhir
 * @property int|null $status
 * @property int|null $ditanggapi_oleh
 * @property string|null $ditanggapi_pada
 * @property string|null $catatan_admin
 *
 * @property Karyawan $karyawan
 * @property ShiftKerja $shiftKerja
 */
class PengajuanShift extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengajuan_shift';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'id_shift_kerja', 'diajukan_pada', 'tanggal_awal', 'tanggal_akhir'], 'required'],
            [['id_karyawan', 'id_shift_kerja', 'status', 'ditanggapi_oleh'], 'integer'],
            [['diajukan_pada', 'tanggal_awal', 'tanggal_akhir', 'ditanggapi_pada'], 'safe'],
            [['catatan_admin'], 'string'],
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
            'id_pengajuan_shift' => 'Pengajuan Shift',
            'id_karyawan' => 'Karyawan',
            'id_shift_kerja' => 'Shift Kerja',
            'diajukan_pada' => 'Diajukan Pada',
            'tanggal_awal' => 'Tanggal Awal',
            'tanggal_akhir' => 'Tanggal Akhir',
            'status' => 'Status',
            'ditanggapi_oleh' => 'Ditanggapi Oleh',
            'ditanggapi_pada' => 'Ditanggapi Pada',
            'catatan_admin' => 'Catatan Admin',
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
