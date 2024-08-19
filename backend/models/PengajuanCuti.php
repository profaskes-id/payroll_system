<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pengajuan_cuti".
 *
 * @property int $id_pengajuan_cuti
 * @property int $id_karyawan
 * @property string $tanggal_pengajuan
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string|null $alasan_cuti
 * @property int|null $status
 * @property string|null $catatan_admin
 *
 * @property Karyawan $karyawan
 */
class PengajuanCuti extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengajuan_cuti';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'tanggal_pengajuan', 'tanggal_mulai', 'tanggal_selesai'], 'required'],
            [['id_karyawan', 'status'], 'integer'],
            [['tanggal_pengajuan', 'tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['alasan_cuti', 'catatan_admin'], 'string'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pengajuan_cuti' => 'Id Pengajuan Cuti',
            'id_karyawan' => 'Id Karyawan',
            'tanggal_pengajuan' => 'Tanggal Pengajuan',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'alasan_cuti' => 'Alasan Cuti',
            'status' => 'Status',
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
}
