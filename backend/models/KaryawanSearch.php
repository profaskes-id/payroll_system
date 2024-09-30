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

    public $id_karyawan;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_karyawan', 'jenis_identitas', 'kode_jenis_kelamin', 'status_nikah', 'is_current_domisili'], 'integer'],
            [['kode_karyawan', 'nama', 'nomer_identitas', 'tempat_lahir', 'tanggal_lahir', 'agama', 'suku', 'email', 'nomer_telepon', 'foto', 'ktp', 'cv', 'ijazah_terakhir', 'kode_negara', 'kode_provinsi_identitas', 'kode_kabupaten_kota_identitas', 'kode_kecamatan_identitas', 'desa_lurah_identitas', 'alamat_identitas', 'rt_identitas', 'rw_identitas', 'kode_post_identitas', 'kode_provinsi_domisili', 'kode_kabupaten_kota_domisili', 'kode_kecamatan_domisili', 'desa_lurah_domisili', 'alamat_domisili', 'rt_domisili', 'rw_domisili', 'kode_post_domisili', 'informasi_lain'], 'safe'],
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
            'jenis_identitas' => $this->jenis_identitas,
            'kode_jenis_kelamin' => $this->kode_jenis_kelamin,
            'tanggal_lahir' => $this->tanggal_lahir,
            'status_nikah' => $this->status_nikah,
            'is_current_domisili' => $this->is_current_domisili,
        ]);

        $query->andFilterWhere(['like', 'kode_karyawan', $this->kode_karyawan])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'nomer_identitas', $this->nomer_identitas])
            ->andFilterWhere(['like', 'tempat_lahir', $this->tempat_lahir])
            ->andFilterWhere(['like', 'agama', $this->agama])
            ->andFilterWhere(['like', 'suku', $this->suku])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'nomer_telepon', $this->nomer_telepon])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'ktp', $this->ktp])
            ->andFilterWhere(['like', 'cv', $this->cv])
            ->andFilterWhere(['like', 'ijazah_terakhir', $this->ijazah_terakhir])
            ->andFilterWhere(['like', 'kode_negara', $this->kode_negara])
            ->andFilterWhere(['like', 'kode_provinsi_identitas', $this->kode_provinsi_identitas])
            ->andFilterWhere(['like', 'kode_kabupaten_kota_identitas', $this->kode_kabupaten_kota_identitas])
            ->andFilterWhere(['like', 'kode_kecamatan_identitas', $this->kode_kecamatan_identitas])
            ->andFilterWhere(['like', 'desa_lurah_identitas', $this->desa_lurah_identitas])
            ->andFilterWhere(['like', 'alamat_identitas', $this->alamat_identitas])
            ->andFilterWhere(['like', 'rt_identitas', $this->rt_identitas])
            ->andFilterWhere(['like', 'rw_identitas', $this->rw_identitas])
            ->andFilterWhere(['like', 'kode_post_identitas', $this->kode_post_identitas])
            ->andFilterWhere(['like', 'kode_provinsi_domisili', $this->kode_provinsi_domisili])
            ->andFilterWhere(['like', 'kode_kabupaten_kota_domisili', $this->kode_kabupaten_kota_domisili])
            ->andFilterWhere(['like', 'kode_kecamatan_domisili', $this->kode_kecamatan_domisili])
            ->andFilterWhere(['like', 'desa_lurah_domisili', $this->desa_lurah_domisili])
            ->andFilterWhere(['like', 'alamat_domisili', $this->alamat_domisili])
            ->andFilterWhere(['like', 'rt_domisili', $this->rt_domisili])
            ->andFilterWhere(['like', 'rw_domisili', $this->rw_domisili])
            ->andFilterWhere(['like', 'kode_post_domisili', $this->kode_post_domisili])
            ->andFilterWhere(['like', 'informasi_lain', $this->informasi_lain]);

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
                if ($row['tanggal_absensi'] == date('Y-m-d')) {
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
        }

        $dataProvider = new ArrayDataProvider([
            'models' => $result,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }


    public function searchJadwalKerja($params)
    {

        $query = Karyawan::find()
            ->select(['karyawan.id_karyawan', 'karyawan.nama', 'jam_kerja.nama_jam_kerja', 'jam_kerja_karyawan.max_terlambat'])
            ->leftJoin('jam_kerja_karyawan', 'karyawan.id_karyawan = jam_kerja_karyawan.id_karyawan')
            ->leftJoin('jam_kerja', 'jam_kerja_karyawan.id_jam_kerja = jam_kerja.id_jam_kerja')
            ->asArray()
            ->orderBy(['karyawan.nama' => SORT_ASC])
            ->all();

        $dataProvider = new ArrayDataProvider([
            'models' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }

    public function  searchJadwalKerjaID($params)
    {

        $query = Karyawan::find()
            ->select(['karyawan.id_karyawan', 'karyawan.nama', 'jam_kerja.nama_jam_kerja', 'jam_kerja_karyawan.max_terlambat'])
            ->leftJoin('jam_kerja_karyawan', 'karyawan.id_karyawan = jam_kerja_karyawan.id_karyawan')
            ->leftJoin('jam_kerja', 'jam_kerja_karyawan.id_jam_kerja = jam_kerja.id_jam_kerja')
            ->asArray()
            ->where(['karyawan.id_karyawan' => $params])
            ->orderBy(['karyawan.nama' => SORT_ASC])
            ->all();

        $dataProvider = new ArrayDataProvider([
            'models' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }
    public function  searchAtasanKaryawan($params)
    {


        $query = (new Query())
            ->select([
                'k.id_karyawan',
                'k.nama',
                'a.id_atasan',
                'a.status',
                'a.id_master_lokasi',
            ])
            ->from('karyawan k')
            ->leftJoin('atasan_karyawan a', 'k.id_karyawan = a.id_karyawan')
            // ->where(['karyawan.id_karyawan' => $params])
            ->all();


        // dd($query);
        $dataProvider = new ArrayDataProvider([
            'models' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $dataProvider;
    }
    public function  searchAtasanKaryawanID($params)
    {


        $query = (new Query())
            ->select([
                'k.id_karyawan',
                'k.nama',
                'a.id_atasan',
                'a.status',
                'a.id_master_lokasi'
            ])
            ->from('karyawan k')
            ->leftJoin('atasan_karyawan a', 'k.id_karyawan = a.id_karyawan')
            ->where(['k.id_karyawan' => $params])
            ->all();



        $dataProvider = new ArrayDataProvider([
            'models' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $dataProvider;
    }
}
