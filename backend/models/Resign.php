<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "resign".
 *
 * @property int $id_resign
 * @property int $id_karyawan
 * @property string|null $tanggal_resign
 * @property string|null $surat_pengunduran_diri
 *
 * @property Karyawan $karyawan
 */
class Resign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan'], 'required'],
            [['id_karyawan'], 'integer'],
            [['tanggal_resign'], 'safe'],
            [['surat_pengunduran_diri'], 'string', 'max' => 255],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_resign' => 'Id Resign',
            'id_karyawan' => 'Id Karyawan',
            'tanggal_resign' => 'Tanggal Resign',
            'surat_pengunduran_diri' => 'Surat Pengunduran Diri',
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
