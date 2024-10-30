<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "periode_gaji".
 *
 * @property int $bulan
 * @property int $tahun
 * @property string $tanggal_awal
 * @property string $tanggal_akhir
 * @property string|null $terima
 *
 * @property TransaksiGaji[] $transaksiGajis
 */
class PeriodeGaji extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periode_gaji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan', 'tahun', 'tanggal_awal', 'tanggal_akhir'], 'required'],
            [['bulan', 'tahun'], 'integer'],
            [['tanggal_awal', 'tanggal_akhir', 'terima'], 'safe'],
            [['bulan', 'tahun'], 'unique', 'targetAttribute' => ['bulan', 'tahun']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'tanggal_awal' => 'Tanggal Awal',
            'tanggal_akhir' => 'Tanggal Akhir',
            'terima' => 'Terima',
        ];
    }

    /**
     * Gets query for [[TransaksiGajis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransaksiGajis()
    {
        return $this->hasMany(TransaksiGaji::class, ['periode_gaji_bulan' => 'bulan', 'periode_gaji_tahun' => 'tahun']);
    }
}
