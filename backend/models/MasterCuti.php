<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "master_cuti".
 *
 * @property int $id_master_cuti
 * @property string $jenis_cuti
 * @property string|null $deskripsi_singkat
 * @property int $total_hari_pertahun
 * @property int|null $status
 *
 * @property RekapCuti[] $rekapCutis
 */
class MasterCuti extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_cuti';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenis_cuti', 'total_hari_pertahun'], 'required'],
            [['deskripsi_singkat'], 'string'],
            [['total_hari_pertahun', 'status'], 'integer'],
            [['jenis_cuti'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_master_cuti' => 'Id Master Cuti',
            'jenis_cuti' => 'Jenis Cuti',
            'deskripsi_singkat' => 'Deskripsi Singkat',
            'total_hari_pertahun' => 'Total Hari Pertahun',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[RekapCutis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRekapCutis()
    {
        return $this->hasMany(RekapCuti::class, ['id_master_cuti' => 'id_master_cuti']);
    }
}
