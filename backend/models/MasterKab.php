<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "master_kab".
 *
 * @property string $kode_kab
 * @property string $kode_prop
 * @property string $nama_kab
 */
class MasterKab extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_kab';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_kab', 'kode_prop', 'nama_kab'], 'required'],
            [['kode_kab'], 'string', 'max' => 4],
            [['kode_prop'], 'string', 'max' => 2],
            [['nama_kab'], 'string', 'max' => 41],
            [['kode_kab', 'kode_prop'], 'unique', 'targetAttribute' => ['kode_kab', 'kode_prop']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kode_kab' => 'Kode Kab',
            'kode_prop' => 'Kode Prop',
            'nama_kab' => 'Nama Kab',
        ];
    }

    public function getPropinsi()
    {
        return $this->hasOne(MasterProp::className(), ['kode_prop' => 'kode_prop']);
    }
}
