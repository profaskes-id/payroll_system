<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pengalaman_kerja".
 *
 * @property int $id_pengalaman_kerja
 * @property int $id_karyawan
 * @property string $perusahaan
 * @property string $posisi
 * @property string $masuk_pada
 * @property string $keluar_pada
 *
 * @property Karyawan $karyawan
 */
class PengalamanKerja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengalaman_kerja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'perusahaan', 'posisi', 'masuk_pada', 'keluar_pada'], 'required'],
            [['id_karyawan'], 'integer'],
            [['masuk_pada', 'keluar_pada'], 'safe'],
            [['perusahaan', 'posisi'], 'string', 'max' => 255],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pengalaman_kerja' => 'Id Pengalaman Kerja',
            'id_karyawan' => 'Id Karyawan',
            'perusahaan' => 'Perusahaan',
            'posisi' => 'Posisi',
            'masuk_pada' => 'Masuk Pada',
            'keluar_pada' => 'Keluar Pada',
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
