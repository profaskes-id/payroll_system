<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pending_kasbon".
 *
 * @property int $id_pending_kasbon
 * @property int $id_karyawan
 * @property int $id_kasbon
 * @property int|null $bulan
 * @property int $tahun
 *
 * @property Karyawan $karyawan
 * @property PengajuanKasbon $kasbon
 */
class PendingKasbon extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pending_kasbon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan'], 'default', 'value' => null],
            [['id_karyawan', 'id_kasbon', 'tahun'], 'required'],
            [['id_karyawan', 'id_kasbon', 'bulan', 'tahun'], 'integer'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['id_kasbon'], 'exist', 'skipOnError' => true, 'targetClass' => PengajuanKasbon::class, 'targetAttribute' => ['id_kasbon' => 'id_pengajuan_kasbon']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pending_kasbon' => 'Id Pending Kasbon',
            'id_karyawan' => 'Id Karyawan',
            'id_kasbon' => 'Id Kasbon',
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

    /**
     * Gets query for [[Kasbon]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKasbon()
    {
        return $this->hasOne(PengajuanKasbon::class, ['id_pengajuan_kasbon' => 'id_kasbon']);
    }
}
