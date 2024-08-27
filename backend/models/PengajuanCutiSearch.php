<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PengajuanCuti;

/**
 * PengajuanCutiSearch represents the model behind the search form of `backend\models\PengajuanCuti`.
 */
class PengajuanCutiSearch extends PengajuanCuti
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pengajuan_cuti', 'id_karyawan', 'status'], 'integer'],
            [['tanggal_pengajuan', 'tanggal_mulai', 'tanggal_selesai', 'alasan_cuti', 'catatan_admin'], 'safe'],
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
        $query = PengajuanCuti::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC, // Mengurutkan berdasarkan status secara ascending
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_pengajuan_cuti' => $this->id_pengajuan_cuti,
            'id_karyawan' => $this->id_karyawan,
            'tanggal_pengajuan' => $this->tanggal_pengajuan,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'alasan_cuti', $this->alasan_cuti])
            ->andFilterWhere(['like', 'catatan_admin', $this->catatan_admin]);

        return $dataProvider;
    }
}
