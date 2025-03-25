<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PengajuanWfh;

/**
 * PengajuanWfhSearch represents the model behind the search form of `backend\models\PengajuanWfh`.
 */
class PengajuanWfhSearch extends PengajuanWfh
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pengajuan_wfh', 'id_karyawan', 'status'], 'integer'],
            [['alasan', 'lokasi', 'tanggal_array'], 'safe'],
            [['longitude', 'latitude'], 'number'],
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
        $query = PengajuanWfh::find();
        $result = $query->asArray()->all();

        $filtered_result = array_filter(
            $result,
            function ($data) use ($tgl_mulai, $tgl_selesai) {
                $tanggal = json_decode($data['tanggal_array'], true);
                if (!empty($tanggal)) {
                    $firstDate = reset($tanggal);
                    $lastDate = end($tanggal);
                    return strtotime($firstDate) >= strtotime($tgl_mulai) &&
                        strtotime($lastDate) <= strtotime($tgl_selesai);
                }

                return false; // Jika tanggal_array kosong, kembalikan false
            }
        );




        $filteredIds = array_map(function ($item) {
            return $item['id_pengajuan_wfh'];
        }, array_values($filtered_result));

        // Buat query baru dengan ID yang telah difilter
        $query = PengajuanWfh::find()->where(['in', 'id_pengajuan_wfh', $filteredIds]);

        // Buat DataProvider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status' => SORT_DESC]]
        ]);



        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_pengajuan_wfh' => $this->id_pengajuan_wfh,
            'id_karyawan' => $this->id_karyawan,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'alasan', $this->alasan])
            ->andFilterWhere(['like', 'lokasi', $this->lokasi])
            ->andFilterWhere(['like', 'tanggal_array', $this->tanggal_array]);

        return $dataProvider;
    }



    public function searchApi($params, $tgl_mulai, $tgl_selesai)
    {
        $query = PengajuanWfh::find();
        $result = $query->asArray()->all();

        $filtered_result = array_filter(
            $result,
            function ($data) use ($tgl_mulai, $tgl_selesai) {
                $tanggal = json_decode($data['tanggal_array'], true);
                if (!empty($tanggal)) {
                    $firstDate = reset($tanggal);
                    $lastDate = end($tanggal);
                    return strtotime($firstDate) >= strtotime($tgl_mulai) &&
                        strtotime($lastDate) <= strtotime($tgl_selesai);
                }

                return false; // Jika tanggal_array kosong, kembalikan false
            }
        );




        $filteredIds = array_map(function ($item) {
            return $item['id_pengajuan_wfh'];
        }, array_values($filtered_result));

        // Buat query baru dengan ID yang telah difilter
        $query = PengajuanWfh::find()->select(['pengajuan_wfh.*', 'karyawan.nama'])->where(['in', 'id_pengajuan_wfh', $filteredIds])->leftJoin('karyawan', 'pengajuan_wfh.id_karyawan = karyawan.id_karyawan')->asArray();

        // Buat DataProvider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status' => SORT_DESC]]
        ]);



        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'pengajuan_wfh.id_pengajuan_wfh' => $this->id_pengajuan_wfh,
            'pengajuan_wfh.id_karyawan' => $this->id_karyawan,
            'pengajuan_wfh.longitude' => $this->longitude,
            'pengajuan_wfh.latitude' => $this->latitude,
            'pengajuan_wfh.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'alasan', $this->alasan])
            ->andFilterWhere(['like', 'lokasi', $this->lokasi])
            ->andFilterWhere(['like', 'tanggal_array', $this->tanggal_array]);

        return $dataProvider;
    }
}
