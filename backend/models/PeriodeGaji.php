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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_periode_gaji' => 'Id Periode Gaji',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'tanggal_awal' => 'Tanggal Awal',
            'tanggal_akhir' => 'Tanggal Akhir',
            'terima' => 'Terima',
        ];
    }
}
