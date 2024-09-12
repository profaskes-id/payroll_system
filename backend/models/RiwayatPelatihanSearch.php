<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RiwayatPelatihan;

/**
 * RiwayatPelatihanSearch represents the model behind the search form of `backend\models\RiwayatPelatihan`.
 */
class RiwayatPelatihanSearch extends RiwayatPelatihan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_riwayat_pelatihan', 'id_karyawan'], 'integer'],
            [['judul_pelatihan', 'tanggal_mulai', 'tanggal_selesai', 'penyelenggara', 'deskripsi', 'sertifikat'], 'safe'],
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
        $query = RiwayatPelatihan::find();

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
            'id_riwayat_pelatihan' => $this->id_riwayat_pelatihan,
            'id_karyawan' => $this->id_karyawan,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
        ]);

        $query->andFilterWhere(['like', 'judul_pelatihan', $this->judul_pelatihan])
            ->andFilterWhere(['like', 'penyelenggara', $this->penyelenggara])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi])
            ->andFilterWhere(['like', 'sertifikat', $this->sertifikat]);

        return $dataProvider;
    }
}
