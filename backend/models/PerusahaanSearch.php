<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Perusahaan;

/**
 * PerusahaanSearch represents the model behind the search form of `backend\models\Perusahaan`.
 */
class PerusahaanSearch extends Perusahaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_perusahaan', 'status_perusahaan'], 'integer'],
            [['nama_perusahaan'], 'safe'],
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
        $query = Perusahaan::find();

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
            'id_perusahaan' => $this->id_perusahaan,
            'status_perusahaan' => $this->status_perusahaan,
        ]);

        $query->andFilterWhere(['like', 'nama_perusahaan', $this->nama_perusahaan]);

        return $dataProvider;
    }
}
