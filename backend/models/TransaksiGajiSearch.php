<?php

namespace backend\models;

use backend\models\helpers\PeriodeGajiHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TransaksiGaji;
use PhpParser\Node\Stmt\Expression;
use yii\db\Query;

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
            [['id_transaksi_gaji', 'jam_kerja', 'periode_gaji', 'jumlah_hari_kerja', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_wfh', 'jumlah_cuti'], 'integer'],
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

    public function search($params, $bulan, $tahun, $id_karyawan, $periode_gaji_id)
    {


        if ($periode_gaji_id == null || $periode_gaji_id == '') {
            $periode_gaji_id = PeriodeGajiHelper::getPeriodeGajiBulanIni()['id_periode_gaji'];
        }
        $query = (new \yii\db\Query())
            ->select([
                'k.id_karyawan',
                'k.nomer_identitas',
                'pg.id_periode_gaji',
                'pg.bulan',
                'pg.tahun',
                'pg.terima',
                'tg.*',
            ])
            ->from('karyawan k')
            ->where(['k.is_aktif' => 1]);

        // Jika id_karyawan tidak null, tambahkan kondisi where
        $query->andWhere(['pg.id_periode_gaji' => $periode_gaji_id]);


        if ($id_karyawan != null) {
            $query->andWhere(['k.id_karyawan' => $id_karyawan]);
        }

        $query->leftJoin('periode_gaji pg', [
            'AND',
            'pg.bulan = :bulan',
            'pg.tahun = :tahun'
        ])
            ->leftJoin('transaksi_gaji tg', 'tg.periode_gaji = pg.id_periode_gaji AND tg.nomer_identitas = k.nomer_identitas')
            ->params([
                ':bulan' => $bulan,
                ':tahun' => $tahun
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }



        return $dataProvider;
    }

    public function searchGaji($params, $bulan, $tahun)
    {

        $periodeGajian = PeriodeGaji::findOne(['bulan' => $bulan, 'tahun' => $tahun]);

        $query = (new Query())
            ->select([
                'k.id_karyawan',
                'k.kode_karyawan',
                'k.nama AS nama_karyawan',
                'MAX(dp.id_data_pekerjaan) AS id_data_pekerjaan',
                'MAX(bg.nama_bagian) AS bagian',
                'MAX(jk.id_jam_kerja_karyawan) AS id_jam_kerja_karyawan',
                // 'MAX(jk.id_jam_kerja) AS id_jam_kerja',
                // 'MAX(jk.max_terlambat) AS max_terlambat',
                // 'MAX(j.nama_jam_kerja) AS nama_jam_kerja',
                // 'MAX(j.jenis_shift) AS jenis_shift',

            ])
            ->from('{{%karyawan}} k')
            ->where(['k.is_aktif' => 1])
            ->leftJoin('{{%data_pekerjaan}} dp', 'k.id_karyawan = dp.id_karyawan')
            ->leftJoin('{{%bagian}} bg', 'dp.id_bagian = bg.id_bagian AND dp.is_aktif = 1')
            ->leftJoin('{{%jam_kerja_karyawan}} jk', 'k.id_karyawan = jk.id_karyawan')
            // ->leftJoin('{{%jam_kerja}} j', 'jk.id_jam_kerja = j.id_jam_kerja')
            // ->leftJoin('{{%jadwal_kerja}} wj', 'jk.id_jam_kerja = wj.id_jam_kerja')
            // ->leftJoin('{{%atasan_karyawan}} atsk', 'k.id_karyawan = atsk.id_karyawan')
            // ->leftJoin('{{%master_lokasi}} msl', 'atsk.id_master_lokasi = msl.id_master_lokasi')
            // ->leftJoin('{{%TransaksiGaji}} tg', 'k.id_karyawan = tg.id_karyawan AND a.bulan = :bulan')
            // ->addParams([':bulan' => $tanggalSet]); // Menambahkan parameter tanggal
            ->groupBy('k.id_karyawan');




        $results = $query->all();
        // dd($results);
        $result = [];

        $currentDate = $tanggalSet; // Gunakan tanggalSet
        // Mendapatkan hari saat ini (1 = Senin, 0 = Minggu, dst.)
        $currentDayOfWeek = date('w'); // 0 (Minggu) sampai 6 (Sabtu)
        $currentDayOfWeek = $currentDayOfWeek == 0 ? 7 : $currentDayOfWeek; // Mengubah Minggu (0) menjadi 7

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
                    'absensi' => [],
                    'jam_kerja' => [],
                    'jadwal_kerja' => [] // Tambahkan untuk jadwal kerja
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
            if ($row['id_absensi'] && $row['tanggal_absensi'] == $currentDate) {
                // Tambahkan entri absensi ke array
                $result[$row['id_karyawan']]['absensi'] = [
                    'id_absensi' => $row['id_absensi'],
                    'tanggal_absensi' => $row['tanggal_absensi'],
                    'jam_masuk' => $row['jam_masuk'],
                    'long' => $row['long'],
                    'lat' => $row['lat'],
                    'penempatan_long' => $row['penempatan_longtitude'],
                    'penempatan_lat' => $row['penempatan_latitude'],
                    // 'jam_pulang' => $row['jam_pulang'],
                    'kode_status_hadir' => $row['kode_status_hadir'],
                    // 'keterangan_absensi' => $row['keterangan_absensi'],
                    // 'lampiran' => $row['lampiran'],
                ];
            }

            // Add jam_kerja
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

            // Add jadwal_kerja (hanya untuk hari ini)
            if ($row['id_jadwal_kerja']) {
                // Cek jika jadwal kerja sudah ada untuk karyawan ini
                $existingJadwal = $result[$row['id_karyawan']]['jadwal_kerja'] ?? null;

                // Jika belum ada, tambahkan jadwal kerja
                if (!$existingJadwal) {
                    $result[$row['id_karyawan']]['jadwal_kerja'] = [
                        'id_jadwal_kerja' => $row['id_jadwal_kerja'],
                        'nama_hari' => $row['nama_hari'],
                        'jam_masuk' => $row['jam_masuk_jadwal'],
                        'jam_keluar' => $row['jam_keluar_jadwal'],
                        // 'mulai_istirahat' => $row['mulai_istirahat'],
                        // 'berakhir_istirahat' => $row['berakhir_istirahat'],
                        'jumlah_jam' => $row['jumlah_jam'],
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
}
