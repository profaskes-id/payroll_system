<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "data_keluarga".
 *
 * @property int $id_data_keluarga
 * @property int $id_karyawan
 * @property string $nama_anggota_keluarga
 * @property string $hubungan
 * @property string $pekerjaan
 * @property int $tahun_lahir
 *
 * @property Karyawan $karyawan
 */
class DataKeluarga extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_keluarga';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'nama_anggota_keluarga', 'hubungan', 'pekerjaan', 'tahun_lahir'], 'required'],
            [['id_karyawan', 'tahun_lahir'], 'integer'],
            [['nama_anggota_keluarga', 'hubungan', 'pekerjaan'], 'string', 'max' => 255],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_data_keluarga' => 'Id Data Keluarga',
            'id_karyawan' => 'Id Karyawan',
            'nama_anggota_keluarga' => 'Nama Anggota Keluarga',
            'hubungan' => 'Hubungan',
            'pekerjaan' => 'Pekerjaan',
            'tahun_lahir' => 'Tahun Lahir',
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
