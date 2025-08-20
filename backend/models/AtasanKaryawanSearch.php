<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AtasanKaryawan;

/**
 * AtasanKaryawanSearch represents the model behind the search form of `backend\models\AtasanKaryawan`.
 */
class AtasanKaryawanSearch extends AtasanKaryawan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_atasan_karyawan', 'id_atasan', 'id_karyawan', 'status', 'di_setting_oleh'], 'integer'],
            [['di_setting_pada'], 'safe'],
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
   $query = Karyawan::find()
    ->select([
        'karyawan.id_karyawan',
        'karyawan.nama',
        'atasan_karyawan.id_atasan_karyawan',
        'atasan.nama AS nama_atasan', // Ambil nama dari tabel karyawan atasan
        'mk.nama_kode AS jabatan',
        'ml.nama_lokasi AS nama_lokasi',
        'ml.label'
    ])
    ->leftJoin('atasan_karyawan', 'atasan_karyawan.id_karyawan = karyawan.id_karyawan')
    ->leftJoin('karyawan atasan', 'atasan_karyawan.id_atasan = atasan.id_karyawan') // Join ke tabel karyawan lagi untuk dapatkan nama atasan
    ->leftJoin(['dp' => DataPekerjaan::find()->where(['is_aktif' => 1])], 'dp.id_karyawan = karyawan.id_karyawan')
    ->leftJoin('master_kode mk', 'mk.nama_group = "jabatan" and dp.jabatan = mk.kode')
    ->leftJoin('master_lokasi ml', 'ml.id_master_lokasi = atasan_karyawan.id_master_lokasi')
    ->where(['karyawan.is_aktif' => 1])
    ->asArray();



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
            'id_atasan_karyawan' => $this->id_atasan_karyawan,
            'id_atasan' => $this->id_atasan,
            'id_karyawan' => $this->id_karyawan,
            'status' => $this->status,
            'di_setting_oleh' => $this->di_setting_oleh,
            'di_setting_pada' => $this->di_setting_pada,
        ]);

        return $dataProvider;
    }
}
