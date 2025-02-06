<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ExpensesHeader;

/**
 * ExpensesHeaderSearch represents the model behind the search form of `backend\models\ExpensesHeader`.
 */
class ExpensesHeaderSearch extends ExpensesHeader
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_expense_header', 'create_by', 'update_by'], 'integer'],
            [['tanggal', 'create_at', 'update_at'], 'safe'],
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
        $query = ExpensesHeader::find();

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
            'id_expense_header' => $this->id_expense_header,
            'tanggal' => $this->tanggal,
            'create_at' => $this->create_at,
            'create_by' => $this->create_by,
            'update_at' => $this->update_at,
            'update_by' => $this->update_by,
        ]);

        return $dataProvider;
    }
}
