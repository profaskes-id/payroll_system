<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\GajiPotongan;

/**
 * GajiPotonganSearch represents the model behind the search form of `backend\models\GajiPotongan`.
 */
class GajiPotonganSearch extends GajiPotongan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_gaji_potongan', 'id_potongan_detail', 'id_transaksi_gaji'], 'integer'],
            [['nama_potongan'], 'safe'],
            [['jumlah'], 'number'],
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
        $query = GajiPotongan::find();

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
            'id_gaji_potongan' => $this->id_gaji_potongan,
            'id_potongan_detail' => $this->id_potongan_detail,
            'jumlah' => $this->jumlah,
            'id_transaksi_gaji' => $this->id_transaksi_gaji,
        ]);

        $query->andFilterWhere(['like', 'nama_potongan', $this->nama_potongan]);

        return $dataProvider;
    }
}
