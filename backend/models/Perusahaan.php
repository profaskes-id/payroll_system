<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "perusahaan".
 *
 * @property int $id_perusahaan
 * @property string $nama_perusahaan
 * @property int $status_perusahaan
 *
 * @property Bagian[] $bagian
 */
class Perusahaan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'perusahaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_perusahaan', 'status_perusahaan', 'alamat', 'direktur'], 'required'],
            [['status_perusahaan'], 'integer'],
            [['alamat', 'bidang_perusahaan'], 'string'],
            [['nama_perusahaan', 'direktur', 'logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_perusahaan' => 'Id Perusahaan',
            'nama_perusahaan' => 'Nama Perusahaan',
            'status_perusahaan' => 'Status Perusahaan',
            'alamat' => 'Alamat Perusahaan',
            'direktur' => 'Direktur',
            'logo' => 'Logo',
            'bidang_perusahaan' => 'Bidang Usaha',
        ];
    }

    /**
     * Gets query for [[bagian]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBagians()
    {
        return $this->hasMany(Bagian::class, ['id_perusahaan' => 'id_perusahaan']);
    }

    public function getStatusPerusahaan()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'status_perusahaan'])->onCondition(['nama_group' => 'status-perusahaan']);
    }
}
