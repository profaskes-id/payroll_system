<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\JamKerjaKaryawan;

/**
 * JamKerjaKaryawanSearch represents the model behind the search form of `backend\models\JamKerjaKaryawan`.
 */
class JamKerjaKaryawanSearch extends JamKerjaKaryawan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jam_kerja_karyawan', 'id_jam_kerja', 'jenis_shift'], 'integer'],
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
        $query = JamKerjaKaryawan::find();

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
            'id_jam_kerja_karyawan' => $this->id_jam_kerja_karyawan,
            'id_jam_kerja' => $this->id_jam_kerja,
            'jenis_shift' => $this->jenis_shift,
        ]);

        return $dataProvider;
    }
}
