<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "master_haribesar".
 *
 * @property int $kode
 * @property string $tanggal
 * @property string $nama_hari
 * @property int|null $libur_nasional
 * @property string|null $pesan_default
 * @property string|null $lampiran
 */
class MasterHaribesar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_haribesar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tanggal', 'nama_hari'], 'required'],
            [['tanggal'], 'safe'],
            [['nama_hari', 'pesan_default', 'lampiran'], 'string'],
            [['libur_nasional'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'tanggal' => 'Tanggal',
            'nama_hari' => 'Nama Hari',
            'libur_nasional' => 'Libur Nasional',
            'pesan_default' => 'Pesan Default',
            'lampiran' => 'Lampiran',
        ];
    }
}
