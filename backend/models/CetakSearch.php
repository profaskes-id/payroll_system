<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Cetak;

/**
 * CetakSearch represents the model behind the search form of `backend\models\Cetak`.
 */
class CetakSearch extends Cetak
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cetak', 'id_karyawan', 'id_data_pekerjaan', 'status'], 'integer'],
            [['nomor_surat', 'nama_penanda_tangan', 'jabatan_penanda_tangan', 'deskripsi_perusahaan'], 'safe'],
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
        $query = Cetak::find();

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
            'id_cetak' => $this->id_cetak,
            'id_karyawan' => $this->id_karyawan,
            'id_data_pekerjaan' => $this->id_data_pekerjaan,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'nomor_surat', $this->nomor_surat])
            ->andFilterWhere(['like', 'nama_penanda_tangan', $this->nama_penanda_tangan])
            ->andFilterWhere(['like', 'jabatan_penanda_tangan', $this->jabatan_penanda_tangan])
            ->andFilterWhere(['like', 'deskripsi_perusahaan', $this->deskripsi_perusahaan]);

        return $dataProvider;
    }
}
