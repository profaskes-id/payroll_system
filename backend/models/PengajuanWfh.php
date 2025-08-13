<?php

namespace backend\models;

use amnah\yii2\user\models\User;
use Yii;

/**
 * This is the model class for table "pengajuan_wfh".
 *
 * @property int $id_pengajuan_wfh
 * @property int $id_karyawan
 * @property string $alasan
 * @property string|null $lokasi
 * @property float $longitude
 * @property float $latitude
 * @property string|null $tanggal_array
 * @property int $status
 *
 * @property Karyawan $karyawan
 */
class PengajuanWfh extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengajuan_wfh';
    }

    /**
     * {@inheritdoc}
     */
    public $tanggal_mulai;
    public $tanggal_selesai;
    public function rules()
    {
        return [
            [['id_karyawan', 'alasan', 'longitude', 'latitude'], 'required'],
            [['id_karyawan', 'status', 'disetujui_oleh'], 'integer'],
            [['alasan', 'alamat',  'tanggal_array', 'catatan_admin'], 'string'],
            [['longitude', 'latitude'], 'number'],
            [['lokasi'], 'string', 'max' => 255],
            [['tanggal_mulai', 'tanggal_selesai', 'disetujui_oleh', 'disetujui_pada'], 'safe'], // Tambahkan ini
            [['tanggal_mulai'], 'date', 'format' => 'php:Y-m-d'], // Validasi format tanggal
            [['tanggal_selesai'], 'date', 'format' => 'php:Y-m-d'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pengajuan_wfh' => 'Pengajuan Wfh',
            'id_karyawan' => 'Karyawan',
            'alasan' => 'Alasan',
            'lokasi' => 'Lokasi',
            'alamat' => 'Alamat',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'tanggal_array' => 'Tanggal Array',
            'status' => 'Status',
            'tanggal_mulai' => 'Mulai',
            'tanggal_selesai' => 'Selesai',
            'disetujui_oleh' => 'Ditanggapi Oleh',
            'disetujui_pada' => 'Ditanggapi Pada',
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

    public function getStatusPengajuan()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'status'])->onCondition(['nama_group' => 'status-pengajuan', 'status' => '1']);
    }


    public static function getKaryawanData()
    {
        return Karyawan::find()->select(['id_karyawan', 'nama',])->where(['is_aktif' => 1])->asArray()->all();
    }
        public function getDisetujuiOleh()
    {
        return $this->hasOne(User::class, ['id' => 'disetujui_oleh']);
    }
}
