<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PengajuanWfh;

/**
 * PengajuanWfhSearch represents the model behind the search form of `backend\models\PengajuanWfh`.
 */
class PengajuanWfhSearch extends PengajuanWfh
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pengajuan_wfh', 'id_karyawan', 'status'], 'integer'],
            [['alasan', 'lokasi', 'tanggal_array'], 'safe'],
            [['longitude', 'latitude'], 'number'],
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
        $query = PengajuanWfh::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_pengajuan_wfh' => $this->id_pengajuan_wfh,
            'id_karyawan' => $this->id_karyawan,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'alasan', $this->alasan])
            ->andFilterWhere(['like', 'lokasi', $this->lokasi])
            ->andFilterWhere(['like', 'tanggal_array', $this->tanggal_array]);

        return $dataProvider;
    }
}
