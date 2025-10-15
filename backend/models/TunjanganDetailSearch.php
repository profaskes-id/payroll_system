<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TunjanganDetail;

/**
 * TunjanganDetailSearch represents the model behind the search form of `backend\models\TunjanganDetail`.
 */
class TunjanganDetailSearch extends TunjanganDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tunjangan_detail', 'id_tunjangan', 'id_karyawan'], 'integer'],
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
        $query = TunjanganDetail::find()
            ->select(['tunjangan_detail.*',  'karyawan.nama', 'tunjangan.nama_tunjangan',  'tunjangan_detail.id_tunjangan_detail', 'bagian.nama_bagian', 'master_kode.nama_kode'])
            ->leftJoin('karyawan', 'karyawan.id_karyawan = tunjangan_detail.id_karyawan')
            ->leftJoin('tunjangan', 'tunjangan.id_tunjangan = tunjangan_detail.id_tunjangan')
            ->leftJoin('data_pekerjaan', 'data_pekerjaan.id_karyawan = karyawan.id_karyawan AND data_pekerjaan.is_aktif = 1')
            ->leftJoin('bagian', 'bagian.id_bagian = data_pekerjaan.id_bagian ')
            ->leftJoin('master_kode', 'data_pekerjaan.jabatan = master_kode.kode AND master_kode.nama_group = "jabatan"')
            ->asArray();

        // dd($query->all());
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id_tunjangan_detail' => SORT_DESC]],

        ]);

        if (isset($params['id_karyawan']) && $params['id_karyawan'] != '') {
            $query->andWhere(['id_karyawan' => $params['id_karyawan']]);
        }
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_tunjangan_detail' => $this->id_tunjangan_detail,
            'id_tunjangan' => $this->id_tunjangan,
            'id_karyawan' => $this->id_karyawan,
            'jumlah' => $this->jumlah,
        ]);

        return $dataProvider;
    }
}
