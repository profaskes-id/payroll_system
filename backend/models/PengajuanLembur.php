<?php

namespace backend\models;

use amnah\yii2\user\models\User;
use Yii;

/**
 * This is the model class for table "pengajuan_lembur".
 *
 * @property int $id_pengajuan_lembur
 * @property int $id_karyawan
 * @property string $pekerjaan
 * @property int $status
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property string $tanggal
 * @property int|null $disetujui_oleh
 *
 * @property Karyawan $karyawan
 */
class PengajuanLembur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengajuan_lembur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'pekerjaan', 'status', 'jam_mulai', 'jam_selesai', 'tanggal'], 'required'],
            [['id_karyawan', 'status', 'disetujui_oleh'], 'integer'],
            [['pekerjaan'], 'string'],
            [['jam_mulai', 'jam_selesai', 'tanggal', 'disetujui_pada'], 'safe'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pengajuan_lembur' => 'Id Pengajuan Lembur',
            'id_karyawan' => 'Id Karyawan',
            'pekerjaan' => 'Pekerjaan',
            'status' => 'Status',
            'jam_mulai' => 'Jam Mulai',
            'jam_selesai' => 'Jam Selesai',
            'tanggal' => 'Tanggal',
            'disetujui_oleh' => 'Disetujui Oleh',
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

    public function getStatusPengajuan()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'status'])->onCondition(['nama_group' => 'status-pengajuan', 'status' => '1']);
    }

    public function getDisetujuiOleh()
    {
        return $this->hasOne(User::class, ['id' => 'disetujui_oleh']);
    }
}
