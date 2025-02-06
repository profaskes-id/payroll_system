<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\KategoriExpenses;

/**
 * KategoriExpensesSearch represents the model behind the search form of `backend\models\KategoriExpenses`.
 */
class KategoriExpensesSearch extends KategoriExpenses
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kategori_expenses', 'create_by', 'update_by'], 'integer'],
            [['nama_kategori', 'deskripsi', 'create_at', 'update_at'], 'safe'],
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
        $query = KategoriExpenses::find();

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
            'id_kategori_expenses' => $this->id_kategori_expenses,
            'create_at' => $this->create_at,
            'create_by' => $this->create_by,
            'update_at' => $this->update_at,
            'update_by' => $this->update_by,
        ]);

        $query->andFilterWhere(['like', 'nama_kategori', $this->nama_kategori])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi]);

        return $dataProvider;
    }
}
