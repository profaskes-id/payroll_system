<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RiwayatKesehatan;

/**
 * RiwayatKesehatanSearch represents the model behind the search form of `backend\models\RiwayatKesehatan`.
 */
class RiwayatKesehatanSearch extends RiwayatKesehatan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_riwayat_kesehatan', 'id_karyawan'], 'integer'],
            [['nama_pengecekan', 'keterangan', 'surat_dokter', 'tanggal'], 'safe'],
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
        $query = RiwayatKesehatan::find();

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
            'id_riwayat_kesehatan' => $this->id_riwayat_kesehatan,
            'id_karyawan' => $this->id_karyawan,
            'tanggal' => $this->tanggal,
        ]);

        $query->andFilterWhere(['like', 'nama_pengecekan', $this->nama_pengecekan])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'surat_dokter', $this->surat_dokter]);

        return $dataProvider;
    }
}
