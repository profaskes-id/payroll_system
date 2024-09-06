<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MasterCuti;

/**
 * MasterCutiSearch represents the model behind the search form of `backend\models\MasterCuti`.
 */
class MasterCutiSearch extends MasterCuti
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_master_cuti', 'total_hari_pertahun', 'status'], 'integer'],
            [['jenis_cuti', 'deskripsi_singkat'], 'safe'],
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
        $query = MasterCuti::find();

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
            'id_master_cuti' => $this->id_master_cuti,
            'total_hari_pertahun' => $this->total_hari_pertahun,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'jenis_cuti', $this->jenis_cuti])
            ->andFilterWhere(['like', 'deskripsi_singkat', $this->deskripsi_singkat]);

        return $dataProvider;
    }
}
