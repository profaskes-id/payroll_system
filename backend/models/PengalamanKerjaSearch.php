<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PengalamanKerja;

/**
 * PengalamanKerjaSearch represents the model behind the search form of `backend\models\PengalamanKerja`.
 */
class PengalamanKerjaSearch extends PengalamanKerja
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pengalaman_kerja', 'id_karyawan'], 'integer'],
            [['perusahaan', 'posisi', 'masuk_pada', 'keluar_pada'], 'safe'],
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
        $query = PengalamanKerja::find();

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
            'id_pengalaman_kerja' => $this->id_pengalaman_kerja,
            'id_karyawan' => $this->id_karyawan,
            'masuk_pada' => $this->masuk_pada,
            'keluar_pada' => $this->keluar_pada,
        ]);

        $query->andFilterWhere(['like', 'perusahaan', $this->perusahaan])
            ->andFilterWhere(['like', 'posisi', $this->posisi]);

        return $dataProvider;
    }
}
