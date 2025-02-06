<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ShiftKerja;

/**
 * ShiftKerjaSearch represents the model behind the search form of `backend\models\ShiftKerja`.
 */
class ShiftKerjaSearch extends ShiftKerja
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_shift_kerja'], 'integer'],
            [['jam_masuk', 'jam_keluar', 'mulai_istirahat', 'berakhir_istirahat'], 'safe'],
            [['jumlah_jam'], 'number'],
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
        $query = ShiftKerja::find();

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
            'id_shift_kerja' => $this->id_shift_kerja,
            'jam_masuk' => $this->jam_masuk,
            'jam_keluar' => $this->jam_keluar,
            'mulai_istirahat' => $this->mulai_istirahat,
            'berakhir_istirahat' => $this->berakhir_istirahat,
            'jumlah_jam' => $this->jumlah_jam,
        ]);

        return $dataProvider;
    }
}
