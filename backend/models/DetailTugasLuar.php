<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "detail_tugas_luar".
 *
 * @property int $id_detail
 * @property int $id_tugas_luar
 * @property string $keterangan Contoh: Ke Jakarta untuk xxxxxx, Ke Bandung untuk xxxxx
 * @property string $jam_diajukan
 * @property string|null $jam_check_in
 * @property string|null $longitude
 * @property string|null $latitude
 * @property int $status_check 0=belum, 1=sudah
 * @property int $urutan
 * @property string|null $bukti_foto
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PengajuanTugasLuar $tugasLuar
 */
class DetailTugasLuar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detail_tugas_luar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tugas_luar', 'keterangan', 'jam_diajukan', 'urutan'], 'required'],
            [['id_tugas_luar', 'status_check', 'urutan'], 'integer'],
            [['jam_diajukan', 'jam_check_in', 'created_at', 'updated_at'], 'safe'],
            [['keterangan', 'bukti_foto'], 'string', 'max' => 100],
            [['longitude', 'latitude'], 'string', 'max' => 50],
            [['id_tugas_luar'], 'exist', 'skipOnError' => true, 'targetClass' => PengajuanTugasLuar::class, 'targetAttribute' => ['id_tugas_luar' => 'id_tugas_luar']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detail' => 'Id Detail',
            'id_tugas_luar' => 'Id Tugas Luar',
            'keterangan' => 'Keterangan',
            'jam_diajukan' => 'Jam Diajukan',
            'jam_check_in' => 'Jam Check In',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'status_check' => 'Status Check',
            'urutan' => 'Urutan',
            'bukti_foto' => 'Bukti Foto',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[TugasLuar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTugasLuar()
    {
        return $this->hasOne(PengajuanTugasLuar::class, ['id_tugas_luar' => 'id_tugas_luar']);
    }
}
