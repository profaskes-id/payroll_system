<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "bagian".
 *
 * @property int $id_bagian
 * @property string $nama_bagian
 * @property int $id_perusahaan
 *
 * @property DataPekerjaan[] $dataPekerjaans
 * @property Perusahaan $perusahaan
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
            [['nama_bagian', 'id_perusahaan'], 'required'],
            [['id_perusahaan'], 'integer'],
            [['nama_bagian'], 'string', 'max' => 255],
            [['id_perusahaan'], 'exist', 'skipOnError' => true, 'targetClass' => Perusahaan::class, 'targetAttribute' => ['id_perusahaan' => 'id_perusahaan']],
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
            'id_perusahaan' => 'Id Perusahaan',
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

    /**
     * Gets query for [[Perusahaan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerusahaan()
    {
        return $this->hasOne(Perusahaan::class, ['id_perusahaan' => 'id_perusahaan']);
    }
}
