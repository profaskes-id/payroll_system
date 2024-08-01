<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "master_kode".
 *
 * @property string $nama_group
 * @property string $kode
 * @property string $nama_kode
 */
class MasterKode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_kode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_group', 'kode', 'nama_kode'], 'required'],
            [['nama_group', 'kode', 'nama_kode'], 'string', 'max' => 255],
            [['nama_group', 'kode'], 'unique', 'targetAttribute' => ['nama_group', 'kode']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nama_group' => 'Nama Group',
            'kode' => 'Kode',
            'nama_kode' => 'Nama Kode',
        ];
    }
}
