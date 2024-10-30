<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "potongan".
 *
 * @property int $id_potongan
 * @property string $nama_potongan
 *
 * @property PotonganDetail[] $potonganDetails
 */
class Potongan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'potongan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_potongan'], 'required'],
            [['nama_potongan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_potongan' => 'Id Potongan',
            'nama_potongan' => 'Nama Potongan',
        ];
    }

    /**
     * Gets query for [[PotonganDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPotonganDetails()
    {
        return $this->hasMany(PotonganDetail::class, ['id_potongan' => 'id_potongan']);
    }
}
