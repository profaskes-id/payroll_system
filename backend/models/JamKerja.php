<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "jam_kerja".
 *
 * @property int $id_jam_kerja
 * @property string $nama_jam_kerja
 * @property int $jenis_shift
 *
 * @property Absensi[] $absensis
 * @property JadwalKerja[] $jadwalKerjas
 * @property JamKerjaKaryawan[] $jamKerjaKaryawans
 */
class JamKerja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jam_kerja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_jam_kerja', 'jenis_shift'], 'required'],
            [['jenis_shift', 'jumlah_hari'], 'integer'],
            [['nama_jam_kerja'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_jam_kerja' => 'Id Jam Kerja',
            'nama_jam_kerja' => 'Nama Jam Kerja',
            'jenis_shift' => 'Jenis Shift',
            'jumlah_hari' => 'jumlah hari',
        ];
    }

    /**
     * Gets query for [[Absensis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbsensis()
    {
        return $this->hasMany(Absensi::class, ['id_jam_kerja' => 'id_jam_kerja']);
    }

    /**
     * Gets query for [[JadwalKerjas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJadwalKerjas()
    {
        return $this->hasMany(JadwalKerja::class, ['id_jam_kerja' => 'id_jam_kerja']);
    }

    /**
     * Gets query for [[JamKerjaKaryawans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJamKerjaKaryawans()
    {
        return $this->hasMany(JamKerjaKaryawan::class, ['id_jam_kerja' => 'id_jam_kerja']);
    }

    public function getJenisShift()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'jenis_shift'])->onCondition(['nama_group' => 'jenis-shift']);
    }

    public function getTampilanJamKerja()
    {
        $data = JamKerja::find()->all();

        $dataResult = [];

        foreach ($data as $key => $value) {
            $jenisShift = isset($value['jenisShift']) ? $value['jenisShift']['nama_kode'] : null;
            $dataResult[] = [
                'id' => $value['id_jam_kerja'],
                'text' => $value['nama_jam_kerja']  . ' - ' . $jenisShift,
            ];
        }
        return $dataResult;
    }
}
