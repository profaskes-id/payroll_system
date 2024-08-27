<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RiwayatPendidikan;

/**
 * RiwayatPendidikanSearch represents the model behind the search form of `backend\models\RiwayatPendidikan`.
 */
class RiwayatPendidikanSearch extends RiwayatPendidikan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_riwayat_pendidikan', 'id_karyawan', 'tahun_masuk', 'tahun_keluar'], 'integer'],
            [['jenjang_pendidikan', 'institusi'], 'safe'],
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
        $query = RiwayatPendidikan::find();

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
            'id_riwayat_pendidikan' => $this->id_riwayat_pendidikan,
            'id_karyawan' => $this->id_karyawan,
            'tahun_masuk' => $this->tahun_masuk,
            'tahun_keluar' => $this->tahun_keluar,
        ]);

        $query->andFilterWhere(['like', 'jenjang_pendidikan', $this->jenjang_pendidikan])
            ->andFilterWhere(['like', 'institusi', $this->institusi]);

        return $dataProvider;
    }
}
