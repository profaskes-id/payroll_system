<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\HariLibur;

/**
 * HariLiburSearch represents the model behind the search form of `backend\models\HariLibur`.
 */
class HariLiburSearch extends HariLibur
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_hari_libur'], 'integer'],
            [['tanggal', 'nama_hari_libur'], 'safe'],
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
        $query = HariLibur::find();

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
            'id_hari_libur' => $this->id_hari_libur,
            'tanggal' => $this->tanggal,
        ]);

        $query->andFilterWhere(['like', 'nama_hari_libur', $this->nama_hari_libur]);

        return $dataProvider;
    }
}
