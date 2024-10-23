<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "master_lokasi".
 *
 * @property int $id_master_lokasi
 * @property string $label
 * @property string $alamat
 * @property float $longtitude
 * @property float $latitude
 *
 * @property Bagian[] $bagians
 */
class MasterLokasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_lokasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['label', 'alamat', 'longtitude', 'latitude'], 'required'],
            [['longtitude', 'latitude'], 'number'],
            [['radius'], 'integer'],
            [['label', 'alamat'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_master_lokasi' => 'Id Master Lokasi',
            'label' => 'Label',
            'alamat' => 'Alamat',
            'longtitude' => 'Longtitude',
            'latitude' => 'Latitude',
            'radius' => 'Radius',
        ];
    }

    /**
     * Gets query for [[Bagians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBagians()
    {
        return $this->hasMany(Bagian::class, ['id_master_lokasi' => 'id_master_lokasi']);
    }
}
