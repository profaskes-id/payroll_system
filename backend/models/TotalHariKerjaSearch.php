<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TotalHariKerja;

/**
 * TotalHariKerjaSearch represents the model behind the search form of `backend\models\TotalHariKerja`.
 */
class TotalHariKerjaSearch extends TotalHariKerja
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_total_hari_kerja', 'id_jam_kerja', 'total_hari', 'bulan', 'tahun', 'is_aktif'], 'integer'],
            [['keterangan'], 'safe'],
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
        $query = TotalHariKerja::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_total_hari_kerja' => $this->id_total_hari_kerja,
            'id_jam_kerja' => $this->id_jam_kerja,
            'total_hari' => $this->total_hari,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'is_aktif' => $this->is_aktif,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
