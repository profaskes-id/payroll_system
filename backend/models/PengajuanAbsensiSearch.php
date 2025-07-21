<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PengajuanAbsensi;

/**
 * PengajuanAbsensiSearch represents the model behind the search form of `backend\models\PengajuanAbsensi`.
 */
class PengajuanAbsensiSearch extends PengajuanAbsensi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_karyawan', 'status', 'id_approver'], 'integer'],
            [['tanggal_absen', 'jam_masuk', 'jam_keluar', 'alasan_pengajuan', 'tanggal_pengajuan', 'tanggal_disetujui', 'catatan_approver'], 'safe'],
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
        $query = PengajuanAbsensi::find();

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
            'id' => $this->id,
            'id_karyawan' => $this->id_karyawan,
            'tanggal_absen' => $this->tanggal_absen,
            'jam_masuk' => $this->jam_masuk,
            'jam_keluar' => $this->jam_keluar,
            'status' => $this->status,
            'tanggal_pengajuan' => $this->tanggal_pengajuan,
            'id_approver' => $this->id_approver,
            'tanggal_disetujui' => $this->tanggal_disetujui,
        ]);

        $query->andFilterWhere(['like', 'alasan_pengajuan', $this->alasan_pengajuan])
            ->andFilterWhere(['like', 'catatan_approver', $this->catatan_approver]);

        return $dataProvider;
    }
}
