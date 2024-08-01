<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Karyawan;

/**
 * KaryawanSearch represents the model behind the search form of `backend\models\Karyawan`.
 */
class KaryawanSearch extends Karyawan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'kode_jenis_kelamin'], 'integer'],
            [['kode_karyawan', 'nama', 'nomer_identitas', 'jenis_identitas', 'tanggal_lahir', 'kode_negara', 'kode_provinsi', 'kode_kabupaten_kota', 'kode_kecamatan', 'alamat', 'email'], 'safe'],
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
        $query = Karyawan::find();

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
            'id_karyawan' => $this->id_karyawan,
            'tanggal_lahir' => $this->tanggal_lahir,
            'kode_jenis_kelamin' => $this->kode_jenis_kelamin,
        ]);

        $query->andFilterWhere(['like', 'kode_karyawan', $this->kode_karyawan])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'nomer_identitas', $this->nomer_identitas])
            ->andFilterWhere(['like', 'jenis_identitas', $this->jenis_identitas])
            ->andFilterWhere(['like', 'kode_negara', $this->kode_negara])
            ->andFilterWhere(['like', 'kode_provinsi', $this->kode_provinsi])
            ->andFilterWhere(['like', 'kode_kabupaten_kota', $this->kode_kabupaten_kota])
            ->andFilterWhere(['like', 'kode_kecamatan', $this->kode_kecamatan])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
