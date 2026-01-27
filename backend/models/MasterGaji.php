<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "master_gaji".
 *
 * @property int $id_gaji
 * @property int $id_karyawan
 * @property float $nominal_gaji
 *
 * @property Karyawan $karyawan
 */
class MasterGaji extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_gaji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'nominal_gaji'], 'required'],
            [['id_karyawan'], 'integer'],
            [['nominal_gaji', 'visibility'], 'number',],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_gaji' => 'Id Gaji',
            'id_karyawan' => 'Id Karyawan',
            'nominal_gaji' => 'Nominal Gaji',
            'visibility' => 'Visibility',
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
}
