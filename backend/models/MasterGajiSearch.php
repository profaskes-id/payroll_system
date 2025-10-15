<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MasterGaji;

/**
 * MasterGajiSearch represents the model behind the search form of `backend\models\MasterGaji`.
 */
class MasterGajiSearch extends MasterGaji
{
    /**
     * {@inheritdoc}
     */
    public $jabatan;
    public $nominal_gaji;

    public function rules()
    {
        return [
            [['id_karyawan'], 'integer'],
            [['nama', 'jabatan'], 'safe'],
            [['nominal_gaji'], 'number'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = (new \yii\db\Query())
            ->select([
                'k.id_karyawan',
                'k.nama',
                'k.kode_karyawan',
                'mk.nama_kode AS jabatan',
                'COALESCE(mg.nominal_gaji, 0) AS nominal_gaji',
                'mg.id_gaji AS id_gaji', // Tambahkan ini
            ])
            ->from('karyawan k')
            ->leftJoin('data_pekerjaan dp', 'dp.id_karyawan = k.id_karyawan AND dp.is_aktif = 1')
            ->leftJoin('master_gaji mg', 'mg.id_karyawan = k.id_karyawan')
            ->leftJoin('master_kode mk', 'mk.nama_group = "jabatan" and dp.jabatan = mk.kode')
            ->where(['k.is_aktif' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
