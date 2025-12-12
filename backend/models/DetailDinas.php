<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "detail_dinas".
 *
 * @property int $id_detail_dinas
 * @property int $id_pengajuan_dinas
 * @property string $tanggal
 * @property string|null $keterangan
 * @property int|null $status
 *
 * @property PengajuanDinas $pengajuanDinas
 */
class DetailDinas extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detail_dinas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keterangan'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 0],
            [['id_pengajuan_dinas', 'tanggal'], 'required'],
            [['id_pengajuan_dinas', 'status'], 'integer'],
            [['tanggal'], 'safe'],
            [['keterangan'], 'string'],
            [['id_pengajuan_dinas'], 'exist', 'skipOnError' => true, 'targetClass' => PengajuanDinas::class, 'targetAttribute' => ['id_pengajuan_dinas' => 'id_pengajuan_dinas']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detail_dinas' => 'Id Detail Dinas',
            'id_pengajuan_dinas' => 'Id Pengajuan Dinas',
            'tanggal' => 'Tanggal',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[PengajuanDinas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengajuanDinas()
    {
        return $this->hasOne(PengajuanDinas::class, ['id_pengajuan_dinas' => 'id_pengajuan_dinas']);
    }

}
