<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "settingan_umum".
 *
 * @property int $id_settingan_umum
 * @property string $kode_setting
 * @property string $nama_setting
 * @property int|null $nilai_setting
 * @property string|null $ket
 */
class SettinganUmum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settingan_umum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_setting', 'nama_setting'], 'required'],
            [['nilai_setting'], 'integer'],
            [['kode_setting', 'nama_setting'], 'string', 'max' => 255],
            [['ket'] , 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_settingan_umum' => 'Id Settingan Umum',
            'kode_setting' => 'Kode Setting',
            'nama_setting' => 'Nama Setting',
            'nilai_setting' => 'Nilai Setting',
            'ket' => 'Ket',
        ];
    }
}
