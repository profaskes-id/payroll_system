<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "data_pekerjaan".
 *
 * @property int $id_data_pekerjaan
 * @property int $id_karyawan
 * @property int $id_bagian
 * @property string $dari
 * @property string|null $sampai
 * @property int $status
 * @property string $jabatan
 * @property int $is_aktif
 * @property string|null $surat_lamaran_pekerjaan
 *
 * @property Bagian $bagian
 * @property Karyawan $karyawan
 */
class DataPekerjaan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_pekerjaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'id_bagian', 'dari', 'status', 'jabatan', 'is_aktif'], 'required'],
            [['id_karyawan', 'id_bagian', 'status', 'is_aktif', 'is_currenty'], 'integer'],
            [['dari', 'sampai'], 'safe'],
            [['gaji_pokok'], 'number'],
            [['jabatan', 'surat_lamaran_pekerjaan', 'terbilang'], 'string', 'max' => 255],
            [['id_bagian'], 'exist', 'skipOnError' => true, 'targetClass' => Bagian::class, 'targetAttribute' => ['id_bagian' => 'id_bagian']],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['surat_lamaran_pekerjaan'], 'file', 'extensions' => 'png, jpg, jpeg, pdf', 'maxSize' => 1024 * 1024 * 2],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_data_pekerjaan' => 'Id Data Pekerjaan',
            'id_karyawan' => 'Id Karyawan',
            'id_bagian' => 'Id Bagian',
            'dari' => 'Dari',
            'sampai' => 'Sampai',
            'status' => 'Status Karyawan',
            'jabatan' => 'Jabatan',
            'is_aktif' => 'Status Jabatan',
            'surat_lamaran_pekerjaan' => 'Surat Lamaran Pekerjaan',
            'is_currenty' => 'Sampai Sekarang',
            'gaji_pokok' => 'Gaji Pokok',
            'terbilang' => 'Terbilang',
        ];
    }

    /**
     * Gets query for [[Bagian]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBagian()
    {
        return $this->hasOne(Bagian::class, ['id_bagian' => 'id_bagian']);
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

    public function getStatusPekerjaan()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'status'])->onCondition(['nama_group' => 'status-pekerjaan']);
    }
    public function getJabatanPekerja()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'jabatan'])->onCondition(['nama_group' => 'jabatan']);
    }
}
