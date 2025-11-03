<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pending_kasbon".
 *
 * @property int $id_pending_kasbon
 * @property int $id_karyawan
 * @property int $id_kasbon
 * @property int $id_periode_gaji
 *
 * @property Karyawan $karyawan
 * @property PengajuanKasbon $kasbon
 * @property PeriodeGaji $periodeGaji
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
            [['id_karyawan', 'id_kasbon', 'id_periode_gaji'], 'required'],
            [['id_karyawan', 'id_kasbon', 'id_periode_gaji'], 'integer'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['id_kasbon'], 'exist', 'skipOnError' => true, 'targetClass' => PengajuanKasbon::class, 'targetAttribute' => ['id_kasbon' => 'id_pengajuan_kasbon']],
            [['id_periode_gaji'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodeGaji::class, 'targetAttribute' => ['id_periode_gaji' => 'id_periode_gaji']],
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
            'id_periode_gaji' => 'Id Periode Gaji',
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

    /**
     * Gets query for [[PeriodeGaji]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodeGaji()
    {
        return $this->hasOne(PeriodeGaji::class, ['id_periode_gaji' => 'id_periode_gaji']);
    }

}
