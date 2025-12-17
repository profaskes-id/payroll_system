<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pembayaran_kasbon".
 *
 * @property int $id_pembayaran_kasbon
 * @property int $id_karyawan
 * @property int $id_kasbon
 * @property int|null $id_periode_gaji
 * @property float|null $jumlah_potong
 * @property string $tanggal_potong
 * @property float|null $angsuran
 * @property int|null $status_potongan 0 = belum lunas, 1 = lunas
 * @property float|null $sisa_kasbon
 * @property int|null $created_at
 *
 * @property Karyawan $karyawan
 * @property PengajuanKasbon $kasbon
 * @property PeriodeGaji $periodeGaji
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
            [['created_at'], 'default', 'value' => null],
            [['sisa_kasbon'], 'default', 'value' => 0.00],
            [['status_potongan'], 'default', 'value' => 0],
            [['autodebt'], 'default', 'value' => 1],
            [['id_karyawan', 'id_kasbon', 'tanggal_potong', 'bulan', 'tahun'], 'required'],
            [['id_karyawan', 'id_kasbon',  'status_potongan', 'created_at'], 'integer'],
            [['jumlah_potong', 'angsuran',], 'number'],
            [['tanggal_potong', 'deskripsi'], 'safe'],
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
            'id_karyawan' => ' Karyawan',
            'id_kasbon' => ' Kasbon',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'jumlah_potong' => 'Jumlah Potong',
            'tanggal_potong' => 'Tanggal Potong',
            'angsuran' => 'Angsuran',
            'status_potongan' => 'Status Potongan',
            'sisa_kasbon' => 'Sisa Kasbon',
            'created_at' => 'Created At',
            'autodebt' => 'Autodebt',
            'deskripsi' => 'Deskripsi',
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

    /**
     * Gets query for [[PeriodeGaji]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodeGaji()
    {
        return $this->hasOne(PeriodeGaji::class, ['id_periode_gaji' => 'id_periode_gaji']);
    }
}
