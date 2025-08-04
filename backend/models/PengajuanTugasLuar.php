<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pengajuan_tugas_luar".
 *
 * @property int $id_tugas_luar
 * @property int $id_karyawan
 * @property int $status_pengajuan 0=pending, 1=disetujui, 2=ditolak
 * @property string $created_at
 * @property string $updated_at
 *
 * @property DetailTugasLuar[] $detailTugasLuars
 * @property Karyawan $karyawan
 */
class PengajuanTugasLuar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengajuan_tugas_luar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan',  'tanggal'], 'required'],
            [['id_karyawan', 'status_pengajuan'], 'integer'],
            [['created_at', 'updated_at', 'catatan_approver'], 'safe'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tugas_luar' => 'Id Tugas Luar',
            'id_karyawan' => 'Id Karyawan',
            'status_pengajuan' => 'Status Pengajuan',
            'tanggal' => 'tanggal',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'catatan_approver' => 'Catatan Approver',
        ];
    }

    /**
     * Gets query for [[DetailTugasLuars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetailTugasLuars()
    {
        return $this->hasMany(DetailTugasLuar::class, ['id_tugas_luar' => 'id_tugas_luar']);
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
