<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "jam_kerja_karyawan".
 *
 * @property int $id_jam_kerja_karyawan
 * @property int $id_karyawan
 * @property int $id_jam_kerja
 * @property int $jenis_shift
 *
 * @property JamKerja $jamKerja
 * @property Karyawan $karyawan
 */
class JamKerjaKaryawan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jam_kerja_karyawan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'id_jam_kerja',], 'required'],
            [['id_karyawan', 'id_jam_kerja', 'id_shift_kerja'], 'integer'],
            [['max_terlambat'], 'safe'],
            [['id_jam_kerja'], 'exist', 'skipOnError' => true, 'targetClass' => JamKerja::class, 'targetAttribute' => ['id_jam_kerja' => 'id_jam_kerja']],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['id_shift_kerja'], 'exist', 'skipOnError' => true, 'targetClass' => ShiftKerja::class, 'targetAttribute' => ['id_shift_kerja' => 'id_shift_kerja']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_jam_kerja_karyawan' => 'Id Jam Kerja Karyawan',
            'id_karyawan' => 'Id Karyawan',
            'id_jam_kerja' => 'Id Jam Kerja',
            // 'jenis_shift' => 'Jenis Shift',
            'max_terlambat' => 'Max Terlambat',
            'id_shift_kerja' => 'Id Shift Kerja',
        ];
    }

    /**
     * Gets query for [[JamKerja]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJamKerja()
    {
        return $this->hasOne(JamKerja::class, ['id_jam_kerja' => 'id_jam_kerja']);
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

    public function shiftKerja($id)
    {
        $data = ShiftKerja::find()->asArray()->where(['id_shift_kerja' => $id])->one();
        return $data;
    }
}
