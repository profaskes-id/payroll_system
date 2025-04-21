<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SettinganUmum;

/**
 * SettinganUmumSearch represents the model behind the search form of `backend\models\SettinganUmum`.
 */
class SettinganUmumSearch extends SettinganUmum
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_settingan_umum', 'nilai_setting'], 'integer'],
            [['kode_setting', 'nama_setting', 'ket'], 'safe'],
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
        $query = SettinganUmum::find();

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
            'id_settingan_umum' => $this->id_settingan_umum,
            'nilai_setting' => $this->nilai_setting,
        ]);

        $query->andFilterWhere(['like', 'kode_setting', $this->kode_setting])
            ->andFilterWhere(['like', 'nama_setting', $this->nama_setting])
            ->andFilterWhere(['like', 'ket', $this->ket]);

        return $dataProvider;
    }
}
