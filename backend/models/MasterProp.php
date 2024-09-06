<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "master_prop".
 *
 * @property string $kode_prop
 * @property string $nama_prop
 */
class MasterProp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_prop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_prop', 'nama_prop'], 'required'],
            [['kode_prop'], 'string', 'max' => 2],
            [['nama_prop'], 'string', 'max' => 40],
            [['kode_prop'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kode_prop' => 'Kode Prop',
            'nama_prop' => 'Nama Prop',
        ];
    }
}
