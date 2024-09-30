<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Absensi;

/**
 * AbsensiSearch represents the model behind the search form of `backend\models\Absensi`.
 */
class AbsensiSearch extends Absensi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_absensi', 'id_karyawan',], 'integer'],
            [['tanggal', 'lampiran', 'keterangan', 'jam_masuk', 'jam_pulang', 'kode_status_hadir'], 'safe'],
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
    public function search($params, $bulanan = false)
    {
        $query = Absensi::find();

        // Jika ingin mengambil data bulanan, tambahkan filter tanggal
        if ($bulanan) {
            $query->andFilterWhere(['>=', 'tanggal', date('Y-m-d', strtotime('-1 month'))]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tanggal' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_absensi' => $this->id_absensi,
            'id_karyawan' => $this->id_karyawan,
            'tanggal' => $this->tanggal,
            'jam_masuk' => $this->jam_masuk,
            'jam_pulang' => $this->jam_pulang,
            'tanggal' => $this->tanggal,
        ]);

        $query->andFilterWhere(['like', 'kode_status_hadir', $this->kode_status_hadir]);

        return $dataProvider;
    }
}
