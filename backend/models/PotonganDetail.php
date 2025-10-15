<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "potongan_detail".
 *
 * @property int $id_potongan_detail
 * @property int $id_potongan
 * @property int $id_karyawan
 * @property float $jumlah
 * @property int|null $status
 *
 * @property Karyawan $karyawan
 * @property Potongan $potongan
 */
class PotonganDetail extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'potongan_detail';
    }

    /**
     * {@inheritdoc}
     */

    public $tipe_jumlah;
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 0],
            [['id_potongan', 'id_karyawan', 'jumlah'], 'required'],
            [['id_potongan', 'id_karyawan', 'status'], 'integer'],
            [['jumlah'], 'number'],
            [['tipe_jumlah'], 'safe'],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['id_potongan'], 'exist', 'skipOnError' => true, 'targetClass' => Potongan::class, 'targetAttribute' => ['id_potongan' => 'id_potongan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_potongan_detail' => 'Id Potongan Detail',
            'id_potongan' => 'Id Potongan',
            'id_karyawan' => 'Id Karyawan',
            'jumlah' => 'Jumlah',
            'status' => 'Status',
            'tiper_jumlah' => 'tipe',
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
     * Gets query for [[Potongan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPotongan()
    {
        return $this->hasOne(Potongan::class, ['id_potongan' => 'id_potongan']);
    }
    public static function getPotonganKaryawan($id_karyawan, $nominalGaji = null)
    {
        if ($nominalGaji === null) {
            $gajiKaryawan = MasterGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
            $nominalGaji = $gajiKaryawan ? $gajiKaryawan->nominal_gaji : 0;
        }

        $potonganData = PotonganDetail::find()
            ->select([
                'potongan_detail.*',
                'potongan.nama_potongan',
                'potongan.satuan',
                'potongan.jumlah as jumlah_standar'
            ])
            ->leftJoin('potongan', 'potongan_detail.id_potongan = potongan.id_potongan')
            ->where([
                'potongan_detail.id_karyawan' => $id_karyawan,
                'potongan_detail.status' => 1
            ])
            ->asArray()
            ->all();

        foreach ($potonganData as &$item) {
            $jumlah = isset($item['jumlah']) ? $item['jumlah'] : $item['jumlah_standar'];
            $item['nominal_final'] = ($item['satuan'] === '%') ? ($jumlah * $nominalGaji) / 100 : $jumlah;
        }

        return $potonganData;
    }
}
