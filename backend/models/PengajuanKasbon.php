<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pengajuan_kasbon".
 *
 * @property int $id_pengajuan_kasbon
 * @property int $id_karyawan
 * @property float|null $gaji_pokok
 * @property float|null $jumlah_kasbon
 * @property string $tanggal_pengajuan
 * @property string|null $tanggal_pencairan
 * @property int|null $lama_cicilan
 * @property float|null $angsuran_perbulan
 * @property string|null $tanggal_mulai_potong
 * @property string|null $keterangan
 * @property string|null $tanggal_disetujui
 * @property int|null $disetujui_oleh
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 *
 * @property Karyawan $disetujuiOleh
 * @property Karyawan $karyawan
 * @property PembayaranKasbon[] $pembayaranKasbons
 * @property PendingKasbon[] $pendingKasbons
 */
class PengajuanKasbon extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengajuan_kasbon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tanggal_pencairan', 'tanggal_mulai_potong', 'keterangan', 'tanggal_disetujui', 'disetujui_oleh', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'default', 'value' => null],
            [['angsuran_perbulan'], 'default', 'value' => 0.00],
            [['status'], 'default', 'value' => 0],
            [['id_karyawan', 'tanggal_pengajuan'], 'required'],
            [['id_karyawan', 'lama_cicilan', 'disetujui_oleh',  'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['gaji_pokok', 'jumlah_kasbon', 'angsuran_perbulan'], 'number'],
            [['tanggal_pengajuan', 'tanggal_pencairan', 'tanggal_mulai_potong', 'tanggal_disetujui'], 'safe'],
            [['keterangan'], 'string'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pengajuan_kasbon' => 'ID Pengajuan Kasbon',
            'id_karyawan' => 'Karyawan',
            'gaji_pokok' => 'Gaji Pokok',
            'jumlah_kasbon' => 'Jumlah Kasbon',
            'tanggal_pengajuan' => 'Tanggal Pengajuan',
            'tanggal_pencairan' => 'Tanggal Pencairan',
            'lama_cicilan' => 'Lama Cicilan',
            'angsuran_perbulan' => 'Angsuran Perbulan',
            'tanggal_mulai_potong' => 'Tanggal Mulai Potong',
            'keterangan' => 'Keterangan',
            'tanggal_disetujui' => 'Tanggal Disetujui',
            'disetujui_oleh' => 'Disetujui Oleh',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[DisetujuiOleh]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisetujuiOleh()
    {
        return $this->hasOne(Karyawan::class, ['id_karyawan' => 'disetujui_oleh']);
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
     * Gets query for [[PembayaranKasbons]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPembayaranKasbons()
    {
        return $this->hasMany(PembayaranKasbon::class, ['id_kasbon' => 'id_pengajuan_kasbon']);
    }

    /**
     * Gets query for [[PendingKasbons]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPendingKasbons()
    {
        return $this->hasMany(PendingKasbon::class, ['id_kasbon' => 'id_pengajuan_kasbon']);
    }
}
