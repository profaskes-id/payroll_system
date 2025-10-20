<?php

namespace backend\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TransaksiGaji;
use DateTime;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Query;


/**
 * TransaksiGajiSearch represents the model behind the search form of `backend\models\TransaksiGaji`.
 */
class TransaksiGajiSearch extends TransaksiGaji
{

    private $_settingGajiPerJam = null;
    private $_masterWfhPotongan = null;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by'], 'default', 'value' => null],
            [['id_karyawan', 'nama', 'id_bagian', 'nama_bagian', 'jabatan', 'bulan', 'tahun', 'tanggal_awal', 'tanggal_akhir', 'total_absensi', 'terlambat', 'total_alfa_range', 'nominal_gaji', 'gaji_perhari', 'tunjangan_karyawan', 'potongan_karyawan', 'potongan_terlambat', 'potongan_absensi', 'jam_lembur', 'total_pendapatan_lembur', 'dinas_luar_belum_terbayar'], 'required'],
            [['id_karyawan', 'id_bagian', 'bulan', 'tahun', 'total_absensi', 'total_alfa_range', 'jam_lembur', 'created_by', 'updated_by'], 'integer'],
            [['tanggal_awal', 'tanggal_akhir', 'terlambat', 'created_at', 'updated_at'], 'safe'],
            [['nominal_gaji', 'gaji_perhari', 'tunjangan_karyawan', 'potongan_karyawan', 'potongan_terlambat', 'potongan_absensi', 'total_pendapatan_lembur', 'dinas_luar_belum_terbayar'], 'number'],
            [['nama', 'nama_bagian', 'jabatan'], 'string', 'max' => 255],
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

    public function getPerioderGajiSekarang()
    {
        $tanggal_cut_off = MasterKode::findOne(['nama_group' => 'tanggal-cut-of']);
        $tanggal_cut_off_value = (int)$tanggal_cut_off['nama_kode'];

        $sekarang = new DateTime();
        $tanggal_sekarang = (int)$sekarang->format('d');

        if ($tanggal_sekarang < $tanggal_cut_off_value) {
            // Kurangi 1 bulan
            $sekarang->modify('-1 month');
        }

        $hasil = sprintf(
            '%d-%02d-%02d',
            $sekarang->format('Y'),
            $sekarang->format('m'),
            $tanggal_cut_off_value
        );

        $periode_gaji = PeriodeGaji::find()->where(['tanggal_awal' => $hasil])->one();

        // Example assignments (should use '=' instead of '=>')
        $periode_gaji['id_periode_gaji'] = 79;
        $periode_gaji['bulan'] = 1;
        $periode_gaji['tahun'] = 2025;
        $periode_gaji['tanggal_awal'] = "2024-12-20";
        $periode_gaji['tanggal_akhir'] = "2025-01-19";

        return $periode_gaji;
    }

    public function search($params, $id_karyawan, $bulan = null, $tahun = null)
    {

        $bulan = $bulan ?? date('m');   // default ke bulan sekarang
        $tahun = $tahun ?? date('Y');   // default ke tahun sekarang (4 digit)



        $periodeGajiObject = $this->getPerioderGajiSekarang();
        $id_periode_penggajian = $periodeGajiObject->id_periode_gaji;
        $getToleranceTerlambat = MasterKode::findOne(['nama_group' => Yii::$app->params['teleransi-keterlambatan']])['nama_kode'];;


        // Buat query
        $query = (new \yii\db\Query())
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'bg.id_bagian',
                'bg.nama_bagian',
                'mk.nama_kode AS jabatan',
                'pg.bulan',
                'pg.tahun',
                'pg.tanggal_awal',
                'pg.tanggal_akhir'
            ])
            ->from('karyawan')
            ->leftJoin('periode_gaji pg', 'pg.id_periode_gaji = :periode_gaji_id')
            ->leftJoin('data_pekerjaan dp', 'dp.id_karyawan = karyawan.id_karyawan AND dp.is_aktif = 1')
            ->leftJoin('bagian bg', 'dp.id_bagian = bg.id_bagian')
            ->leftJoin('master_kode mk', 'mk.nama_group = "jabatan" AND dp.jabatan = mk.kode')
            ->where(['karyawan.is_aktif' => 1])
            ->groupBy([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'bg.id_bagian',
                'bg.nama_bagian',
                'mk.nama_kode',
                'pg.tanggal_awal',
                'pg.tanggal_akhir',
                'pg.bulan',
                'pg.tahun',
            ])
            ->orderBy([
                'bg.id_bagian' => SORT_DESC,
                'karyawan.nama' => SORT_ASC
            ])
            ->addParams([':periode_gaji_id' => $id_periode_penggajian]);

        if ($id_karyawan) {
            $query->andWhere(['karyawan.id_karyawan' => $id_karyawan]);
        }


        $dataKaryawan = $query->all();

        // Ambil absensi nested
        foreach ($dataKaryawan as &$karyawan) {
            $totalAbsensi = (new \yii\db\Query())
                ->from('absensi')
                ->where([
                    'id_karyawan' => $karyawan['id_karyawan']
                ])
                ->andWhere(['between', 'tanggal', $karyawan['tanggal_awal'], $karyawan['tanggal_akhir']])
                ->all();

            $terlambatList = [];
            $terlambatWithDate = [];

            foreach ($totalAbsensi as $absen) {
                if (!is_null($absen['lama_terlambat'])) {
                    $terlambatList[] = $absen['lama_terlambat'];

                    $terlambatWithDate[] = [
                        'tanggal' => $absen['tanggal'],
                        'terlambat' => $absen['lama_terlambat'],
                    ];
                }
            }

            $karyawan['total_absensi'] = count($totalAbsensi);
            $karyawan['terlambat'] = $terlambatList;
            $karyawan['terlambat_with_date'] = $terlambatWithDate;
        }

        // Warna pastel per id_bagian
        $colorList = [
            '#A5D6A7', // hijau pastel
            '#FFF59D', // kuning pastel
            '#90CAF9', // biru pastel
            '#FFCC80', // oranye pastel
            '#CE93D8', // ungu pastel
            '#B0BEC5', // abu pastel
            '#F48FB1', // pink pastel
            '#AED581', // lime pastel
            '#80DEEA', // cyan pastel
        ];

        $usedColors = [];
        $colorIndex = 0;

        foreach ($dataKaryawan as &$item) {
            $idBagian = $item['id_bagian'] ?? 'null';

            if (!isset($usedColors[$idBagian])) {
                $usedColors[$idBagian] = $colorList[$colorIndex] ?? '#F0F0F0';
                $colorIndex++;
            }

            $item['color'] = $usedColors[$idBagian];
        }
        unset($item);

        // Tambahkan data gaji, potongan, tunjangan, lembur, dll
        foreach ($dataKaryawan as &$karyawan) {
            $id = $karyawan['id_karyawan'];

            $karyawan['nominal_gaji'] = $this->getNominalGajiKaryawan($id);
            $karyawan['potongan_karyawan'] = $this->getPotonganKaryawan($id, $karyawan['nominal_gaji']);
            $karyawan['tunjangan_karyawan'] = $this->getTunjanganKaryawan($id, $karyawan['nominal_gaji']);
            $karyawan['gaji_perhari'] = $this->getGajiKaryawanPerHari($karyawan['nominal_gaji'], $id, $periodeGajiObject);
            $lemburData = $this->getLemburKaryawan($id, $karyawan['nominal_gaji'], $periodeGajiObject);
            $karyawan['jam_lembur'] = $lemburData['jam_lembur'];
            $karyawan['total_pendapatan_lembur'] = $lemburData['total_pendapatan_lembur'];
            $karyawan['potongan_terlambat'] = $this->getPotonganTerlambat($id, $karyawan['terlambat'], $getToleranceTerlambat, $karyawan['nominal_gaji']);
            $karyawan['list_terlambat'] = $karyawan['terlambat'];
            $karyawan['terlambat'] = $this->calculateTerlambat($karyawan['terlambat']);
            $karyawan['total_alfa_range'] = $this->getTotalAlfaRange($id, $periodeGajiObject, $karyawan['total_absensi']);
            $potonganAndwfh = $this->getPotonganAbsensi($id, $karyawan['gaji_perhari'], $karyawan['total_alfa_range'], $periodeGajiObject);
            $karyawan['jumlah_wfh'] = $potonganAndwfh['jumlah_wfh'];
            $karyawan['potongan_absensi'] = $potonganAndwfh['allpotongan'];
            $karyawan['dinas_luar_belum_terbayar'] = $this->getDinasLuarBelumTerbayar($id, $periodeGajiObject);

            $karyawan['gaji_bersih'] = $karyawan['nominal_gaji']
                + $karyawan['tunjangan_karyawan']
                + $karyawan['dinas_luar_belum_terbayar']
                + $karyawan['total_pendapatan_lembur']
                - $karyawan['potongan_karyawan']
                - $karyawan['potongan_absensi']
                - $karyawan['potongan_terlambat'];

            $karyawan['status'] = 0;
        }
        unset($karyawan);

        // Buat data provider
        $dataProvider = new ArrayDataProvider([
            'allModels' => $dataKaryawan,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => [
                    'id_karyawan',
                    'nama',
                    'nama_bagian' => [
                        'asc' => ['nama_bagian' => SORT_ASC, 'jabatan' => SORT_ASC],
                        'desc' => ['nama_bagian' => SORT_DESC, 'jabatan' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'nominal_gaji',
                    'total_alfa_range',
                    'total_absensi',
                    'gaji_perhari',
                    'potongan_karyawan',
                    'tunjangan_karyawan',
                    'jam_lembur',
                    'total_pendapatan_lembur',
                    'gaji_bersih',
                    'dinas_luar_belum_terbayar',
                ],
            ],
        ]);

        // Filter form search
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }


    public function getPotonganTerlambat($id_karyawan, $terlambat, $getToleranceTerlambat, $gajiPokok)
    {
        $totalMenitTerlambat = 0;

        foreach ($terlambat as $waktu) {
            list($jam, $menit, $detik) = explode(':', $waktu);
            $menitTotal = ((int)$jam * 60) + (int)$menit + ((int)$detik >= 30 ? 1 : 0);

            if ($menitTotal > (int)$getToleranceTerlambat) {
                $totalMenitTerlambat += ($menitTotal);
            }
        }


        if ($totalMenitTerlambat > $getToleranceTerlambat) {
            $potonganPerMenit = ($gajiPokok / 173) / 60; // Gaji per jam dibagi 60 untuk dapat per menit
            return $totalMenitTerlambat * $potonganPerMenit;
        }

        return 0;
    }


    protected function calculateTerlambat($data)
    {
        $totalMenit = 0;

        foreach ($data as $waktu) {
            list($jam, $menit, $detik) = explode(':', $waktu);

            // Hitung total menit, bulatkan jika detik >= 30
            $menitTotal = ((int)$jam * 60) + (int)$menit + ((int)$detik >= 30 ? 1 : 0);

            // Tambahkan hanya jika lebih dari 10 menit
            if ($menitTotal > 10) {
                $totalMenit += $menitTotal;
            }
        }

        // Konversi ke jam dan menit
        $jam = floor($totalMenit / 60);
        $menit = $totalMenit % 60;

        // Format hasil akhir sebagai HH:MM (tanpa leading zero di jam)
        return sprintf('%d:%02d', $jam, $menit);
    }

    public function getNominalGajiKaryawan($id_karyawan)
    {
        $nominal = (new \yii\db\Query())
            ->select(['nominal_gaji'])
            ->from('master_gaji')
            ->where(['id_karyawan' => $id_karyawan])
            ->scalar();

        return $nominal ?: 0;
    }

    /**
     * Fungsi untuk mendapatkan total tunjangan karyawan
     */
    public function getTunjanganKaryawan($id_karyawan, $gajiPokok = 0)
    {
        $query = (new \yii\db\Query())
            ->select([
                'td.jumlah',
                't.satuan'
            ])
            ->from('tunjangan_detail td')
            ->innerJoin('tunjangan t', 't.id_tunjangan = td.id_tunjangan')
            ->where([
                'td.id_karyawan' => $id_karyawan,
                'td.status' => 1
            ]);

        $rows = $query->all();
        $total = 0;

        foreach ($rows as $row) {
            if (($row['satuan'] == '%' || $row['jumlah'] < 100) && $gajiPokok > 0) {
                $total += ($gajiPokok * $row['jumlah']) / 100;
            } else {
                $total += $row['jumlah'];
            }
        }

        return $total ?: 0;
    }

    public function getPotonganKaryawan($id_karyawan, $gajiPokok = 0)
    {
        $query = (new \yii\db\Query())
            ->select([
                'pd.jumlah',
                'p.satuan'
            ])
            ->from('potongan_detail pd')
            ->innerJoin('potongan p', 'p.id_potongan = pd.id_potongan')
            ->where([
                'pd.id_karyawan' => $id_karyawan,
                'pd.status' => 1
            ]);

        $rows = $query->all();


        $total = 0;

        foreach ($rows as $row) {
            if (($row['satuan'] == '%' || $row['jumlah'] < 100) && $gajiPokok > 0) {
                $total += ($gajiPokok * $row['jumlah']) / 100;
            } else {
                $total += $row['jumlah'];
            }
        }
        return $total ?: 0;
    }

    public function getLemburKaryawan($id_karyawan, $gajiKaryawan, $periodeGajiObject)
    {

        $tanggal_awal = $periodeGajiObject['tanggal_awal'];
        $tanggal_akhir = $periodeGajiObject['tanggal_akhir'];

        $jam_lembur = 0;

        $jenisPengambilanLembur = SettinganUmum::find()
            ->where(['kode_setting' => Yii::$app->params['ajukan_lembur']])
            ->one();



        if ($jenisPengambilanLembur && $jenisPengambilanLembur->nilai_setting == '0') {
            // Lembur tidak diajukan, ambil dari rekap langsung
            $jam_lembur = (new \yii\db\Query())
                ->from('rekap_lembur')
                ->where(['id_karyawan' => $id_karyawan])
                ->andWhere(['between', 'tanggal', $tanggal_awal, $tanggal_akhir])
                ->sum('jam_total');
        } else {
            // Lembur diajukan, ambil dari pengajuan_lembur yang disetujui
            $jam_lembur = (new \yii\db\Query())
                ->from('pengajuan_lembur')
                ->where([
                    'id_karyawan' => $id_karyawan,
                    'status' => 1, // hanya yang disetujui
                ])
                ->andWhere(['between', 'tanggal', $tanggal_awal, $tanggal_akhir])
                ->sum('hitungan_jam');
        }


        return [
            'jam_lembur' => $jam_lembur ?? 0,
            'total_pendapatan_lembur' => $jam_lembur * round($gajiKaryawan / 173, 2),
        ];
    }

    public function getGajiKaryawanPerHari($gajiPokok = 0, $id_karyawan = null,  $periode_gaji = null)
    {


        // Cek apakah setting sudah di-cache
        if ($this->_settingGajiPerJam === null) {
            $settingKeys = [
                'penggajian_hari_kalender' => Yii::$app->params['penggajian_hari_kalender'],
                'potongan_hari_kerja_efektif' => Yii::$app->params['potongan_hari_kerja_efektif'],
                'penggajian_berbasis_jam_kerja' => Yii::$app->params['penggajian_berbasis_jam_kerja'],
            ];

            $settings = SettinganUmum::find()
                ->where(['kode_setting' => array_values($settingKeys)])
                ->indexBy('kode_setting')
                ->all();

            $this->_settingGajiPerJam = [
                'penggajian_hari_kalender' => $settings[$settingKeys['penggajian_hari_kalender']]->nilai_setting ?? 0,
                'potongan_hari_kerja_efektif' => $settings[$settingKeys['potongan_hari_kerja_efektif']]->nilai_setting ?? 0,
                'penggajian_berbasis_jam_kerja' => $settings[$settingKeys['penggajian_berbasis_jam_kerja']]->nilai_setting ?? 0,
            ];
        }

        $bulan = date('n'); // bulan sekarang (1-12)
        $tahun = date('Y'); // tahun sekarang (4 digit)

        $totalHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        // Ambil dari cache
        $setting = $this->_settingGajiPerJam;;

        $liburCount = $this->countLiburInRange($id_karyawan, $periode_gaji['tanggal_awal'], $periode_gaji['tanggal_akhir']);

        $tanggalAwal = new \DateTime($periode_gaji['tanggal_awal']);
        $tanggalAkhir = new \DateTime($periode_gaji['tanggal_akhir']);
        $interval = $tanggalAwal->diff($tanggalAkhir);

        // Total hari antara tanggalAwal dan tanggalAkhir (termasuk keduanya)
        $countRangeTanggal = $interval->days + 1;

        // Hitung jumlah hari libur nasional (master_haribesar) di antara tanggal_awal dan tanggal_akhir
        $masterHariLibur = (new \yii\db\Query())
            ->from('master_haribesar')
            ->where(['between', 'tanggal', $periode_gaji['tanggal_awal'], $periode_gaji['tanggal_akhir']])
            ->count();


        if (
            $setting['penggajian_hari_kalender'] == 1 &&
            $setting['potongan_hari_kerja_efektif'] == 0 &&
            $setting['penggajian_berbasis_jam_kerja'] == 0
        ) {
            //
            return ($gajiPokok / $totalHari) * 1;
        } elseif (
            $setting['penggajian_hari_kalender'] == 0 &&
            $setting['potongan_hari_kerja_efektif'] == 1 &&
            $setting['penggajian_berbasis_jam_kerja'] == 0
        ) {
            return ($gajiPokok / ($countRangeTanggal - $liburCount   - $masterHariLibur)) * 1;
        } elseif (
            $setting['penggajian_hari_kalender'] == 0 &&
            $setting['potongan_hari_kerja_efektif'] == 0 &&
            $setting['penggajian_berbasis_jam_kerja'] == 1
        ) {
            return ($gajiPokok / 173);
        } else {
            throw new \Exception('Hanya satu jenis perhitungan gaji per jam yang boleh aktif.');
        }
    }

    protected function getDinasLuarBelumTerbayar($id_karyawan, $periodeGajiObject)
    {
        $tanggal_awal = $periodeGajiObject->tanggal_awal;
        $tanggal_akhir = $periodeGajiObject->tanggal_akhir;

        $query = PengajuanDinas::find()
            ->where([
                'id_karyawan' => $id_karyawan,
                'status' => 1,
                'status_dibayar' => 0
            ])
            ->andWhere(['<=', 'tanggal_mulai', $tanggal_akhir])
            ->andWhere(['>=', 'tanggal_selesai', $tanggal_awal]);

        return $query->sum('biaya_yang_disetujui') ?? 0;
    }



    protected function getTotalAlfaRange($id_karyawan, $periode_gaji, $absen = 0)
    {
        // Ambil id_jam_kerja dari jam_kerja_karyawan

        $tanggalAwal = new \DateTime($periode_gaji['tanggal_awal']);
        $tanggalAkhir = new \DateTime($periode_gaji['tanggal_akhir']);
        $interval = $tanggalAwal->diff($tanggalAkhir);

        // Total hari antara tanggalAwal dan tanggalAkhir (termasuk keduanya)
        $countRangeTanggal = $interval->days + 1;
        $idJamKerja = (new \yii\db\Query())
            ->select('id_jam_kerja')
            ->from('jam_kerja_karyawan')
            ->where(['id_karyawan' => $id_karyawan])
            ->scalar();

        if (!$idJamKerja) {
            return $countRangeTanggal; // Kalau tidak punya jadwal kerja, tidak bisa hitung
        }

        // Hitung libur menggunakan fungsi terpisah
        $liburCount = $this->countLiburInRange($id_karyawan, $periode_gaji['tanggal_awal'], $periode_gaji['tanggal_akhir']);

        return $countRangeTanggal - $absen - $liburCount;
    }

    protected function countLiburInRange($id_karyawan, $tanggal_awal, $tanggal_akhir)
    {
        // Ambil id_jam_kerja
        $idJamKerja = (new \yii\db\Query())
            ->select('id_jam_kerja')
            ->from('jam_kerja_karyawan')
            ->where(['id_karyawan' => $id_karyawan])
            ->scalar();

        if (!$idJamKerja) {
            return 0;
        }

        // Ambil hari kerja resmi (unik)
        $hariKerjaResmi = array_unique(
            (new \yii\db\Query())
                ->select('nama_hari')
                ->from('jadwal_kerja')
                ->where(['id_jam_kerja' => $idJamKerja])
                ->column()
        );

        // normalisasi ke string untuk perbandingan
        $hariKerjaResmi = array_map('strval', $hariKerjaResmi);

        $tanggalAwalObj = new \DateTime($tanggal_awal);
        $tanggalAkhirObj = new \DateTime($tanggal_akhir);

        $liburCount = 0;
        $tanggalIterasi = clone $tanggalAwalObj;

        while ($tanggalIterasi <= $tanggalAkhirObj) {
            $namaHari = $tanggalIterasi->format('N'); // 1 (Mon) .. 7 (Sun)
            if (!in_array((string)$namaHari, $hariKerjaResmi, true)) {
                $liburCount++;
            }
            $tanggalIterasi->modify('+1 day');
        }

        return $liburCount;
    }

    protected function getPotonganAbsensi($id_karyawan, $gajiPerhari = 0, $total_alfa = 0, $periode_gaji = null)
    {
        // fallback ke periode sekarang jika tidak diberikan
        if ($periode_gaji === null) {
            $periode_gaji = $this->getPerioderGajiSekarang();
        }

        // jika tidak ada gaji per jam atau tidak ada alfa, tidak ada potongan
        if (empty($gajiPerhari) || empty($total_alfa)) {
            return [
                'jumlah_wfh' => 0,
                'allpotongan' => 0
            ];
        }

        // Ambil semua tanggal_array pengajuan WFH yang disetujui dalam periode
        $pengajuanWfhTanggalArray = (new \yii\db\Query())
            ->select('tanggal_array')
            ->from('pengajuan_wfh')
            ->where([
                'id_karyawan' => $id_karyawan,
                'status' => 1, // hanya yang disetujui
            ])
            ->column();


        // Gabungkan semua tanggal dari array
        $wfhDates = [];
        foreach ($pengajuanWfhTanggalArray as $jsonTanggalArray) {
            $arr = json_decode($jsonTanggalArray, true);
            if (is_array($arr)) {
                foreach ($arr as $tgl) {
                    // Pastikan tanggal dalam range periode
                    if ($tgl >= $periode_gaji['tanggal_awal'] && $tgl <= $periode_gaji['tanggal_akhir']) {
                        $wfhDates[] = $tgl;
                    }
                }
            }
        }

        // Hitung jumlah tanggal WFH yang termasuk dalam periode
        $pengajuanWfhCount = count($wfhDates);

        // Cache master kode potongan WFH agar hanya query sekali
        if ($this->_masterWfhPotongan === null) {
            $masterWfhPotongan = MasterKode::findOne(['nama_group' => 'potongan-persen-wfh']);
            $this->_masterWfhPotongan = $masterWfhPotongan ? (float)$masterWfhPotongan->nama_kode : 0;
        }
        $dataPotonganPersenWfh = $this->_masterWfhPotongan;

        $potongan = $dataPotonganPersenWfh > 0 ?
            $dataPotonganPersenWfh / 100 : 0;


        $data =  ($gajiPerhari * ($pengajuanWfhCount * $potongan)) + ($gajiPerhari * $total_alfa);

        return [
            'jumlah_wfh' => $pengajuanWfhCount ?? 0,
            'allpotongan' => $data ?? 0
        ];
    }
}
