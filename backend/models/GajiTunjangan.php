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
 *
 * @property TunjanganDetail $tunjanganDetail
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
            [['id_tunjangan_detail'], 'integer'],
            [['jumlah'], 'number'],
            [['nama_tunjangan'], 'string', 'max' => 255],
            [['id_tunjangan_detail'], 'exist', 'skipOnError' => true, 'targetClass' => TunjanganDetail::class, 'targetAttribute' => ['id_tunjangan_detail' => 'id_tunjangan_detail']],
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
        ];
    }

    /**
     * Gets query for [[TunjanganDetail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTunjanganDetail()
    {
        return $this->hasOne(TunjanganDetail::class, ['id_tunjangan_detail' => 'id_tunjangan_detail']);
    }
}
