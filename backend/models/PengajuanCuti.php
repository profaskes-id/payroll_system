<?php

namespace backend\models;

use amnah\yii2\user\models\User;
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
    public $sisa_hari;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'tanggal_pengajuan', 'tanggal_mulai', 'tanggal_selesai', 'jenis_cuti', 'sisa_hari'], 'required'],
            [['id_karyawan', 'status', 'jenis_cuti', 'sisa_hari', 'ditanggapi_oleh'], 'integer'],
            [['tanggal_pengajuan', 'tanggal_mulai', 'tanggal_selesai', 'ditanggapi_pada', 'ditanggapi_oleh'], 'safe'],
            [['alasan_cuti', 'catatan_admin', 'ditanggapi_pada'], 'string'],
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
            'jenis_cuti' => 'Jenis Cuti',
            'catatan_admin' => 'Catatan Admin',
            'sisa_hari' => 'Sisa Hari',
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

    public function getStatusPengajuan()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'status'])->onCondition(['nama_group' => 'status-pengajuan', 'status' => '1']);
    }
    public function getJenisCuti()
    {
        return $this->hasOne(MasterCuti::class, ['id_master_cuti' => 'jenis_cuti'])->onCondition(['status' => '1']);
    }
        public function getDisetujuiOleh()
    {
        return $this->hasOne(User::class, ['id' => 'ditanggapi_oleh']);
    }
}
