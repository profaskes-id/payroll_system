<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PotonganDetail;

/**
 * PotonganDetailSearch represents the model behind the search form of `backend\models\PotonganDetail`.
 */
class PotonganDetailSearch extends PotonganDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_potongan_detail', 'id_potongan', 'id_karyawan'], 'integer'],
            [['jumlah'], 'number'],
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
        $subQuery = PotonganDetail::find()
            ->select(['id_karyawan', 'SUM(jumlah) as total_potongan'])
            ->groupBy('id_karyawan');

        $query = Karyawan::find()
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                // 'karyawan.nik', // tambahkan field lainnya yang diperlukan
                'COALESCE(p.total_potongan, 0) as total_potongan',
                'bagian.nama_bagian',
                'master_kode.nama_kode'
            ])
            ->leftJoin(['p' => $subQuery], 'p.id_karyawan = karyawan.id_karyawan')
            ->leftJoin('data_pekerjaan', 'data_pekerjaan.id_karyawan = karyawan.id_karyawan AND data_pekerjaan.is_aktif = 1')
            ->leftJoin('bagian', 'bagian.id_bagian = data_pekerjaan.id_bagian')
            ->leftJoin('master_kode', 'data_pekerjaan.jabatan = master_kode.kode AND master_kode.nama_group = "jabatan"')
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
            'sort' => [
                'defaultOrder' => ['id_karyawan' => SORT_DESC],
                'attributes' => [
                    'id_karyawan',
                    'nama',
                    'total_potongan',
                    'nama_bagian',
                    'nama_kode'
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filter conditions
        $query->andFilterWhere([
            'karyawan.id_karyawan' => $this->id_karyawan,
        ]);

        // $query->andFilterWhere(['like', 'karyawan.nama', $this->nama]);

        return $dataProvider;
    }
}
