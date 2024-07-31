<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bagian".
 *
 * @property int $id_bagian
 * @property string $nama_bagian
 *
 * @property DataPekerjaan[] $dataPekerjaans
 */
class Bagian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bagian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_bagian'], 'required'],
            [['nama_bagian'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_bagian' => 'Id Bagian',
            'nama_bagian' => 'Nama Bagian',
        ];
    }

    /**
     * Gets query for [[DataPekerjaans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDataPekerjaans()
    {
        return $this->hasMany(DataPekerjaan::class, ['id_bagian' => 'id_bagian']);
    }
}
