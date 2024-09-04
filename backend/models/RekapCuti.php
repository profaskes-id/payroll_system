<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rekap_cuti".
 *
 * @property int $id_rekap_cuti
 * @property int $id_master_cuti
 * @property int $id_karyawan
 * @property int $total_hari_terpakai
 *
 * @property Karyawan $karyawan
 * @property MasterCuti $masterCuti
 */
class RekapCuti extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rekap_cuti';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_master_cuti', 'id_karyawan', 'total_hari_terpakai'], 'required'],
            [['id_master_cuti', 'id_karyawan', 'total_hari_terpakai'], 'integer'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['id_master_cuti'], 'exist', 'skipOnError' => true, 'targetClass' => MasterCuti::class, 'targetAttribute' => ['id_master_cuti' => 'id_master_cuti']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_rekap_cuti' => 'Id Rekap Cuti',
            'id_master_cuti' => 'Id Master Cuti',
            'id_karyawan' => 'Id Karyawan',
            'total_hari_terpakai' => 'Total Hari Terpakai',
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
     * Gets query for [[MasterCuti]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMasterCuti()
    {
        return $this->hasOne(MasterCuti::class, ['id_master_cuti' => 'id_master_cuti']);
    }
}
