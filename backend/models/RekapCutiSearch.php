<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RekapCuti;

/**
 * RekapCutiSearch represents the model behind the search form of `backend\models\RekapCuti`.
 */
class RekapCutiSearch extends RekapCuti
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rekap_cuti', 'id_master_cuti', 'id_karyawan', 'total_hari_terpakai'], 'integer'],
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
        $query = RekapCuti::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id_rekap_cuti' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_rekap_cuti' => $this->id_rekap_cuti,
            'id_master_cuti' => $this->id_master_cuti,
            'id_karyawan' => $this->id_karyawan,
            'total_hari_terpakai' => $this->total_hari_terpakai,
        ]);

        return $dataProvider;
    }
}
