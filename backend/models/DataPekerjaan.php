<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "data_pekerjaan".
 *
 * @property int $id_data_pekerjaan
 * @property int $id_karyawan
 * @property int $id_bagian
 * @property string $dari
 * @property string|null $sampai
 * @property string $status
 * @property string $jabatan
 *
 * @property Bagian $bagian
 * @property Karyawan $karyawan
 */
class DataPekerjaan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_pekerjaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'id_bagian', 'dari', 'status', 'jabatan'], 'required'],
            [['id_karyawan', 'id_bagian'], 'integer'],
            [['dari', 'sampai'], 'safe'],
            [['status', 'jabatan'], 'string', 'max' => 255],
            [['id_bagian'], 'exist', 'skipOnError' => true, 'targetClass' => Bagian::class, 'targetAttribute' => ['id_bagian' => 'id_bagian']],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_data_pekerjaan' => 'Id Data Pekerjaan',
            'id_karyawan' => 'Id Karyawan',
            'id_bagian' => 'Id Bagian',
            'dari' => 'Dari',
            'sampai' => 'Sampai',
            'status' => 'Status',
            'jabatan' => 'Jabatan',
        ];
    }

    /**
     * Gets query for [[Bagian]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBagian()
    {
        return $this->hasOne(Bagian::class, ['id_bagian' => 'id_bagian']);
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
