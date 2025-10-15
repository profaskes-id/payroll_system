<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "detail_cuti".
 *
 * @property int $id_detail_cuti
 * @property int $id_pengajuan_cuti
 * @property string $tanggal
 * @property string|null $keterangan
 * @property int|null $status
 *
 * @property PengajuanCuti $pengajuanCuti
 */
class DetailCuti extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detail_cuti';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keterangan'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 0],
            [['id_pengajuan_cuti', 'tanggal'], 'required'],
            [['id_pengajuan_cuti', 'status'], 'integer'],
            [['tanggal'], 'safe'],
            [['keterangan'], 'string'],
            [['id_pengajuan_cuti'], 'exist', 'skipOnError' => true, 'targetClass' => PengajuanCuti::class, 'targetAttribute' => ['id_pengajuan_cuti' => 'id_pengajuan_cuti']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detail_cuti' => 'Id Detail Cuti',
            'id_pengajuan_cuti' => 'Id Pengajuan Cuti',
            'tanggal' => 'Tanggal',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[PengajuanCuti]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengajuanCuti()
    {
        return $this->hasOne(PengajuanCuti::class, ['id_pengajuan_cuti' => 'id_pengajuan_cuti']);
    }

}
