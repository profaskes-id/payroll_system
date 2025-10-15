<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dinas_detail_gaji".
 *
 * @property int $id_dinas_detail
 * @property int $id_karyawan
 * @property int|null $bulan
 * @property int|null $tahun
 * @property string $nama
 * @property string $tanggal
 * @property string|null $keterangan
 * @property float $biaya
 */
class DinasDetailGaji extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dinas_detail_gaji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan', 'tahun', 'keterangan'], 'default', 'value' => null],
            [['biaya'], 'default', 'value' => 0.00],
            [['id_karyawan', 'nama', 'tanggal'], 'required'],
            [['id_karyawan', 'bulan', 'tahun'], 'integer'],
            [['tanggal'], 'safe'],
            [['keterangan'], 'string'],
            [['biaya'], 'number'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_dinas_detail' => 'Id Dinas Detail',
            'id_karyawan' => 'Id Karyawan',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'nama' => 'Nama',
            'tanggal' => 'Tanggal',
            'keterangan' => 'Keterangan',
            'biaya' => 'Biaya',
        ];
    }

}
