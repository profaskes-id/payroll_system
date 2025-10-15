<?php

namespace backend\models;

use amnah\yii2\user\models\User;
use Yii;

/**
 * This is the model class for table "jatah_cuti_karyawan".
 *
 * @property int $id_jatah_cuti
 * @property int $id_karyawan
 * @property int $id_master_cuti
 * @property int $jatah_hari_cuti
 * @property int $tahun
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 *
 * @property Karyawan $karyawan
 * @property MasterCuti $masterCuti
 */
class JatahCutiKaryawan extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jatah_cuti_karyawan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'default', 'value' => null],
            [['jatah_hari_cuti'], 'default', 'value' => 0],
            [['id_karyawan', 'id_master_cuti', 'tahun'], 'required'],
            [['id_karyawan', 'id_master_cuti', 'jatah_hari_cuti', 'tahun', 'created_by',  'updated_by'], 'integer'],
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
            'id_jatah_cuti' => 'Jatah Cuti',
            'id_karyawan' => 'Karyawan',
            'id_master_cuti' => 'Master Cuti',
            'jatah_hari_cuti' => 'Jatah Hari Cuti',
            'tahun' => 'Tahun',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
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

    public function getCreatedby()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
    public function getUpdatedby()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }
}
