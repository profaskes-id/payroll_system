<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MasterHaribesar;

/**
 * MasterHaribesarSearch represents the model behind the search form of `backend\models\MasterHaribesar`.
 */
class MasterHaribesarSearch extends MasterHaribesar
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode', 'libur_nasional'], 'integer'],
            [['tanggal', 'nama_hari', 'pesan_default', 'lampiran'], 'safe'],
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
        $query = MasterHaribesar::find();

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
            'kode' => $this->kode,
            'tanggal' => $this->tanggal,
            'libur_nasional' => $this->libur_nasional,
        ]);

        $query->andFilterWhere(['like', 'nama_hari', $this->nama_hari])
            ->andFilterWhere(['like', 'pesan_default', $this->pesan_default])
            ->andFilterWhere(['like', 'lampiran', $this->lampiran]);

        return $dataProvider;
    }
}
