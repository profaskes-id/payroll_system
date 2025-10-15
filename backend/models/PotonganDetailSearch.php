<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PotonganDetail;

/**
 * PotonganDetailSearch represents the model behind the search form of `backend\models\PotonganDetail`.
 */
class PotonganDetailSearch extends PotonganDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_potongan_detail', 'id_potongan', 'id_karyawan'], 'integer'],
            [['jumlah'], 'number'],
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
        $query = PotonganDetail::find()
            ->select(['potongan_detail.*',  'karyawan.nama', 'potongan.nama_potongan',  'potongan_detail.id_potongan_detail', 'bagian.nama_bagian', 'master_kode.nama_kode'])
            ->leftJoin('karyawan', 'karyawan.id_karyawan = potongan_detail.id_karyawan')
            ->leftJoin('potongan', 'potongan.id_potongan = potongan_detail.id_potongan')
            ->leftJoin('data_pekerjaan', 'data_pekerjaan.id_karyawan = karyawan.id_karyawan AND data_pekerjaan.is_aktif = 1')
            ->leftJoin('bagian', 'bagian.id_bagian = data_pekerjaan.id_bagian ')
            ->leftJoin('master_kode', 'data_pekerjaan.jabatan = master_kode.kode AND master_kode.nama_group = "jabatan"')
            ->asArray();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id_potongan_detail' => SORT_DESC]],

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_potongan_detail' => $this->id_potongan_detail,
            'id_potongan' => $this->id_potongan,
            'id_karyawan' => $this->id_karyawan,
            'jumlah' => $this->jumlah,
        ]);

        return $dataProvider;
    }
}
