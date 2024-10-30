<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "potongan_detail".
 *
 * @property int $id_potongan_detail
 * @property int $id_potongan
 * @property int $id_karyawan
 * @property float $jumlah
 *
 * @property GajiPotongan[] $gajiPotongans
 * @property Karyawan $karyawan
 * @property Potongan $potongan
 */
class PotonganDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'potongan_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_potongan', 'id_karyawan', 'jumlah'], 'required'],
            [['id_potongan', 'id_karyawan'], 'integer'],
            [['jumlah'], 'number'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['id_potongan'], 'exist', 'skipOnError' => true, 'targetClass' => Potongan::class, 'targetAttribute' => ['id_potongan' => 'id_potongan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_potongan_detail' => 'Id Potongan Detail',
            'id_potongan' => 'Id Potongan',
            'id_karyawan' => 'Id Karyawan',
            'jumlah' => 'Jumlah',
        ];
    }

    /**
     * Gets query for [[GajiPotongans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGajiPotongans()
    {
        return $this->hasMany(GajiPotongan::class, ['id_potongan_detail' => 'id_potongan_detail']);
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
     * Gets query for [[Potongan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPotongan()
    {
        return $this->hasOne(Potongan::class, ['id_potongan' => 'id_potongan']);
    }
}
