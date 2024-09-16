<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "atasan_karyawan".
 *
 * @property int $id_atasan_karyawan
 * @property int $id_atasan
 * @property int $id_karyawan
 * @property int $status
 * @property int|null $di_setting_oleh
 * @property string|null $di_setting_pada
 *
 * @property Karyawan $atasan
 * @property Karyawan $karyawan
 */
class AtasanKaryawan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'atasan_karyawan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_atasan', 'id_karyawan'], 'required'],
            [['id_atasan', 'id_karyawan', 'status', 'di_setting_oleh'], 'integer'],
            [['di_setting_pada'], 'safe'],
            [['id_atasan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_atasan' => 'id_karyawan']],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_atasan_karyawan' => 'Id Atasan Karyawan',
            'id_atasan' => 'Id Atasan',
            'id_karyawan' => 'Id Karyawan',
            'status' => 'Status',
            'di_setting_oleh' => 'Di Setting Oleh',
            'di_setting_pada' => 'Di Setting Pada',
        ];
    }

    /**
     * Gets query for [[Atasan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAtasan()
    {
        return $this->hasOne(Karyawan::class, ['id_karyawan' => 'id_atasan']);
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
