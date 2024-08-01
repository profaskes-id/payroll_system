<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DataKeluarga;

/**
 * DataKeluargaSearch represents the model behind the search form of `backend\models\DataKeluarga`.
 */
class DataKeluargaSearch extends DataKeluarga
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_data_keluarga', 'id_karyawan', 'tahun_lahir'], 'integer'],
            [['nama_anggota_keluarga', 'hubungan', 'pekerjaan'], 'safe'],
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
        $query = DataKeluarga::find();

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
            'id_data_keluarga' => $this->id_data_keluarga,
            'id_karyawan' => $this->id_karyawan,
            'tahun_lahir' => $this->tahun_lahir,
        ]);

        $query->andFilterWhere(['like', 'nama_anggota_keluarga', $this->nama_anggota_keluarga])
            ->andFilterWhere(['like', 'hubungan', $this->hubungan])
            ->andFilterWhere(['like', 'pekerjaan', $this->pekerjaan]);

        return $dataProvider;
    }
}
