<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "karyawan".
 *
 * @property int $id_karyawan
 * @property string $kode_karyawan
 * @property string $nama
 * @property string $nomer_identitas
 * @property string $jenis_identitas
 * @property string $tanggal_lahir
 * @property string $kode_negara
 * @property string $kode_provinsi
 * @property string $kode_kabupaten_kota
 * @property string $kode_kecamatan
 * @property string $alamat
 * @property int $kode_jenis_kelamin
 * @property string $email
 *
 * @property Absensi[] $absensis
 * @property DataKeluarga[] $dataKeluargas
 * @property DataPekerjaan[] $dataPekerjaans
 * @property PengalamanKerja[] $pengalamanKerjas
 * @property RiwayatPendidikan[] $riwayatPendidikans
 */
class Karyawan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'karyawan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_karyawan', 'nama', 'nomer_identitas', 'jenis_identitas', 'tanggal_lahir', 'kode_negara', 'kode_provinsi', 'kode_kabupaten_kota', 'kode_kecamatan', 'alamat', 'kode_jenis_kelamin', 'email'], 'required'],
            [['tanggal_lahir'], 'safe'],
            [['alamat'], 'string'],
            [['kode_jenis_kelamin'], 'integer'],
            [['kode_karyawan', 'nama', 'nomer_identitas', 'jenis_identitas', 'kode_provinsi', 'kode_kabupaten_kota', 'kode_kecamatan', 'email'], 'string', 'max' => 255],
            [['kode_negara'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_karyawan' => 'Id Karyawan',
            'kode_karyawan' => 'Kode Karyawan',
            'nama' => 'Nama',
            'nomer_identitas' => 'Nomer Identitas',
            'jenis_identitas' => 'Jenis Identitas',
            'tanggal_lahir' => 'Tanggal Lahir',
            'kode_negara' => 'Kode Negara',
            'kode_provinsi' => 'Kode Provinsi',
            'kode_kabupaten_kota' => 'Kode Kabupaten Kota',
            'kode_kecamatan' => 'Kode Kecamatan',
            'alamat' => 'Alamat',
            'kode_jenis_kelamin' => 'Kode Jenis Kelamin',
            'email' => 'Email',
        ];
    }

    /**
     * Gets query for [[Absensis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbsensis()
    {
        return $this->hasMany(Absensi::class, ['id_karyawan' => 'id_karyawan']);
    }

    /**
     * Gets query for [[DataKeluargas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDataKeluargas()
    {
        return $this->hasMany(DataKeluarga::class, ['id_karyawan' => 'id_karyawan']);
    }

    /**
     * Gets query for [[DataPekerjaans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDataPekerjaans()
    {
        return $this->hasMany(DataPekerjaan::class, ['id_karyawan' => 'id_karyawan']);
    }

    /**
     * Gets query for [[PengalamanKerjas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengalamanKerjas()
    {
        return $this->hasMany(PengalamanKerja::class, ['id_karyawan' => 'id_karyawan']);
    }

    /**
     * Gets query for [[RiwayatPendidikans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRiwayatPendidikans()
    {
        return $this->hasMany(RiwayatPendidikan::class, ['id_karyawan' => 'id_karyawan']);
    }
}
