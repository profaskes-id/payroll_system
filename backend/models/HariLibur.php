<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hari_libur".
 *
 * @property int $id_hari_libur
 * @property string $tanggal
 * @property string $nama_hari_libur
 */
class HariLibur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hari_libur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tanggal', 'nama_hari_libur'], 'required'],
            [['tanggal'], 'safe'],
            [['nama_hari_libur'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_hari_libur' => 'Id Hari Libur',
            'tanggal' => 'Tanggal',
            'nama_hari_libur' => 'Nama Hari Libur',
        ];
    }
}
