<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Karyawan;
use yii\data\ArrayDataProvider;
use yii\db\Query;

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

    public function searchAbsensi($params)
    {
        $query = (new Query())
            ->select([
                'k.id_karyawan',
                'k.kode_karyawan',
                'k.nama AS nama_karyawan',
                'dp.id_data_pekerjaan',
                'dp.id_bagian',
                'a.id_absensi',
                'a.tanggal AS tanggal_absensi',
                'a.jam_masuk',
                'a.jam_pulang',
                'a.kode_status_hadir',
                'a.keterangan AS keterangan_absensi',
                'a.lampiran',

            ])
            ->from('{{%karyawan}} k')
            ->leftJoin('{{%data_pekerjaan}} dp', 'k.id_karyawan = dp.id_karyawan')
            ->leftJoin('{{%absensi}} a', 'k.id_karyawan = a.id_karyawan');

        $results = $query->all();
        $result = [];
        foreach ($results as $row) {
            // Initialize karyawan entry if not set
            if (!isset($result[$row['id_karyawan']])) {
                $result[$row['id_karyawan']] = [
                    'karyawan' => [
                        'id_karyawan' => $row['id_karyawan'],
                        'kode_karyawan' => $row['kode_karyawan'],
                        'nama' => $row['nama_karyawan'],
                    ],
                    'data_pekerjaan' => null,
                    'absensi' => []
                ];
            }

            // Add data_pekerjaan
            if ($row['id_data_pekerjaan'] && !$result[$row['id_karyawan']]['data_pekerjaan']) {
                $result[$row['id_karyawan']]['data_pekerjaan'] = [
                    'id_data_pekerjaan' => $row['id_data_pekerjaan'],
                    'id_bagian' => $row['id_bagian'],
                ];
            }

            // Add absensi
            if ($row['id_absensi']) {
                $result[$row['id_karyawan']]['absensi'][] = [
                    'id_absensi' => $row['id_absensi'],
                    'tanggal_absensi' => $row['tanggal_absensi'],
                    'jam_masuk' => $row['jam_masuk'],
                    'jam_pulang' => $row['jam_pulang'],
                    'kode_status_hadir' => $row['kode_status_hadir'],
                    'keterangan_absensi' => $row['keterangan_absensi'],
                    'lampiran' => $row['lampiran'],
                ];
            }
        }

        $dataProvider = new ArrayDataProvider([
            'models' => $result,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }
}
