<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IzinPulangCepat;

/**
 * IzinPulangCepatSearch represents the model behind the search form of `backend\models\IzinPulangCepat`.
 */
class IzinPulangCepatSearch extends IzinPulangCepat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_izin_pulang_cepat', 'id_karyawan', 'status'], 'integer'],
            [['alasan'], 'safe'],
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
        $query = IzinPulangCepat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tanggal' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_izin_pulang_cepat' => $this->id_izin_pulang_cepat,
            'id_karyawan' => $this->id_karyawan,
            'status' => $this->status,
            'tanggal' => $this->tanggal,
        ]);

        $query->andFilterWhere(['like', 'alasan', $this->alasan]);

        return $dataProvider;
    }
}
