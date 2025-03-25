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
            [['id_pengajuan_cuti', 'id_karyawan', 'status', 'jenis_cuti'], 'integer'],
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
    public function search($params, $tgl_mulai, $tgl_selesai)
    {
        $query = PengajuanCuti::find()
            ->where(['>=', 'tanggal_mulai', $tgl_mulai])
            ->andWhere(['<=', 'tanggal_selesai', $tgl_selesai]);

        // Add conditions that should always apply here

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
            // Uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Filter id_karyawan only if it's provided in the params
        if (!empty($this->id_karyawan)) {
            $query->andFilterWhere([
                'id_karyawan' => (int)$this->id_karyawan,  // Pastikan id_karyawan dalam tipe data integer
            ]);
        }

        // Filter status only if it's provided in the params
        if (!empty($this->status)) {
            $query->andFilterWhere([
                'status' => $this->status,
            ]);
        }

        // Filter jenis_cuti only if it's provided in the params
        if (!empty($this->jenis_cuti)) {
            $query->andFilterWhere([
                'jenis_cuti' => $this->jenis_cuti,
            ]);
        }

        // Apply additional text filters if there are values for alasan_cuti and catatan_admin
        if (!empty($this->alasan_cuti)) {
            $query->andFilterWhere(['like', 'alasan_cuti', $this->alasan_cuti]);
        }

        if (!empty($this->catatan_admin)) {
            $query->andFilterWhere(['like', 'catatan_admin', $this->catatan_admin]);
        }

        return $dataProvider;
    }

    public function searchApi($params, $tgl_mulai, $tgl_selesai)
    {
        $query = PengajuanCuti::find()
            ->select(['pengajuan_cuti.*', 'master_cuti.jenis_cuti as jenis_cuti', 'karyawan.nama'])
            ->where(['>=', 'tanggal_mulai', $tgl_mulai])->andWhere(['<=', 'tanggal_selesai', $tgl_selesai])
            ->leftJoin('master_cuti', 'pengajuan_cuti.jenis_cuti = master_cuti.id_master_cuti')
            ->leftJoin('karyawan', 'pengajuan_cuti.id_karyawan = karyawan.id_karyawan')
            ->asArray();


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
            'jenis_cuti' => $this->jenis_cuti,
        ]);

        $query->andFilterWhere(['like', 'alasan_cuti', $this->alasan_cuti])
            ->andFilterWhere(['like', 'catatan_admin', $this->catatan_admin]);

        return $dataProvider;
    }
}
