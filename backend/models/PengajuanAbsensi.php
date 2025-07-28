<?php

namespace backend\models;

use amnah\yii2\user\models\User;
use Yii;

/**
 * This is the model class for table "pengajuan_absensi".
 *
 * @property int $id
 * @property int $id_karyawan
 * @property string $tanggal_absen
 * @property string|null $jam_masuk
 * @property string|null $jam_keluar
 * @property string $alasan_pengajuan
 * @property int $status
 * @property string|null $tanggal_pengajuan
 * @property int|null $id_approver
 * @property string|null $tanggal_disetujui
 * @property string|null $catatan_approver
 *
 * @property User $approver
 * @property Karyawan $karyawan
 */
class PengajuanAbsensi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengajuan_absensi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'tanggal_absen', 'alasan_pengajuan'], 'required'],
            [['id_karyawan', 'status', 'id_approver'], 'integer'],
            [['tanggal_absen', 'jam_masuk', 'jam_keluar', 'tanggal_pengajuan', 'tanggal_disetujui'], 'safe'],
            [['alasan_pengajuan', 'catatan_approver'], 'string'],
            [['id_approver'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_approver' => 'id']],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_karyawan' => 'Id Karyawan',
            'tanggal_absen' => 'Tanggal Absen',
            'jam_masuk' => 'Jam Masuk',
            'jam_keluar' => 'Jam Keluar',
            'alasan_pengajuan' => 'Alasan Pengajuan',
            'status' => 'Status',
            'tanggal_pengajuan' => 'Tanggal Pengajuan',
            'id_approver' => 'Id Approver',
            'tanggal_disetujui' => 'Tanggal Disetujui',
            'catatan_approver' => 'Catatan Approver',
        ];
    }

    /**
     * Gets query for [[Approver]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApprover()
    {
        return $this->hasOne(User::class, ['id' => 'id_approver']);
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
