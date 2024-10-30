<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TransaksiGaji;

/**
 * TransaksiGajiSearch represents the model behind the search form of `backend\models\TransaksiGaji`.
 */
class TransaksiGajiSearch extends TransaksiGaji
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_transaksi_gaji', 'jam_kerja', 'periode_gaji_bulan', 'periode_gaji_tahun', 'jumlah_hari_kerja', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_wfh', 'jumlah_cuti'], 'integer'],
            [['nomer_identitas', 'nama', 'bagian', 'jabatan', 'status_karyawan', 'jumlah_jam_lembur'], 'safe'],
            [['gaji_pokok', 'lembur_perjam', 'total_lembur', 'jumlah_tunjangan', 'jumlah_potongan', 'potongan_wfh_hari', 'jumlah_potongan_wfh', 'gaji_diterima'], 'number'],
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
        $query = TransaksiGaji::find();

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
            'id_transaksi_gaji' => $this->id_transaksi_gaji,
            'jam_kerja' => $this->jam_kerja,
            'periode_gaji_bulan' => $this->periode_gaji_bulan,
            'periode_gaji_tahun' => $this->periode_gaji_tahun,
            'jumlah_hari_kerja' => $this->jumlah_hari_kerja,
            'jumlah_hadir' => $this->jumlah_hadir,
            'jumlah_sakit' => $this->jumlah_sakit,
            'jumlah_wfh' => $this->jumlah_wfh,
            'jumlah_cuti' => $this->jumlah_cuti,
            'gaji_pokok' => $this->gaji_pokok,
            'jumlah_jam_lembur' => $this->jumlah_jam_lembur,
            'lembur_perjam' => $this->lembur_perjam,
            'total_lembur' => $this->total_lembur,
            'jumlah_tunjangan' => $this->jumlah_tunjangan,
            'jumlah_potongan' => $this->jumlah_potongan,
            'potongan_wfh_hari' => $this->potongan_wfh_hari,
            'jumlah_potongan_wfh' => $this->jumlah_potongan_wfh,
            'gaji_diterima' => $this->gaji_diterima,
        ]);

        $query->andFilterWhere(['like', 'nomer_identitas', $this->nomer_identitas])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'bagian', $this->bagian])
            ->andFilterWhere(['like', 'jabatan', $this->jabatan])
            ->andFilterWhere(['like', 'status_karyawan', $this->status_karyawan]);

        return $dataProvider;
    }
}
