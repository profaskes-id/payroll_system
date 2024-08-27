<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MasterKode;

/**
 * MasterKodeSearch represents the model behind the search form of `backend\models\MasterKode`.
 */
class MasterKodeSearch extends MasterKode
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_group', 'kode', 'nama_kode'], 'safe'],
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
        $query = MasterKode::find()->orderBy(['nama_group' => SORT_ASC, 'urutan' => SORT_ASC]);

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
        $query->andFilterWhere(['like', 'nama_group', $this->nama_group])
            ->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'nama_kode', $this->nama_kode]);

        return $dataProvider;
    }
}
