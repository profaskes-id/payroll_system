<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\JamKerja;

/**
 * JamKerjaSearch represents the model behind the search form of `backend\models\JamKerja`.
 */
class JamKerjaSearch extends JamKerja
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jam_kerja'], 'integer'],
            [['nama_jam_kerja'], 'safe'],
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
        $query = JamKerja::find();

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
            'id_jam_kerja' => $this->id_jam_kerja,
        ]);

        $query->andFilterWhere(['like', 'nama_jam_kerja', $this->nama_jam_kerja]);

        return $dataProvider;
    }
}
