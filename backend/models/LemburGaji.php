<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lembur_gaji".
 *
 * @property int $id_lembur_gaji
 * @property string $nama
 * @property int|null $bulan
 * @property int|null $tahun
 * @property int $id_karyawan
 * @property string $tanggal
 * @property int $hitungan_jam
 */
class LemburGaji extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lembur_gaji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan', 'tahun'], 'default', 'value' => null],
            [['nama', 'id_karyawan', 'tanggal', 'hitungan_jam'], 'required'],
            [['bulan', 'tahun', 'id_karyawan'], 'integer'],
            [['tanggal'], 'safe'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_lembur_gaji' => 'Id Lembur Gaji',
            'nama' => 'Nama',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'id_karyawan' => 'Id Karyawan',
            'tanggal' => 'Tanggal',
            'hitungan_jam' => 'Hitungan Jam',
        ];
    }
}
