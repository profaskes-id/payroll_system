<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "gaji_tunjangan".
 *
 * @property int $id_gaji_tunjangan
 * @property int $id_tunjangan_detail
 * @property string $nama_tunjangan
 * @property float $jumlah
 * @property int|null $id_transaksi_gaji
 */
class GajiTunjangan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gaji_tunjangan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tunjangan_detail', 'nama_tunjangan', 'jumlah'], 'required'],
            [['id_tunjangan_detail', 'id_transaksi_gaji'], 'integer'],
            [['jumlah'], 'number'],
            [['nama_tunjangan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_gaji_tunjangan' => 'Id Gaji Tunjangan',
            'id_tunjangan_detail' => 'Id Tunjangan Detail',
            'nama_tunjangan' => 'Nama Tunjangan',
            'jumlah' => 'Jumlah',
            'id_transaksi_gaji' => 'Id Transaksi Gaji',
        ];
    }

    public function getTunjanganDetail()
    {
        return $this->hasOne(TunjanganDetail::class, ['id_tunjangan_detail' => 'id_tunjangan_detail']);
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
}
