<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DataPekerjaan;

/**
 * DataPekerjaanSearch represents the model behind the search form of `backend\models\DataPekerjaan`.
 */
class DataPekerjaanSearch extends DataPekerjaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_data_pekerjaan', 'id_karyawan', 'id_bagian'], 'integer'],
            [['dari', 'sampai', 'status', 'jabatan'], 'safe'],
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
        $query = DataPekerjaan::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id_data_pekerjaan' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_data_pekerjaan' => $this->id_data_pekerjaan,
            'id_karyawan' => $this->id_karyawan,
            'id_bagian' => $this->id_bagian,
            'dari' => $this->dari,
            'sampai' => $this->sampai,
            'is_aktif' => $this->is_aktif,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'jabatan', $this->jabatan]);

        return $dataProvider;
    }
}
