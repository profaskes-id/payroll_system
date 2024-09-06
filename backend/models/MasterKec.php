<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "master_kec".
 *
 * @property string $kode_kec
 * @property string $kode_kab
 * @property string $nama_kec
 */
class MasterKec extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_kec';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_kec', 'kode_kab', 'nama_kec'], 'required'],
            [['kode_kec'], 'string', 'max' => 7],
            [['kode_kab'], 'string', 'max' => 4],
            [['nama_kec'], 'string', 'max' => 40],
            [['kode_kec', 'kode_kab'], 'unique', 'targetAttribute' => ['kode_kec', 'kode_kab']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kode_kec' => 'Kode Kec',
            'kode_kab' => 'Kode Kab',
            'nama_kec' => 'Nama Kec',
        ];
    }
    public function getKabupaten()
    {
        return $this->hasOne(MasterKab::className(), ['kode_kab' => 'kode_kab']);
    }
}
