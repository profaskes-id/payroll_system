<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pendapatan_potongan_lainnya".
 *
 * @property int $id_ppl
 * @property int $id_karyawan
 * @property string|null $keterangan
 * @property int|null $is_pendapatan
 * @property int|null $is_potongan
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int $jumlah
 * @property int|null $bulan
 * @property int|null $tahun
 *
 * @property Karyawan $karyawan
 */
class PendapatanPotonganLainnya extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pendapatan_potongan_lainnya';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keterangan', 'created_at', 'updated_at', 'bulan', 'tahun'], 'default', 'value' => null],
            [['jumlah'], 'default', 'value' => 0],
            [['id_karyawan'], 'required'],
            [['id_karyawan', 'is_pendapatan', 'is_potongan', 'created_at', 'updated_at', 'jumlah', 'bulan', 'tahun'], 'integer'],
            [['keterangan'], 'string'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_ppl' => 'Id Ppl',
            'id_karyawan' => 'Id Karyawan',
            'keterangan' => 'Keterangan',
            'is_pendapatan' => 'Is Pendapatan',
            'is_potongan' => 'Is Potongan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'jumlah' => 'Jumlah',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
        ];
    }

    /**
     * Gets query for [[Karyawan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKaryawan()
    {
        return $this->hasOne(Karyawan::class, ['id_karyawan' => 'id_karyawan']);
    }

}
