<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tunjangan_detail".
 *
 * @property int $id_tunjangan_detail
 * @property int $id_tunjangan
 * @property int $id_karyawan
 * @property float $jumlah
 * @property int|null $status
 *
 * @property Karyawan $karyawan
 * @property Tunjangan $tunjangan
 */
class TunjanganDetail extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tunjangan_detail';
    }

    /**
     * {@inheritdoc}
     */

    public $tipe_jumlah;
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 0],
            [['id_tunjangan', 'id_karyawan', 'jumlah'], 'required'],
            [['id_tunjangan', 'id_karyawan', 'status'], 'integer'],
            [['jumlah'], 'number'],
            [['tipe_jumlah'], 'safe'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['id_tunjangan'], 'exist', 'skipOnError' => true, 'targetClass' => Tunjangan::class, 'targetAttribute' => ['id_tunjangan' => 'id_tunjangan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tunjangan_detail' => 'Id Tunjangan Detail',
            'id_tunjangan' => 'Id Tunjangan',
            'id_karyawan' => 'Id Karyawan',
            'jumlah' => 'Jumlah',
            'status' => 'Status',
            'tipe_jumlah' => 'tipe ',
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

    /**
     * Gets query for [[Tunjangan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTunjangan()
    {
        return $this->hasOne(Tunjangan::class, ['id_tunjangan' => 'id_tunjangan']);
    }


    public static function getTunjanganKaryawan($id_karyawan, $nominalGaji = null)
    {
        if ($nominalGaji === null) {
            $gajiKaryawan = MasterGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
            $nominalGaji = $gajiKaryawan ? $gajiKaryawan->nominal_gaji : 0;
        }

        $tunjanganData = TunjanganDetail::find()
            ->select([
                'tunjangan_detail.*',
                'tunjangan.nama_tunjangan',
                'tunjangan.satuan',
                'tunjangan.jumlah as jumlah_standar'
            ])
            ->leftJoin('tunjangan', 'tunjangan_detail.id_tunjangan = tunjangan.id_tunjangan')
            ->where([
                'tunjangan_detail.id_karyawan' => $id_karyawan,
                'tunjangan_detail.status' => 1
            ])
            ->asArray()
            ->all();

        foreach ($tunjanganData as &$item) {
            $jumlah = isset($item['jumlah']) ? $item['jumlah'] : $item['jumlah_standar'];
            if ($item['satuan'] === '%') {
                $item['nominal_final'] = ($jumlah * $nominalGaji) / 100;
            } else {
                $item['nominal_final'] = $jumlah;
            }
        }

        return $tunjanganData;
    }
}
