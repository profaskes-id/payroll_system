<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MasterLokasi;

/**
 * MasterLokasiSearch represents the model behind the search form of `backend\models\MasterLokasi`.
 */
class MasterLokasiSearch extends MasterLokasi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_master_lokasi'], 'integer'],
            [['label', 'alamat'], 'safe'],
            [['longtitude', 'latitude'], 'number'],
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
        $query = MasterLokasi::find();

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
            'id_master_lokasi' => $this->id_master_lokasi,
            'longtitude' => $this->longtitude,
            'latitude' => $this->latitude,
        ]);

        $query->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'alamat', $this->alamat]);

        return $dataProvider;
    }
}
