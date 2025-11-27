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
            [['tanggal_pengajuan',   'alasan_cuti', 'catatan_admin'], 'safe'],
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
    public function search($params, $tgl_mulai = null, $tgl_selesai = null)
    {
        $query = PengajuanCuti::find()
            ->joinWith('detailCuti'); // join relasi ke tabel detail_cuti

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filter by karyawan, status, jenis, alasan, catatan
        $query->andFilterWhere(['id_karyawan' => $this->id_karyawan])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['jenis_cuti' => $this->jenis_cuti])
            ->andFilterWhere(['like', 'alasan_cuti', $this->alasan_cuti])
            ->andFilterWhere(['like', 'catatan_admin', $this->catatan_admin]);

        // --- Filter tanggal (jika $tgl_mulai & $tgl_selesai diisi)
        if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
            $query->andWhere([
                'between',
                'detail_cuti.tanggal',
                $tgl_mulai,
                $tgl_selesai
            ]);
        }

        // Agar tidak duplikat karena join many-to-many
        $query->groupBy('{{%pengajuan_cuti}}.id_pengajuan_cuti');

        return $dataProvider;
    }


    public function searchApi($params,)
    {
        $query = PengajuanCuti::find()
            ->select(['pengajuan_cuti.*', 'master_cuti.jenis_cuti as jenis_cuti', 'karyawan.nama'])
            // ->where(['>=', 'tanggal_mulai', $tgl_mulai])->andWhere(['<=', 'tanggal_selesai', $tgl_selesai])
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
