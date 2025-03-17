<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "karyawan".
 *
 * @property int $id_karyawan
 * @property string $kode_karyawan
 * @property string $nama
 * @property string $nomer_identitas
 * @property int $jenis_identitas
 * @property int $kode_jenis_kelamin
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property int $status_nikah
 * @property string $agama
 * @property string|null $suku
 * @property string $email
 * @property string $nomer_telepon
 * @property string|null $foto
 * @property string|null $ktp
 * @property string|null $cv
 * @property string|null $ijazah_terakhir
 * @property string $kode_negara
 * @property string $kode_provinsi_identitas
 * @property string $kode_kabupaten_kota_identitas
 * @property string $kode_kecamatan_identitas
 * @property string $desa_lurah_identitas
 * @property string $alamat_identitas
 * @property string|null $rt_identitas
 * @property string|null $rw_identitas
 * @property string|null $kode_post_identitas
 * @property int $is_current_domisili
 * @property string $kode_provinsi_domisili
 * @property string $kode_kabupaten_kota_domisili
 * @property string $kode_kecamatan_domisili
 * @property string $desa_lurah_domisili
 * @property string $alamat_domisili
 * @property string|null $rt_domisili
 * @property string|null $rw_domisili
 * @property string|null $kode_post_domisili
 * @property string $informasi_lain
 * @property int $is_invite
 * @property string|null $invite_at
 *
 * @property Absensi[] $absensis
 * @property DataKeluarga[] $dataKeluargas
 * @property DataPekerjaan[] $dataPekerjaans
 * @property JamKerjaKaryawan[] $jamKerjaKaryawans
 * @property PengajuanCuti[] $pengajuanCutis
 * @property PengajuanDinas[] $pengajuanDinas
 * @property PengajuanLembur[] $pengajuanLemburs
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
            [['kode_karyawan', 'nama', 'nomer_identitas', 'jenis_identitas', 'kode_jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'status_nikah', 'agama', 'email', 'nomer_telepon', 'kode_negara', 'kode_provinsi_identitas', 'kode_kabupaten_kota_identitas', 'kode_kecamatan_identitas', 'desa_lurah_identitas', 'alamat_identitas'], 'required'],
            [['jenis_identitas', 'status_nikah', 'is_current_domisili',  'agama', 'is_invite', 'is_aktif', 'is_atasan'], 'integer'],
            [['tanggal_lahir', 'kode_provinsi_domisili', 'kode_kabupaten_kota_domisili', 'kode_kecamatan_domisili', 'desa_lurah_domisili', 'invite_at', 'tanggal_resign'], 'safe'],
            [['alamat_identitas', 'alamat_domisili', 'informasi_lain', 'surat_pengunduran_diri'], 'string'],
            [['kode_karyawan', 'nama', 'nomer_identitas', 'tempat_lahir', 'agama', 'suku', 'email', 'nomer_telepon', 'foto', 'ktp', 'cv', 'ijazah_terakhir', 'kode_negara', 'kode_provinsi_identitas', 'kode_kabupaten_kota_identitas', 'kode_kecamatan_identitas', 'desa_lurah_identitas', 'rt_identitas', 'rw_identitas', 'kode_post_identitas', 'kode_provinsi_domisili', 'kode_kabupaten_kota_domisili', 'kode_kecamatan_domisili', 'desa_lurah_domisili', 'rt_domisili', 'rw_domisili', 'kode_post_domisili'], 'string', 'max' => 255],
            [['kode_karyawan'], 'unique'],
            [['nomer_identitas'], 'unique'],
            [['email'], 'unique'],
            [['ktp', 'cv', 'foto', 'ijazah_terakhir'], 'file', 'extensions' => 'png, jpg, jpeg, pdf', 'maxSize' => 1024 * 1024 * 2],
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
            'kode_jenis_kelamin' => 'Kode Jenis Kelamin',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'status_nikah' => 'Status Nikah',
            'agama' => 'Agama',
            'suku' => 'Suku',
            'email' => 'Email',
            'nomer_telepon' => 'Nomer Telepon',
            'foto' => 'Foto',
            'ktp' => 'Ktp',
            'cv' => 'Cv',
            'ijazah_terakhir' => 'Ijazah Terakhir',
            'kode_negara' => 'Kode Negara',
            'kode_provinsi_identitas' => 'Kode Provinsi Identitas',
            'kode_kabupaten_kota_identitas' => 'Kode Kabupaten Kota Identitas',
            'kode_kecamatan_identitas' => 'Kode Kecamatan Identitas',
            'desa_lurah_identitas' => 'Desa Lurah Identitas',
            'alamat_identitas' => 'Alamat Identitas',
            'rt_identitas' => 'Rt Identitas',
            'rw_identitas' => 'Rw Identitas',
            'kode_post_identitas' => 'Kode Post Identitas',
            'is_current_domisili' => 'Alamat Domisili Sama Dengan Alamat Identitas',
            'kode_provinsi_domisili' => 'Kode Provinsi Domisili',
            'kode_kabupaten_kota_domisili' => 'Kode Kabupaten Kota Domisili',
            'kode_kecamatan_domisili' => 'Kode Kecamatan Domisili',
            'desa_lurah_domisili' => 'Desa Lurah Domisili',
            'alamat_domisili' => 'Alamat Domisili',
            'rt_domisili' => 'Rt Domisili',
            'rw_domisili' => 'Rw Domisili',
            'kode_post_domisili' => 'Kode Post Domisili',
            'informasi_lain' => 'Informasi Lain',
            'is_invite' => 'Is Invite',
            'invite_at' => 'Invite At',
            'is_aktif' => 'Apakah Aktif',
            'tanggal_resign' => 'Tanggal Resign',
            'surat_pengunduran_diri' => 'Surat Pengunduran Diri',
            'is_atasan' => 'Apakah Juga Atasan Karyawan',
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
     * Gets query for [[JamKerjaKaryawans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJamKerjaKaryawans()
    {
        return $this->hasMany(JamKerjaKaryawan::class, ['id_karyawan' => 'id_karyawan']);
    }

    /**
     * Gets query for [[PengajuanCutis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengajuanCutis()
    {
        return $this->hasMany(PengajuanCuti::class, ['id_karyawan' => 'id_karyawan']);
    }

    /**
     * Gets query for [[PengajuanDinas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengajuanDinas()
    {
        return $this->hasMany(PengajuanDinas::class, ['id_karyawan' => 'id_karyawan']);
    }

    /**
     * Gets query for [[PengajuanLemburs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengajuanLemburs()
    {
        return $this->hasMany(PengajuanLembur::class, ['id_karyawan' => 'id_karyawan']);
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

    public function generateAutoCode()
    {
        $ym = date('Y');
        $_left = "EP" . $ym;
        $_first = "0001";
        $_len = strlen($_left);
        $noTransaksi = $_left . $_first;
        $last_kode = $this->find()
            ->where(['left(kode_karyawan,' . $_len . ')' => $_left])
            ->orderBy(['kode_karyawan' => SORT_DESC])
            ->one();


        if ($last_kode != null) {
            $_no = substr($last_kode['kode_karyawan'], $_len);
            $_no++;
            $_no = substr("0000", strlen($_no)) . $_no;
            $noTransaksi = $_left . $_no;
        }

        if ($this->isNewRecord) {
            return $noTransaksi;
        }
    }
    public function getProvinsiidentitas()
    {
        return $this->hasOne(MasterProp::class, ['kode_prop'  => 'kode_provinsi_identitas']);
    }
    public function getProvinsidomisili()
    {
        return $this->hasOne(MasterProp::class, ['kode_prop'  => 'kode_provinsi_domisili']);
    }
    public function getKabupatenidentitas()
    {
        return $this->hasOne(MasterKab::class, ['kode_kab'  => 'kode_kabupaten_kota_identitas']);
    }
    public function getKabupatendomisili()
    {
        return $this->hasOne(MasterKab::class, ['kode_kab'  => 'kode_kabupaten_kota_domisili']);
    }
    public function getKecamatanidentitas()
    {
        return $this->hasOne(MasterKec::class, ['kode_kec'  => 'kode_kecamatan_identitas']);
    }
    public function getKecamatandomisili()
    {
        return $this->hasOne(MasterKec::class, ['kode_kec'  => 'kode_kecamatan_domisili']);
    }

    public function getJenisidentitas()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'jenis_identitas'])->onCondition(['nama_group' => 'jenis-identitas', 'status' => 1]);
    }
    public function getMasterAgama()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'agama'])->onCondition(['nama_group' => 'agama', 'status' => 1]);
    }
    public function getStatusNikah()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'status_nikah'])->onCondition(['nama_group' => 'status-pernikahan', 'status' => 1]);
    }
    public function getJenisShift()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'jenis_shift'])->onCondition(['nama_group' => 'jenis-shift', 'status' => 1]);
    }
    public function getBagian()
    {
        return $this->hasOne(Bagian::class, ['id_bagian' => 'id_bagian']);
    }
}
