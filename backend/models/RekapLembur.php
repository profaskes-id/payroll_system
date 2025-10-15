<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rekap_lembur".
 *
 * @property int $id_rekap_lembur
 * @property int $id_karyawan
 * @property string $tanggal
 * @property int $jam_total
 *
 * @property Karyawan $karyawan
 */
class RekapLembur extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rekap_lembur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'tanggal', 'jam_total'], 'required'],
            [['id_karyawan', 'jam_total'], 'integer'],
            [['tanggal'], 'safe'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_rekap_lembur' => 'Rekap Lembur',
            'id_karyawan' => 'Karyawan',
            'tanggal' => 'Tanggal',
            'jam_total' => 'Jam Total',
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
