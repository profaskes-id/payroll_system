<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "gaji_potongan".
 *
 * @property int $id_gaji_potongan
 * @property int $id_potongan_detail
 * @property string $nama_potongan
 * @property float $jumlah
 * @property int|null $id_transaksi_gaji
 *
 * @property PotonganDetail $potonganDetail
 * @property TransaksiGaji $transaksiGaji
 */
class GajiPotongan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gaji_potongan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_potongan_detail', 'nama_potongan', 'jumlah'], 'required'],
            [['id_potongan_detail', 'id_transaksi_gaji'], 'integer'],
            [['jumlah'], 'number'],
            [['nama_potongan'], 'string', 'max' => 255],
            [['id_potongan_detail'], 'exist', 'skipOnError' => true, 'targetClass' => PotonganDetail::class, 'targetAttribute' => ['id_potongan_detail' => 'id_potongan_detail']],
            [['id_transaksi_gaji'], 'exist', 'skipOnError' => true, 'targetClass' => TransaksiGaji::class, 'targetAttribute' => ['id_transaksi_gaji' => 'id_transaksi_gaji']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_gaji_potongan' => 'Id Gaji Potongan',
            'id_potongan_detail' => 'Id Potongan Detail',
            'nama_potongan' => 'Nama Potongan',
            'jumlah' => 'Jumlah',
            'id_transaksi_gaji' => 'Id Transaksi Gaji',
        ];
    }

    /**
     * Gets query for [[PotonganDetail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPotonganDetail()
    {
        return $this->hasOne(PotonganDetail::class, ['id_potongan_detail' => 'id_potongan_detail']);
    }

    /**
     * Gets query for [[TransaksiGaji]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransaksiGaji()
    {
        return $this->hasOne(TransaksiGaji::class, ['id_transaksi_gaji' => 'id_transaksi_gaji']);
    }

    public function getSumPotongan($id_karyawan)
    {
        $gajiPotonganDetail = PotonganDetail::find()->where(['id_karyawan' => $id_karyawan])->sum('jumlah');
        return $gajiPotonganDetail;
    }
}
