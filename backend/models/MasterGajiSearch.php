<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MasterGaji;

/**
 * MasterGajiSearch represents the model behind the search form of `backend\models\MasterGaji`.
 */
class MasterGajiSearch extends MasterGaji
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_gaji', 'id_karyawan'], 'integer'],
            [['nominal_gaji'], 'number'],
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
        $query = MasterGaji::find();

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
            'id_gaji' => $this->id_gaji,
            'id_karyawan' => $this->id_karyawan,
            'nominal_gaji' => $this->nominal_gaji,
        ]);


        return $dataProvider;
    }
}
