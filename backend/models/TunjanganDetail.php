<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tunjangan_detail".
 *
 * @property int $id_tunjangan_detail
 * @property int $id_tunjangan
 * @property int $id_karyawan
 * @property float $jumlah
 *
 * @property GajiTunjangan[] $gajiTunjangan
 * @property Karyawan $karyawan
 * @property Tunjangan $tunjangan
 */
class TunjanganDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tunjangan_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tunjangan', 'id_karyawan', 'jumlah'], 'required'],
            [['id_tunjangan', 'id_karyawan'], 'integer'],
            [['jumlah'], 'number'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['id_tunjangan'], 'exist', 'skipOnError' => true, 'targetClass' => Tunjangan::class, 'targetAttribute' => ['id_tunjangan' => 'id_tunjangan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tunjangan_detail' => 'Id Tunjangan Detail',
            'id_tunjangan' => 'Id Tunjangan',
            'id_karyawan' => 'Id Karyawan',
            'jumlah' => 'Jumlah',
        ];
    }

    /**
     * Gets query for [[GajiTunjangan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGajiTunjangan()
    {
        return $this->hasMany(GajiTunjangan::class, ['id_tunjangan_detail' => 'id_tunjangan_detail']);
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
     * Gets query for [[Tunjangan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTunjangan()
    {
        return $this->hasOne(Tunjangan::class, ['id_tunjangan' => 'id_tunjangan']);
    }
}
