<?php

namespace backend\models;

use amnah\yii2\user\models\User;
use Yii;


/**
 * This is the model class for table "izin_pulang_cepat".
 *
 * @property int $id_izin_pulang_cepat
 * @property int $id_karyawan
 * @property string $alasan
 * @property string $tanggal
 * @property int $status
 * @property string|null $disetujui_pada
 * @property int|null $disetujui_oleh
 * @property string|null $catatan_admin
 *
 * @property Karyawan $karyawan
 */
class IzinPulangCepat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'izin_pulang_cepat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'alasan'], 'required'],
            [['id_karyawan', 'status', 'disetujui_oleh'], 'integer'],
            [['alasan', 'catatan_admin'], 'string'],
            [['tanggal', 'disetujui_pada'], 'safe'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_izin_pulang_cepat' => 'Izin Pulang Cepat',
            'id_karyawan' => 'Karyawan',
            'alasan' => 'Alasan',
            'tanggal' => 'Tanggal',
            'status' => 'Status',
            'disetujui_pada' => 'Ditanggapi Pada',
            'disetujui_oleh' => 'Ditanggapi Oleh',
            'catatan_admin' => 'Catatan Admin',
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
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'disetujui_oleh']);
    }
}
