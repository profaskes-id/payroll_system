<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tunjangan".
 *
 * @property int $id_tunjangan
 * @property string $nama_tunjangan
 *
 * @property TunjanganDetail[] $tunjanganDetails
 */
class Tunjangan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tunjangan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_tunjangan'], 'required'],
            [['nama_tunjangan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tunjangan' => 'Id Tunjangan',
            'nama_tunjangan' => 'Nama Tunjangan',
        ];
    }

    /**
     * Gets query for [[TunjanganDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTunjanganDetails()
    {
        return $this->hasMany(TunjanganDetail::class, ['id_tunjangan' => 'id_tunjangan']);
    }
}
