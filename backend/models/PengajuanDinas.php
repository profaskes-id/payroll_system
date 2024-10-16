<?php

namespace backend\models;

use amnah\yii2\user\models\User;
use Yii;

/**
 * This is the model class for table "pengajuan_dinas".
 *
 * @property int $id_pengajuan_dinas
 * @property int $id_karyawan
 * @property string $keterangan_perjalanan
 * @property string $tanggal
 * @property float $estimasi_biaya
 * @property float|null $biaya_yang_disetujui
 * @property int|null $disetujui_oleh
 * @property string|null $disetujui_pada
 *
 * @property Karyawan $karyawan
 */
class PengajuanDinas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengajuan_dinas';
    }

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['id_karyawan', 'keterangan_perjalanan', 'tanggal_mulai', 'tanggal_selesai', 'estimasi_biaya', 'status'], 'required'],
            [['id_karyawan', 'disetujui_oleh'], 'integer'],
            [['keterangan_perjalanan', 'files', 'catatan_admin'], 'string'],
            [['tanggal_mulai', 'tanggal_selesai', 'disetujui_pada', "disetujui_oleh"], 'safe'],
            [['estimasi_biaya', 'biaya_yang_disetujui'], 'number'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['files'], 'file', 'extensions' => 'jpg, jpeg, png, pdf', 'maxFiles' => 10],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pengajuan_dinas' => 'Id Pengajuan Dinas',
            'id_karyawan' => 'Id Karyawan',
            'keterangan_perjalanan' => 'Keterangan Perjalanan',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'estimasi_biaya' => 'Estimasi Biaya',
            'biaya_yang_disetujui' => 'Biaya Yang Disetujui',
            'disetujui_oleh' => 'Disetujui Oleh',
            'disetujui_pada' => 'Disetujui Pada',
            'status' => 'status',
            'files' => 'File',
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
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'disetujui_oleh']);
    }
    public function getStatusPengajuan()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'status'])->onCondition(['nama_group' => 'status-pengajuan', 'status' => '1']);
    }
}
