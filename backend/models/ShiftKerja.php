<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "shift_kerja".
 *
 * @property int $id_shift_kerja
 * @property string $jam_masuk
 * @property string $jam_keluar
 * @property string|null $mulai_istirahat
 * @property string|null $berakhir_istirahat
 * @property float|null $jumlah_jam
 *
 * @property JadwalKerja[] $jadwalKerjas
 */
class ShiftKerja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shift_kerja';
    }

    public function rules()
    {
        return [
            [['nama_shift', 'jam_masuk', 'jam_keluar'], 'required'],
            [['jam_masuk', 'jam_keluar', 'mulai_istirahat', 'berakhir_istirahat'], 'safe'],
            [['jumlah_jam'], 'number'],
            [['nama_shift'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_shift_kerja' => 'Id Shift Kerja',
            'nama_shift' => 'Nama Shift',
            'jam_masuk' => 'Jam Masuk',
            'jam_keluar' => 'Jam Keluar',
            'mulai_istirahat' => 'Mulai Istirahat',
            'berakhir_istirahat' => 'Berakhir Istirahat',
            'jumlah_jam' => 'Jumlah Jam',
        ];
    }



    /**
     * Gets query for [[JadwalKerjas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJadwalKerjas()
    {
        return $this->hasMany(JadwalKerja::class, ['id_shift_kerja' => 'id_shift_kerja']);
    }

    static function getShiftKerjaAll()
    {
        $data =  ShiftKerja::find()->asArray()->all();

        $dataReturn = [];
        $dataReturn[] = ['id_shift_kerja' => null, 'tampilan' => 'Non Shift'];
        foreach ($data as $key => $value) {
            $dataReturn[] = [
                'id_shift_kerja' => $value['id_shift_kerja'],
                'tampilan' => strtoupper($value['nama_shift']) . "({$value['jam_masuk']} - {$value['jam_keluar']})",
            ];
        }

        return $dataReturn;
    }
    static function getShiftKerjaById($param = null)
    {
        $data =  ShiftKerja::find()->asArray()->where(['id_shift_kerja' => $param])->one();
        return $data;
    }
}
