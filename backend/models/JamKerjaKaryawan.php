<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jam_kerja_karyawan".
 *
 * @property int $id_jam_kerja_karyawan
 * @property int $id_jam_kerja
 * @property int $jenis_shift
 *
 * @property JamKerja $jamKerja
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
            [['id_jam_kerja', 'jenis_shift'], 'required'],
            [['id_jam_kerja', 'jenis_shift'], 'integer'],
            [['id_jam_kerja'], 'exist', 'skipOnError' => true, 'targetClass' => JamKerja::class, 'targetAttribute' => ['id_jam_kerja' => 'id_jam_kerja']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_jam_kerja_karyawan' => 'Id Jam Kerja Karyawan',
            'id_jam_kerja' => 'Id Jam Kerja',
            'jenis_shift' => 'Jenis Shift',
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
}
