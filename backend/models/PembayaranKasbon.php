<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pembayaran_kasbon".
 *
 * @property int $id_pembayaran_kasbon
 * @property int $id_karyawan
 * @property int $id_kasbon
 * @property int $bulan
 * @property int $tahun
 * @property float|null $jumlah_potong
 * @property float|null $jumlah_kasbon
 * @property string $tanggal_potong
 * @property int|null $status_potongan 0 = belum lunas, 1 = lunas
 * @property float|null $sisa_kasbon
 * @property int|null $created_at
 * @property string|null $deskripsi top-up | Pembayaran Kasbon |  pending-kasbon
 
 * @property string|null $metode_bayar manual | potong-gaji | pending
 
 *
 * @property Karyawan $karyawan
 * @property PengajuanKasbon $kasbon
 */
class PembayaranKasbon extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pembayaran_kasbon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'deskripsi', 'metode_bayar'], 'default', 'value' => null],
            [['sisa_kasbon'], 'default', 'value' => 0.00],
            [['status_potongan'], 'default', 'value' => 0],
            [['id_karyawan', 'id_kasbon', 'bulan', 'tahun', 'tanggal_potong'], 'required'],
            [['id_karyawan', 'id_kasbon', 'bulan', 'tahun', 'status_potongan', 'created_at'], 'integer'],
            [['jumlah_potong', 'jumlah_kasbon',], 'number'],
            [['tanggal_potong'], 'safe'],
            [['deskripsi', 'metode_bayar'], 'string', 'max' => 100],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['id_kasbon'], 'exist', 'skipOnError' => true, 'targetClass' => PengajuanKasbon::class, 'targetAttribute' => ['id_kasbon' => 'id_pengajuan_kasbon']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pembayaran_kasbon' => 'Id Pembayaran Kasbon',
            'id_karyawan' => 'Id Karyawan',
            'id_kasbon' => 'Id Kasbon',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'jumlah_potong' => 'Jumlah Potong',
            'jumlah_kasbon' => 'Jumlah Kasbon',
            'tanggal_potong' => 'Tanggal Potong',
            'status_potongan' => 'Status Potongan',
            'sisa_kasbon' => 'Sisa Kasbon',
            'created_at' => 'Created At',
            'deskripsi' => 'Deskripsi',
            'metode_bayar' => 'Metode Bayar',
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

    /**
     * Gets query for [[Kasbon]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKasbon()
    {
        return $this->hasOne(PengajuanKasbon::class, ['id_pengajuan_kasbon' => 'id_kasbon']);
    }
}
