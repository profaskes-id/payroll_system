<?php

namespace backend\models;

use backend\models\helpers\ManualSHiftHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Karyawan;
use Yii;
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
            [['id_karyawan', 'jenis_identitas', 'kode_jenis_kelamin', 'status_nikah', 'is_current_domisili', 'is_aktif'], 'integer'],
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'nama',
                    'kode_karyawan',
                    'kode_jenis_kelamin',
                    'bagian' => [
                        'asc' => [
                            new \yii\db\Expression('(SELECT GROUP_CONCAT(b.nama_bagian) FROM data_pekerjaan dp JOIN bagian b ON dp.id_bagian = b.id_bagian WHERE dp.id_karyawan = karyawan.id_karyawan AND dp.is_aktif = 1) ASC')
                        ],
                        'desc' => [
                            new \yii\db\Expression('(SELECT GROUP_CONCAT(b.nama_bagian) FROM data_pekerjaan dp JOIN bagian b ON dp.id_bagian = b.id_bagian WHERE dp.id_karyawan = karyawan.id_karyawan AND dp.is_aktif = 1) DESC')
                        ],
                        'label' => 'Bagian',
                        'default' => SORT_ASC
                    ],

                    'tanggal_masuk' => [
                        'asc' => [
                            new \yii\db\Expression('(SELECT MAX(dp.dari) FROM data_pekerjaan dp WHERE dp.id_karyawan = karyawan.id_karyawan AND dp.is_aktif = 1) ASC')
                        ],
                        'desc' => [
                            new \yii\db\Expression('(SELECT MAX(dp.dari) FROM data_pekerjaan dp WHERE dp.id_karyawan = karyawan.id_karyawan AND dp.is_aktif = 1) DESC')
                        ],
                        'label' => 'Tanggal Masuk',
                        'default' => SORT_ASC
                    ],
                    'masa_kerja' => [
                        'asc' => [
                            new \yii\db\Expression('(SELECT MIN(dp.dari) FROM data_pekerjaan dp WHERE dp.id_karyawan = karyawan.id_karyawan AND dp.is_aktif = 1) ASC')
                        ],
                        'desc' => [
                            new \yii\db\Expression('(SELECT MIN(dp.dari) FROM data_pekerjaan dp WHERE dp.id_karyawan = karyawan.id_karyawan AND dp.is_aktif = 1) DESC')
                        ],
                        'label' => 'Masa Kerja',
                        'default' => SORT_ASC
                    ],
                ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->is_aktif !== '' && $this->is_aktif !== null) {
            $query->andWhere(['is_aktif' => $this->is_aktif]);
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


    public function searchAbsensi($params, $tanggalSet)
    {

        $manual_shift = ManualSHiftHelper::isManual();

        // $jenisShift = JamKerja::find()->select(['id_jam_kerja', 'jenis_shift'])->asArray()->all();
        $shifKerja = new ShiftKerja();


        $query = (new Query())
            ->select([
                'k.id_karyawan',
                'k.kode_karyawan',
                'k.nama AS nama_karyawan',
                'MAX(dp.id_data_pekerjaan) AS id_data_pekerjaan',
                'MAX(dp.id_bagian) AS id_bagian',
                'MAX(a.id_absensi) AS id_absensi',
                'a.tanggal AS tanggal_absensi',
                'MAX(a.jam_masuk) AS jam_masuk',
                'MAX(a.jam_pulang) AS jam_pulang',
                'MAX(a.longitude) AS long',
                'MAX(a.latitude) AS lat',
                'MAX(a.kode_status_hadir) AS kode_status_hadir',
                'MAX(a.keterangan) AS keterangan_absensi',
                'MAX(a.lampiran) AS lampiran',
                'MAX(a.is_terlambat) AS is_terlambat',
                'MAX(jk.id_jam_kerja_karyawan) AS id_jam_kerja_karyawan',
                'MAX(jk.id_jam_kerja) AS id_jam_kerja',
                'MAX(jk.max_terlambat) AS max_terlambat',
                'MAX(jk.is_shift) AS is_shift',
                'MAX(j.nama_jam_kerja) AS nama_jam_kerja',
                'MAX(mk.nama_kode) AS jenis_shift',
                'MAX(wj.id_jadwal_kerja) AS id_jadwal_kerja',
                'MAX(wj.nama_hari) AS nama_hari',
                'MAX(wj.jam_masuk) AS jam_masuk_jadwal',
                'MAX(wj.jam_keluar) AS jam_keluar_jadwal',
                'MAX(wj.mulai_istirahat) AS mulai_istirahat',
                'MAX(wj.berakhir_istirahat) AS berakhir_istirahat',
                'MAX(wj.jumlah_jam) AS jumlah_jam',
                'MAX(msl.longtitude) AS penempatan_longtitude',
                'MAX(msl.latitude) AS penempatan_latitude',
            ])
            ->from('{{%karyawan}} k')
            ->where(['k.is_aktif' => 1])
            ->leftJoin('{{%absensi}} a', 'k.id_karyawan = a.id_karyawan AND a.tanggal = :tanggal')
            ->leftJoin('{{%data_pekerjaan}} dp', 'k.id_karyawan = dp.id_karyawan')
            ->leftJoin('{{%jam_kerja_karyawan}} jk', 'k.id_karyawan = jk.id_karyawan')
            ->leftJoin('{{%jam_kerja}} j', 'jk.id_jam_kerja = j.id_jam_kerja')
            ->leftJoin('{{%jadwal_kerja}} wj', 'jk.id_jam_kerja = wj.id_jam_kerja')
            ->leftJoin('{{%atasan_karyawan}} atsk', 'k.id_karyawan = atsk.id_karyawan')
            ->leftJoin('{{%master_lokasi}} msl', 'atsk.id_master_lokasi = msl.id_master_lokasi')
            ->leftJoin('{{%master_kode}} mk', 'j.jenis_shift = mk.kode and mk.nama_group = "jenis-shift"')
            ->groupBy('k.id_karyawan')
            ->addParams([':tanggal' => $tanggalSet]); // Menambahkan parameter tanggal

        $results = $query->all();
        $result = [];

        $currentDate = $tanggalSet; // Gunakan tanggalSet
        // Mendapatkan hari saat ini (1 = Senin, 0 = Minggu, dst.)
        $currentDayOfWeek = date('w'); // 0 (Minggu) sampai 6 (Sabtu)
        $currentDayOfWeek = $currentDayOfWeek == 0 ? 7 : $currentDayOfWeek; // Mengubah Minggu (0) menjadi 7

        foreach ($results as $row) {
            // Inisialisasi entri karyawan
            if (!isset($result[$row['id_karyawan']])) {
                $result[$row['id_karyawan']] = [
                    'karyawan' => [
                        'id_karyawan' => $row['id_karyawan'],
                        'kode_karyawan' => $row['kode_karyawan'],
                        'nama' => $row['nama_karyawan'],
                    ],
                    'data_pekerjaan' => null,
                    'absensi' => [],
                    'jam_kerja' => [],
                    'jadwal_kerja' => []
                ];
            }

            // Tambah data pekerjaan
            if ($row['id_data_pekerjaan'] && !$result[$row['id_karyawan']]['data_pekerjaan']) {
                $result[$row['id_karyawan']]['data_pekerjaan'] = [
                    'id_data_pekerjaan' => $row['id_data_pekerjaan'],
                    'id_bagian' => $row['id_bagian'],
                ];
            }

            // Tambah absensi
            if ($row['id_absensi'] && $row['tanggal_absensi'] == $currentDate) {
                $result[$row['id_karyawan']]['absensi'] = [
                    'id_absensi' => $row['id_absensi'],
                    'tanggal_absensi' => $row['tanggal_absensi'],
                    'jam_masuk' => $row['jam_masuk'],
                    'long' => $row['long'],
                    'lat' => $row['lat'],
                    'penempatan_long' => $row['penempatan_longtitude'],
                    'penempatan_lat' => $row['penempatan_latitude'],
                    'jam_pulang' => $row['jam_pulang'],
                    'kode_status_hadir' => $row['kode_status_hadir'],
                    'is_terlambat' => $row['is_terlambat'],
                ];
            }

            // Hanya proses jam kerja dan jadwal jika manual_shift == 1
            if ($manual_shift == 1) {
                if ($row['id_jam_kerja_karyawan']) {
                    if (!isset($result[$row['id_karyawan']]['jam_kerja']) || empty($result[$row['id_karyawan']]['jam_kerja'])) {
                        $result[$row['id_karyawan']]['jam_kerja'] = [
                            'id_jam_kerja_karyawan' => $row['id_jam_kerja_karyawan'],
                            'id_jam_kerja' => $row['id_jam_kerja'],
                            'max_terlambat' => $row['max_terlambat'],
                            'nama_jam_kerja' => $row['nama_jam_kerja'],
                            'jenis_shift' => $row['jenis_shift'],
                        ];
                    }
                }

                if ($row['jenis_shift'] == null) {
                    continue;
                }

                if (strtolower($row['jenis_shift']) == 'shift') {
                    $dataShif = [
                        'jam_masuk' => "",
                        'jam_keluar' => "",
                        'jumlah_jam' => ""
                    ];

                    if ($row['is_shift'] == 1) {
                        $tanggalHariIni = date('Y-m-d');
                        $jadwalShiftHariIni = JadwalShift::find()
                            ->where(['id_karyawan' => $row['id_karyawan'], 'tanggal' => $tanggalHariIni])
                            ->asArray()
                            ->one();
                        if ($jadwalShiftHariIni) {
                            $shift = $shifKerja->getShiftKerjaById($jadwalShiftHariIni['id_shift_kerja']);
                            if ($shift) {
                                $dataShif = [
                                    'jam_masuk' => $shift['jam_masuk'] ?? "",
                                    'jam_keluar' => $shift['jam_keluar'] ?? "",
                                    'jumlah_jam' => $shift['jumlah_jam'] ?? ""
                                ];
                            } else {
                                Yii::$app->session->setFlash('warning', "Data shift kerja tidak ditemukan untuk ID shift: {$jadwalShiftHariIni['id_shift_kerja']}");
                            }
                        } else {
                            Yii::$app->session->setFlash('warning', "Tidak ada jadwal shift hari ini untuk karyawan {$row['nama_karyawan']}");
                        }
                    } else {
                        Yii::$app->session->setFlash('warning', "Data shift kerja tidak ada pada nama {$row['nama_karyawan']}");
                    }

                    if ($row['id_jadwal_kerja']) {
                        $existingJadwal = $result[$row['id_karyawan']]['jadwal_kerja'] ?? null;
                        if (!$existingJadwal) {
                            $result[$row['id_karyawan']]['jadwal_kerja'] = [
                                'id_jadwal_kerja' => $row['id_jadwal_kerja'],
                                'nama_hari' => $row['nama_hari'],
                                'jam_masuk' => $dataShif['jam_masuk'],
                                'jam_keluar' => $dataShif['jam_keluar'],
                                'jumlah_jam' => $dataShif['jumlah_jam'],
                            ];
                        }
                    }
                } else {
                    if ($row['id_jadwal_kerja']) {
                        $existingJadwal = $result[$row['id_karyawan']]['jadwal_kerja'] ?? null;
                        if (!$existingJadwal) {
                            $result[$row['id_karyawan']]['jadwal_kerja'] = [
                                'id_jadwal_kerja' => $row['id_jadwal_kerja'],
                                'nama_hari' => $row['nama_hari'],
                                'jam_masuk' => $row['jam_masuk_jadwal'],
                                'jam_keluar' => $row['jam_keluar_jadwal'],
                                'jumlah_jam' => $row['jumlah_jam'],
                            ];
                        }
                    }
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
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'jam_kerja_karyawan.id_jam_kerja', // Pilih kolom tertentu dari jam_kerja_karyawan
                'jam_kerja_karyawan.is_shift', // Contoh kolom lain dari jam_kerja_karyawan
                'jam_kerja_karyawan.id_jam_kerja', // Contoh kolom lain dari jam_kerja_karyawan
                'jam_kerja_karyawan.max_terlambat', // Contoh kolom lain dari jam_kerja_karyawan
                'jam_kerja.*',
                'master_kode.nama_kode',
                'data_pekerjaan.jabatan'
            ])
            ->asArray()
            ->leftJoin('jam_kerja_karyawan', 'karyawan.id_karyawan = jam_kerja_karyawan.id_karyawan')
            ->leftJoin('jam_kerja', 'jam_kerja_karyawan.id_jam_kerja = jam_kerja.id_jam_kerja')
            ->leftJoin('data_pekerjaan', 'data_pekerjaan.id_karyawan = karyawan.id_karyawan')
            ->where(['karyawan.is_aktif' => 1]) // Hanya filter untuk karyawan
            // ->andWhere(['data_pekerjaan.is_aktif' => 1])
            ->leftJoin('master_kode', 'master_kode.kode = jam_kerja.jenis_shift AND master_kode.nama_group = "jenis-shift"')
            ->orderBy(['karyawan.nama' => SORT_ASC])
            ->all();


        $dataProvider = new ArrayDataProvider([
            'models' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

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
            ->select(['karyawan.id_karyawan', 'karyawan.nama', 'jam_kerja.nama_jam_kerja', 'master_kode.nama_kode', 'jam_kerja_karyawan.*'])
            ->asArray()
            ->leftJoin('jam_kerja_karyawan', 'karyawan.id_karyawan = jam_kerja_karyawan.id_karyawan')
            ->leftJoin('jam_kerja', 'jam_kerja_karyawan.id_jam_kerja = jam_kerja.id_jam_kerja')
            ->leftJoin('master_kode', 'master_kode.nama_group = "jenis-shift" ')
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

                'a.id_master_lokasi',
            ])
            ->from('karyawan k')
            ->leftJoin('atasan_karyawan a', 'k.id_karyawan = a.id_karyawan')
            ->where(['k.is_aktif' => 1])
            ->all();


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

                'a.id_master_lokasi'
            ])
            ->from('karyawan k')
            ->leftJoin('atasan_karyawan a', 'k.id_karyawan = a.id_karyawan')
            ->where(['k.id_karyawan' => $params, 'k.is_aktif' => 1])
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
