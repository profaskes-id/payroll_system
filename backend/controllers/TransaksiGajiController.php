<?php

namespace backend\controllers;

use backend\models\DinasDetailGaji;
use backend\models\helpers\PeriodeGajiHelper;
use backend\models\Karyawan;
use backend\models\LemburGaji;
use backend\models\MasterGaji;
use backend\models\MasterKode;
use backend\models\PembayaranKasbon;
use backend\models\PendapatanPotonganLainnya;
use backend\models\PendingKasbon;
use backend\models\PengajuanDinas;
use backend\models\PeriodeGaji;
use backend\models\PotonganAlfaAndWfhPenggajian;
use backend\models\PotonganDetail;
use backend\models\PotonganRekapGaji;
use backend\models\RekapGajiKaryawanPertransaksi;
use backend\models\RekapTerlambatTransaksiGaji;
use backend\models\SettinganUmum;
use backend\models\TransaksiGaji;
use backend\models\TransaksiGajiSearch;
use backend\models\TunjanganDetail;
use backend\models\TunjanganRekapGaji;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class TransaksiGajiController extends Controller
{

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],

            ]
        );
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            // Ini adalah insert baru
            Yii::$app->session->set('lastInsertedId', $this->id_transaksi_gaji);
        }
    }


    // ! ==================pages==================
    public function actionIndex()
    {
        $params = Yii::$app->request->get();

        $id_karyawan = $params['TransaksiGaji']['id_karyawan'] ?? null;
        $bulan = $params['TransaksiGaji']['bulan'] ?? date('m');
        $tahun = $params['TransaksiGaji']['tahun'] ?? date('Y');
        $model = new TransaksiGaji();
        $searchModel = new TransaksiGajiSearch();
        $periode_gaji = new PeriodeGaji();
        $karyawanID = null;
        $periode_gajiID = PeriodeGajiHelper::getPeriodeGajiBulanIni();
        $id_bagian =  $params['TransaksiGaji']['id_bagian'] ?? null;
        $jabatan =  $params['TransaksiGaji']['jabatan'] ?? null;
        $status_pekerjaan =  $params['TransaksiGaji']['status_pekerjaan'] ?? null;

        // Ambil semua dari search (data lengkap dengan gaji_bersih)
        $allDataFromSearch = $searchModel->search($this->request->queryParams, $id_karyawan, $bulan, $tahun, $jabatan, $id_bagian, $status_pekerjaan)->getModels();

        // Indexing data search berdasarkan ID karyawan
        $searchIndexed = [];
        foreach ($allDataFromSearch as $item) {
            if ($item['visibility'] == 1) {
                $searchIndexed[$item['id_karyawan']] = $item;
            }
        }


        // Ambil data dari tabel transaksi_gaji
        $existsDataTransaksi = TransaksiGaji::find()
            ->where(['bulan' => $bulan, 'tahun' => $tahun]);
        $query = TransaksiGaji::find()->where(['bulan' => $bulan, 'tahun' => $tahun]);

        if ($id_bagian) {
            $query->andWhere(['id_bagian' => $id_bagian]);
        }

        if ($jabatan) {
            $query->andWhere(['jabatan' => $jabatan]);
        }

        if ($id_karyawan) {
            $query->andWhere(['id_karyawan' => $id_karyawan]);
        }

        if ($status_pekerjaan) {
            $query->andWhere(['status_pekerjaan' => $status_pekerjaan]);
        }
        $existsDataTransaksi = $query->asArray()->all();



        // Proses data yang sudah ada di DB
        $finalData = [];

        foreach ($existsDataTransaksi as $transaksi) {
            $idKaryawan = $transaksi['id_karyawan'];

            // Ambil data dari search jika ada (untuk data tambahan), tetapi prioritas ke data transaksi
            $dataSearch = $searchIndexed[$idKaryawan] ?? [];

            // Gabungkan, dengan prioritas data transaksi
            $merged = array_merge($dataSearch, $transaksi);

            $finalData[] = $merged;

            // Hapus dari search agar tidak dimasukkan dua kali
            unset($searchIndexed[$idKaryawan]);
        }
        foreach ($searchIndexed as $sisa) {
            $finalData[] = $sisa;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $finalData,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => [

                    'id_karyawan',
                    'nama',
                    'jabatan',
                    'id_bagian',
                    'nama_bagian' => [
                        'asc' => ['nama_bagian' => SORT_ASC, 'jabatan' => SORT_ASC],
                        'desc' => ['nama_bagian' => SORT_DESC, 'jabatan' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'nominal_gaji',
                    'total_alfa_range',
                    'total_absensi',
                    'gaji_perhari',
                    'potongan_karyawan',
                    'potongan_absensi',
                    'potongan_terlambat',
                    'tunjangan_karyawan',
                    'jam_lembur',
                    'total_pendapatan_lembur',
                    'gaji_bersih',
                    'dinas_luar_belum_terbayar',
                    'status'

                ],
            ],
        ]);
        if ($this->request->isPost) {
            $karyawanID = Yii::$app->request->post('Karyawan')['id_karyawan'];
            $periode_gajiID = intval(Yii::$app->request->post('PeriodeGaji')['id_periode_gaji']);

            if (!$karyawanID) $karyawanID = null;
            if (!$periode_gajiID) $periode_gajiID = null;

            $periode_gaji = PeriodeGaji::findOne($periode_gajiID);
            $bulan = $periode_gaji->bulan;
            $tahun = $periode_gaji->tahun;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'periode_gaji' => $periode_gaji,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'model' => $model,
            'status_pekerjaan' => $status_pekerjaan,
            'karyawanID' => $karyawanID,
            'id_bagian' => $id_bagian,
            'jabatan' => $jabatan,
            'periode_gajiID' => $periode_gajiID
        ]);
    }


    // ? ==================Generate==================
    public function actionGenerateGaji()
    {
        $params  = Yii::$app->request->post();
        $bulan = $params['bulan'] ??  date('m');
        $tahun = $params['tahun'] ?? date('Y');
        $this->removeAll($bulan, $tahun);
        $searchModel = new TransaksiGajiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, null, $bulan, $tahun);
        $models = $dataProvider->getModels();
        $rows = [];
        $now = date('Y-m-d H:i:s');
        $userId = Yii::$app->user->id;
        $tunjanganRekapRows = [];
        $potonganRekapRows = [];
        $dinasDetailRows = [];
        $lemburGajiRows = [];
        $rekapGajiRows = [];
        $rekapTerlambatRows = [];
        $potonganAlfaWfhRows = [];
        $kasbonRekapRows = [];

        if (!empty($models)) {
            foreach ($models as $modelData) {
                $idKaryawan = $modelData['id_karyawan'];
                $nominalGaji = $modelData['nominal_gaji'];
                $tunjanganList = TunjanganDetail::getTunjanganKaryawan($idKaryawan, $nominalGaji);
                $potonganList = PotonganDetail::getPotonganKaryawan($idKaryawan, $nominalGaji);
                $kasbonList = $searchModel->getKasbonKaryawan($idKaryawan);


                $periode_gaji = PeriodeGaji::findOne(['bulan' => $bulan, 'tahun' => $tahun]);
                $tanggal_awal_periode = $periode_gaji->tanggal_awal;
                $tanggal_akhir_periode = $periode_gaji->tanggal_akhir;

                // Ambil data terlambat
                $terlambatData = $this->actionGetPotonganTerlambatKaryawan($idKaryawan);
                $filteredTerlambat = $terlambatData['filteredTerlambat'];
                $potonganPerMenit = $terlambatData['potonganPerMenit'];
                // Ambil data lembur
                $lemburData = $this->getLemburData($idKaryawan, $bulan, $tahun, $tanggal_awal_periode, $tanggal_akhir_periode);
                $jam_lembur = $lemburData['jam_lembur'];
                $gaji_perjam = $lemburData['gaji_perjam'];
                $total_hitungan_jam = $lemburData['total_hitungan_jam'];
                $total_pendapatan_lembur = $lemburData['total_pendapatan_lembur'];

                $pendingKasbon = null;

                if (isset($kasbonList['data_terakhir']['id_kasbon'])) {
                    $pendingKasbon = PendingKasbon::find()
                        ->where([
                            'id_karyawan' => $idKaryawan,
                            'id_kasbon' => $kasbonList['data_terakhir']['id_kasbon'],
                            'bulan' => $bulan,
                            'tahun' => $tahun,
                        ])
                        ->one();
                }
                $dinasList = PengajuanDinas::find()
                    ->where(['id_karyawan' => $idKaryawan, 'status' => 1, 'status_dibayar' => 0])
                    ->asArray()
                    ->all();



                // Ambil data potongan absensi (alfa & WFH)
                $potonganAbsensiData = $this->actionGetPotonganAbsensiKaryawan(
                    $idKaryawan,
                    $modelData['total_alfa_range'],
                    $modelData['gaji_perhari'],
                    $modelData['jumlah_wfh'] ?? 0
                );

                // Simpan data potongan alfa & WFH
                if ($potonganAbsensiData['success']) {
                    $potonganAlfaWfhRows[] = [
                        'id_karyawan' => $idKaryawan,
                        'nama' => $modelData['nama'],
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'jumlah_alfa' => $potonganAbsensiData['total_alfa_range'],
                        'total_potongan_alfa' => $potonganAbsensiData['total_alfa_range']  * $potonganAbsensiData['gaji_perhari'],
                        'jumlah_wfh' => $potonganAbsensiData['jumlah_wfh'],
                        'persen_potongan_wfh' => $potonganAbsensiData['potonganwfhsehari'],
                        'total_potongan_wfh' => $potonganAbsensiData['jumlah_wfh'] * ($potonganAbsensiData['potonganwfhsehari'] / 100) * $potonganAbsensiData['gaji_perhari'],
                        'gaji_perhari' => $potonganAbsensiData['gaji_perhari'],
                    ];
                }

                // Simpan data terlambat ke array untuk batch insert
                foreach ($filteredTerlambat as $terlambat) {
                    $rekapTerlambatRows[] = [
                        'id_karyawan' => $idKaryawan,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'nama' => $modelData['nama'],
                        'tanggal' => $terlambat['tanggal'],
                        'lama_terlambat' => $terlambat['terlambat'],
                        'potongan_permenit' => $potonganPerMenit,
                    ];
                }


                // Ambil data terlambat
                $terlambatData = $this->actionGetPotonganTerlambatKaryawan($idKaryawan);
                $filteredTerlambat = $terlambatData['filteredTerlambat'];
                $potonganPerMenit = $terlambatData['potonganPerMenit'];

                // Simpan data terlambat ke array untuk batch insert
                foreach ($filteredTerlambat as $terlambat) {
                    $rekapTerlambatRows[] = [
                        'id_karyawan' => $idKaryawan,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'nama' => $modelData['nama'],
                        'tanggal' => $terlambat['tanggal'],
                        'lama_terlambat' => $terlambat['terlambat'],
                        'potongan_permenit' => $potonganPerMenit,
                    ];
                }

                // Simpan data ke transaksi_gaji    
                $rows[] = [
                    'id_karyawan' => $modelData['id_karyawan'],
                    'nama' => $modelData['nama'],
                    'id_bagian' => $modelData['id_bagian'],
                    'nama_bagian' => $modelData['nama_bagian'],
                    'jabatan' => $modelData['jabatan'],
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'tanggal_awal' => $modelData['tanggal_awal'],
                    'tanggal_akhir' => $modelData['tanggal_akhir'],
                    'total_absensi' => $modelData['total_absensi'],
                    'terlambat' => $modelData['terlambat'],
                    'nominal_gaji' => $modelData['nominal_gaji'],
                    'potongan_karyawan' => $modelData['potongan_karyawan'],
                    'potongan_kasbon' => $pendingKasbon ? 0 : $modelData['kasbon_karyawan'],
                    'tunjangan_karyawan' => $modelData['tunjangan_karyawan'],
                    'gaji_perhari' => $modelData['gaji_perhari'],
                    'jam_lembur' => $total_hitungan_jam,
                    'total_pendapatan_lembur' => $total_pendapatan_lembur,
                    'potongan_terlambat' => $modelData['potongan_terlambat'],
                    'total_alfa_range' => $modelData['total_alfa_range'],
                    'potongan_absensi' => $modelData['potongan_absensi'],
                    'dinas_luar_belum_terbayar' => $modelData['dinas_luar_belum_terbayar'],
                    'created_at' => $now,
                    'updated_at' => $now,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                    'status' => 1,
                    'hari_kerja_efektif' => $modelData['hari_kerja_efektif'],
                    'nama_bank' =>  $modelData['nama_bank'],
                    'nomer_rekening' => $modelData['nomer_rekening'],
                    'pendapatan_lainnya' => $modelData['pendapatan_lainnya'],
                    'potongan_lainnya' => $modelData['potongan_lainnya'],
                    'gaji_diterima' => $modelData['gaji_bersih'],
                    'status_pekerjaan' => $modelData['status_pekerjaan']
                ];

                // Simpan data untuk rekap_gaji_karyawan_pertransaksi
                $rekapGajiRows[] = [
                    'id_karyawan' => $idKaryawan,
                    'nama_karyawan' => $modelData['nama'] ?? '-',
                    'nama_bagian' => $modelData['nama_bagian'] ?? '-',
                    'jabatan' => $modelData['jabatan'] ?? '-',
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'gaji_perbulan' => $modelData['nominal_gaji'],
                    'gaji_perhari' => $modelData['gaji_perhari'],
                    'gaji_perjam' => $modelData['nominal_gaji'] / 173,
                ];

                // Simpan detail tunjangan
                foreach ($tunjanganList as $tunjangan) {
                    $tunjanganRekapRows[] = [
                        'id_karyawan' => $idKaryawan,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'id_tunjangan' => $tunjangan['id_tunjangan'],
                        'nama_tunjangan' => $tunjangan['nama_tunjangan'],
                        'jumlah' => $tunjangan['jumlah'],
                        'satuan' => $tunjangan['satuan'],
                        'nominal_final' => $tunjangan['nominal_final'],
                    ];
                }

                // Simpan detail potongan
                foreach ($potonganList as $potongan) {
                    $potonganRekapRows[] = [
                        'id_karyawan' => $idKaryawan,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'id_potongan' => $potongan['id_potongan'],
                        'nama_potongan' => $potongan['nama_potongan'],
                        'jumlah' => $potongan['jumlah'],
                        'satuan' => $potongan['satuan'],
                        'nominal_final' => $potongan['nominal_final'],
                    ];
                }


                if (isset($kasbonList['data_terakhir']) && is_array($kasbonList['data_terakhir'])) {
                    $sisaKasbon = isset($kasbonList['data_terakhir']['sisa_kasbon'])
                        ? floatval($kasbonList['data_terakhir']['sisa_kasbon'])
                        : 0;

                    if ($pendingKasbon) {
                        $angsuran = 0;
                        $sisaKasbonBaru = $sisaKasbon;
                    } else {
                        $angsuran = isset($kasbonList['data_terakhir']['angsuran'])
                            ? floatval($kasbonList['data_terakhir']['angsuran'])
                            : 0;
                        $sisaKasbonBaru = max(0, $sisaKasbon - $angsuran);
                    }


                    $row = [
                        'id_karyawan' =>  $idKaryawan,
                        'id_kasbon' => $kasbonList['data_terakhir']['id_kasbon'] ?? null,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'jumlah_kasbon' => $kasbonList['data_terakhir']['jumlah_kasbon'] ?? 0,
                        'jumlah_potong' => $angsuran,
                        'tanggal_potong' => date('Y-m-d'),
                        'angsuran' => $angsuran,
                        'status_potongan' => $kasbonList['data_terakhir']['status_potongan'] ?? 0,
                        'sisa_kasbon' => $sisaKasbonBaru,
                        'created_at' => time(),
                        'autodebt' => 1,
                        'deskripsi' => 'Pembayaran Kasbon',
                    ];



                    $kasbonRekapRows[] = $row;
                }

                // Simpan detail dinas
                foreach ($dinasList as $dinas) {

                    $dinasDetailRows[] = [
                        'id_karyawan' => $idKaryawan,
                        'nama' => $modelData['nama'],
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'keterangan' => $dinas['keterangan_perjalanan'],
                        'biaya' => $dinas['biaya_yang_disetujui'] ?? 0,
                    ];
                }

                // Simpan detail lembur ke tabel lembur_gaji
                foreach ($jam_lembur as $lembur) {
                    $lemburGajiRows[] = [
                        'id_karyawan' => $idKaryawan,
                        'nama' => $modelData['nama'],
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'tanggal' => $lembur['tanggal'],
                        'hitungan_jam' => $lembur['hitungan_jam'] ?? 0,
                    ];
                }
            }


            // Setelah loop, simpan ke semua tabel
            if (!empty($rows)) {
                Yii::$app->db->createCommand()
                    ->batchInsert('{{%transaksi_gaji}}', [
                        'id_karyawan',
                        'nama',
                        'id_bagian',
                        'nama_bagian',
                        'jabatan',
                        'bulan',
                        'tahun',
                        'tanggal_awal',
                        'tanggal_akhir',
                        'total_absensi',
                        'terlambat',
                        'nominal_gaji',
                        'potongan_karyawan',
                        'potongan_kasbon',
                        'tunjangan_karyawan',
                        'gaji_perhari',
                        'jam_lembur',
                        'total_pendapatan_lembur',
                        'potongan_terlambat',
                        'total_alfa_range',
                        'potongan_absensi',
                        'dinas_luar_belum_terbayar',
                        'created_at',
                        'updated_at',
                        'created_by',
                        'updated_by',
                        'status',
                        'hari_kerja_efektif',
                        'nama_bank',
                        'nomer_rekening',
                        'pendapatan_lainnya',
                        'potongan_lainnya',
                        'gaji_diterima',
                        'status_pekerjaan'
                    ], $rows)
                    ->execute();
            }

            // Simpan rekap gaji karyawan per transaksi
            if (!empty($rekapGajiRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%rekap_gaji_karyawan_pertransaksi}}', ['in', 'id_karyawan', array_column($models, 'id_karyawan')])
                    ->execute();

                Yii::$app->db->createCommand()
                    ->batchInsert('{{%rekap_gaji_karyawan_pertransaksi}}', [
                        'id_karyawan',
                        'nama_karyawan',
                        'nama_bagian',
                        'jabatan',
                        'bulan',
                        'tahun',
                        'gaji_perbulan',
                        'gaji_perhari',
                        'gaji_perjam',
                    ], $rekapGajiRows)->execute();
            }

            // Simpan rekap terlambat
            if (!empty($rekapTerlambatRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%rekap_terlambat_transaksi_gaji}}', ['in', 'id_karyawan', array_column($models, 'id_karyawan')])
                    ->execute();

                Yii::$app->db->createCommand()
                    ->batchInsert('{{%rekap_terlambat_transaksi_gaji}}', [
                        'id_karyawan',
                        'bulan',
                        'tahun',
                        'nama',
                        'tanggal',
                        'lama_terlambat',
                        'potongan_permenit',
                    ], $rekapTerlambatRows)->execute();
            }

            // Simpan tunjangan
            if (!empty($tunjanganRekapRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%tunjangan_rekap_gaji}}', ['in', 'id_karyawan', array_column($models, 'id_karyawan')])
                    ->execute();

                Yii::$app->db->createCommand()
                    ->batchInsert('{{%tunjangan_rekap_gaji}}', [
                        'id_karyawan',
                        'bulan',
                        'tahun',
                        'id_tunjangan',
                        'nama_tunjangan',
                        'jumlah',
                        'satuan',
                        'nominal_final'
                    ], $tunjanganRekapRows)->execute();
            }

            // Simpan potongan
            if (!empty($potonganRekapRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%potongan_rekap_gaji}}', ['in', 'id_karyawan', array_column($models, 'id_karyawan')])
                    ->execute();

                Yii::$app->db->createCommand()
                    ->batchInsert('{{%potongan_rekap_gaji}}', [
                        'id_karyawan',
                        'bulan',
                        'tahun',
                        'id_potongan',
                        'nama_potongan',
                        'jumlah',
                        'satuan',
                        'nominal_final'
                    ], $potonganRekapRows)->execute();
            }


            if (!empty($kasbonRekapRows)) {

                // Pastikan urutan kolom sama dengan yang akan diinsert
                $rowsToInsert = [];
                foreach ($kasbonRekapRows as $row) {
                    $rowsToInsert[] = [
                        $row['id_karyawan'],
                        $row['id_kasbon'],
                        $row['bulan'],
                        $row['tahun'],
                        $row['jumlah_potong'],
                        $row['tanggal_potong'],
                        $row['angsuran'],
                        $row['status_potongan'],
                        $row['sisa_kasbon'],
                        $row['created_at'],
                        $row['autodebt'],
                        $row['deskripsi'],
                        $row['jumlah_kasbon'],
                    ];
                }

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    Yii::$app->db->createCommand()
                        ->batchInsert('{{%pembayaran_kasbon}}', [
                            'id_karyawan',
                            'id_kasbon',
                            'bulan',
                            'tahun',
                            'jumlah_potong',
                            'tanggal_potong',
                            'angsuran',
                            'status_potongan',
                            'sisa_kasbon',
                            'created_at',
                            'autodebt',
                            'deskripsi',
                            'jumlah_kasbon',
                        ], $rowsToInsert)
                        ->execute();

                    // Update status kasbon jika lunas
                    $updatedKasbon = [];
                    foreach ($kasbonRekapRows as $row) {
                        if ($row['sisa_kasbon'] <= 0 && !in_array($row['id_kasbon'], $updatedKasbon)) {
                            Yii::$app->db->createCommand()
                                ->update(
                                    '{{%pembayaran_kasbon}}',
                                    ['status_potongan' => 1],
                                    ['id_kasbon' => $row['id_kasbon']]
                                )->execute();
                            $updatedKasbon[] = $row['id_kasbon'];
                        }
                    }

                    $transaction->commit();
                    Yii::info('Berhasil menambahkan ' . count($kasbonRekapRows) . ' pembayaran kasbon baru untuk karyawan ID: ' . $idKaryawan, 'kasbon');
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::error('Gagal menambahkan pembayaran kasbon untuk karyawan ID: ' . $idKaryawan . ' - Error: ' . $e->getMessage(), 'kasbon');
                    throw $e;
                }
            }


            // Simpan dinas
            if (!empty($dinasDetailRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%dinas_detail_gaji}}', ['in', 'id_karyawan', array_column($models, 'id_karyawan')])
                    ->execute();

                Yii::$app->db->createCommand()
                    ->batchInsert('{{%dinas_detail_gaji}}', [
                        'id_karyawan',
                        'nama',
                        'bulan',
                        'tahun',
                        'keterangan',
                        'biaya',
                    ], $dinasDetailRows)->execute();
            }

            // Simpan lembur
            if (!empty($lemburGajiRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%lembur_gaji}}', ['in', 'id_karyawan', array_column($models, 'id_karyawan')])
                    ->execute();

                Yii::$app->db->createCommand()
                    ->batchInsert('{{%lembur_gaji}}', [
                        'id_karyawan',
                        'nama',
                        'bulan',
                        'tahun',
                        'tanggal',
                        'hitungan_jam',
                    ], $lemburGajiRows)->execute();
            }

            if (!empty($potonganAlfaWfhRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%potongan_alfa_and_wfh_penggajian}}', ['in', 'id_karyawan', array_column($models, 'id_karyawan')])
                    ->execute();

                Yii::$app->db->createCommand()
                    ->batchInsert('{{%potongan_alfa_and_wfh_penggajian}}', [
                        'id_karyawan',
                        'nama',
                        'bulan',
                        'tahun',
                        'jumlah_alfa',
                        'total_potongan_alfa',
                        'jumlah_wfh',
                        'persen_potongan_wfh',
                        'total_potongan_wfh',
                        'gaji_perhari',
                    ], $potonganAlfaWfhRows)->execute();
            }

            Yii::$app->session->setFlash('success', "Generate gaji berhasil untuk " . count($rows) . " karyawan.");
        } else {
            Yii::$app->session->setFlash('info', "Tidak ada data untuk digenerate.");
        }

        return $this->redirect(['index', 'TransaksiGaji' => ['id_karyawan' => '', 'bulan' => $bulan, 'tahun' => $tahun]]);
    }
    public function actionGenerateGajiOne($id_karyawan)
    {
        $params = Yii::$app->request->get();
        $bulan = $params['bulan'] ?? date('m');
        $tahun = $params['tahun'] ?? date('Y');

        // 1. Hapus data lama untuk karyawan tertentu
        $this->deleteGajiData($id_karyawan, $bulan, $tahun);

        // 2. Ambil data karyawan
        $searchModel = new TransaksiGajiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $id_karyawan, $bulan, $tahun);
        $models = $dataProvider->getModels();

        if (empty($models)) {
            Yii::$app->session->setFlash('warning', "Tidak ada data perhitungan gaji untuk karyawan ID {$id_karyawan}.");
            return $this->redirect(['index', 'TransaksiGaji' => ['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun]]);
        }


        $modelData = reset($models);;
        $now = date('Y-m-d H:i:s');
        $userId = Yii::$app->user->id;

        // Inisialisasi array untuk batch insert
        $rows = [];
        $tunjanganRekapRows = [];
        $potonganRekapRows = [];
        $dinasDetailRows = [];
        $lemburGajiRows = [];
        $rekapGajiRows = [];
        $rekapTerlambatRows = [];
        $potonganAlfaWfhRows = [];
        $kasbonRekapRows = [];

        // 3. Ambil data komponen gaji
        $nominalGaji = $modelData['nominal_gaji'];
        $tunjanganList = TunjanganDetail::getTunjanganKaryawan($id_karyawan, $nominalGaji);
        $potonganList = PotonganDetail::getPotonganKaryawan($id_karyawan, $nominalGaji);
        $kasbonList = $searchModel->getKasbonKaryawan($id_karyawan);

        $periode_gaji = PeriodeGaji::findOne(['bulan' => $bulan, 'tahun' => $tahun]);
        $tanggal_awal_periode = $periode_gaji->tanggal_awal;
        $tanggal_akhir_periode = $periode_gaji->tanggal_akhir;

        // Ambil data terlambat
        $terlambatData = $this->actionGetPotonganTerlambatKaryawan($id_karyawan);
        $filteredTerlambat = $terlambatData['filteredTerlambat'];
        $potonganPerMenit = $terlambatData['potonganPerMenit'];

        // Ambil data lembur
        $lemburData = $this->getLemburData($id_karyawan, $bulan, $tahun, $tanggal_awal_periode, $tanggal_akhir_periode);
        $jam_lembur = $lemburData['jam_lembur'];
        $total_hitungan_jam = $lemburData['total_hitungan_jam'];
        $total_pendapatan_lembur = $lemburData['total_pendapatan_lembur'];

        // Cek pending kasbon
        $pendingKasbon = null;
        if (isset($kasbonList['data_terakhir']['id_kasbon'])) {
            $pendingKasbon = PendingKasbon::find()
                ->where([
                    'id_karyawan' => $id_karyawan,
                    'id_kasbon' => $kasbonList['data_terakhir']['id_kasbon'],
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ])
                ->one();
        }

        // Ambil data dinas
        $dinasList = PengajuanDinas::find()
            ->where(['id_karyawan' => $id_karyawan, 'status' => 1, 'status_dibayar' => 0])
            ->asArray()
            ->all();

        // Ambil data potongan absensi (alfa & WFH)
        $potonganAbsensiData = $this->actionGetPotonganAbsensiKaryawan(
            $id_karyawan,
            $modelData['total_alfa_range'],
            $modelData['gaji_perhari'],
            $modelData['jumlah_wfh'] ?? 0
        );

        // 4. Siapkan data untuk batch insert
        // Data transaksi_gaji
        $rows[] = [
            'id_karyawan' => $modelData['id_karyawan'],
            'nama' => $modelData['nama'],
            'id_bagian' => $modelData['id_bagian'],
            'nama_bagian' => $modelData['nama_bagian'] ?? '-',
            'jabatan' => $modelData['jabatan'],
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tanggal_awal' => $modelData['tanggal_awal'],
            'tanggal_akhir' => $modelData['tanggal_akhir'],
            'total_absensi' => $modelData['total_absensi'],
            'terlambat' => $modelData['terlambat'],
            'nominal_gaji' => $modelData['nominal_gaji'],
            'potongan_karyawan' => $modelData['potongan_karyawan'],
            'potongan_kasbon' => $pendingKasbon ? 0 : ($kasbonList['data_terakhir']['angsuran'] ?? 0),
            'tunjangan_karyawan' => $modelData['tunjangan_karyawan'],
            'gaji_perhari' => $modelData['gaji_perhari'],
            'jam_lembur' => $total_hitungan_jam,
            'total_pendapatan_lembur' => $total_pendapatan_lembur,
            'potongan_terlambat' => $modelData['potongan_terlambat'],
            'total_alfa_range' => $modelData['total_alfa_range'],
            'potongan_absensi' => $modelData['potongan_absensi'],
            'dinas_luar_belum_terbayar' => $modelData['dinas_luar_belum_terbayar'],
            'created_at' => $now,
            'updated_at' => $now,
            'created_by' => $userId,
            'updated_by' => $userId,
            'status' => 1,
            'hari_kerja_efektif' => $modelData['hari_kerja_efektif'],
            'nama_bank' =>  $modelData['nama_bank'],
            'nomer_rekening' => $modelData['nomer_rekening'],
            'pendapatan_lainnya' => $modelData['pendapatan_lainnya'],
            'potongan_lainnya' => $modelData['potongan_lainnya'],
            'gaji_diterima' => $modelData['gaji_bersih'],
            'status_pekerjaan' => $modelData['status_pekerjaan']
        ];



        // Data rekap gaji
        $rekapGajiRows[] = [
            'id_karyawan' => $id_karyawan,
            'nama_karyawan' => $modelData['nama'] ?? '-',
            'nama_bagian' => $modelData['nama_bagian'] ?? '-',
            'jabatan' => $modelData['jabatan'] ?? '-',
            'bulan' => $bulan,
            'tahun' => $tahun,
            'gaji_perbulan' => $modelData['nominal_gaji'],
            'gaji_perhari' => $modelData['gaji_perhari'],
            'gaji_perjam' => $modelData['nominal_gaji'] / 173,
        ];

        // Data potongan alfa & WFH
        if ($potonganAbsensiData['success']) {
            $potonganAlfaWfhRows[] = [
                'id_karyawan' => $id_karyawan,
                'nama' => $modelData['nama'],
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah_alfa' => $potonganAbsensiData['total_alfa_range'],
                'total_potongan_alfa' => $potonganAbsensiData['total_alfa_range'] * $potonganAbsensiData['gaji_perhari'],
                'jumlah_wfh' => $potonganAbsensiData['jumlah_wfh'],
                'persen_potongan_wfh' => $potonganAbsensiData['potonganwfhsehari'],
                'total_potongan_wfh' => $potonganAbsensiData['jumlah_wfh'] * ($potonganAbsensiData['potonganwfhsehari'] / 100) * $potonganAbsensiData['gaji_perhari'],
                'gaji_perhari' => $potonganAbsensiData['gaji_perhari'],
            ];
        }

        // Data terlambat
        foreach ($filteredTerlambat as $terlambat) {
            $rekapTerlambatRows[] = [
                'id_karyawan' => $id_karyawan,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'nama' => $modelData['nama'],
                'tanggal' => $terlambat['tanggal'],
                'lama_terlambat' => $terlambat['terlambat'],
                'potongan_permenit' => $potonganPerMenit,
            ];
        }

        // Data tunjangan
        foreach ($tunjanganList as $tunjangan) {
            $tunjanganRekapRows[] = [
                'id_karyawan' => $id_karyawan,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'id_tunjangan' => $tunjangan['id_tunjangan'],
                'nama_tunjangan' => $tunjangan['nama_tunjangan'],
                'jumlah' => $tunjangan['jumlah'],
                'satuan' => $tunjangan['satuan'],
                'nominal_final' => $tunjangan['nominal_final'],
            ];
        }

        // Data potongan
        foreach ($potonganList as $potongan) {
            $potonganRekapRows[] = [
                'id_karyawan' => $id_karyawan,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'id_potongan' => $potongan['id_potongan'],
                'nama_potongan' => $potongan['nama_potongan'],
                'jumlah' => $potongan['jumlah'],
                'satuan' => $potongan['satuan'],
                'nominal_final' => $potongan['nominal_final'],
            ];
        }

        // Data kasbon
        if (isset($kasbonList['data_terakhir']) && is_array($kasbonList['data_terakhir'])) {
            $sisaKasbon = floatval($kasbonList['data_terakhir']['sisa_kasbon'] ?? 0);
            $angsuran = $pendingKasbon ? 0 : floatval($kasbonList['data_terakhir']['angsuran'] ?? 0);
            $sisaKasbonBaru = max(0, $sisaKasbon - $angsuran);

            $kasbonRekapRows[] = [
                'id_karyawan' => $id_karyawan,
                'id_kasbon' => $kasbonList['data_terakhir']['id_kasbon'] ?? null,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah_kasbon' => $kasbonList['data_terakhir']['jumlah_kasbon'] ?? 0,
                'jumlah_potong' => $angsuran,
                'tanggal_potong' => date('Y-m-d'),
                'angsuran' => $angsuran,
                'status_potongan' => $kasbonList['data_terakhir']['status_potongan'] ?? 0,
                'sisa_kasbon' => $sisaKasbonBaru,
                'created_at' => time(),
                'autodebt' => 1,
                'deskripsi' => 'Pembayaran Kasbon',
            ];
        }

        // Data dinas
        foreach ($dinasList as $dinas) {


            $dinasDetailRows[] = [
                'id_karyawan' => $id_karyawan,
                'nama' => $modelData['nama'],
                'bulan' => $bulan,
                'tahun' => $tahun,
                'keterangan' => $dinas['keterangan_perjalanan'],
                'biaya' => $dinas['biaya_yang_disetujui'] ?? 0,
            ];
        }

        // Data lembur
        foreach ($jam_lembur as $lembur) {
            $lemburGajiRows[] = [
                'id_karyawan' => $id_karyawan,
                'nama' => $modelData['nama'],
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal' => $lembur['tanggal'],
                'hitungan_jam' => $lembur['hitungan_jam'] ?? 0,
            ];
        }

        // 5. Simpan data ke semua tabel menggunakan batch insert
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Simpan transaksi_gaji
            if (!empty($rows)) {
                Yii::$app->db->createCommand()
                    ->batchInsert('{{%transaksi_gaji}}', [
                        'id_karyawan',
                        'nama',
                        'id_bagian',
                        'nama_bagian',
                        'jabatan',
                        'bulan',
                        'tahun',
                        'tanggal_awal',
                        'tanggal_akhir',
                        'total_absensi',
                        'terlambat',
                        'nominal_gaji',
                        'potongan_karyawan',
                        'potongan_kasbon',
                        'tunjangan_karyawan',
                        'gaji_perhari',
                        'jam_lembur',
                        'total_pendapatan_lembur',
                        'potongan_terlambat',
                        'total_alfa_range',
                        'potongan_absensi',
                        'dinas_luar_belum_terbayar',
                        'created_at',
                        'updated_at',
                        'created_by',
                        'updated_by',
                        'status',
                        'hari_kerja_efektif',
                        'nama_bank',
                        'nomer_rekening',
                        'pendapatan_lainnya',
                        'potongan_lainnya',
                        'gaji_diterima',
                        'status_pekerjaan',
                    ], $rows)
                    ->execute();
            }

            // Simpan rekap gaji
            if (!empty($rekapGajiRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%rekap_gaji_karyawan_pertransaksi}}', ['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun])
                    ->execute();
                Yii::$app->db->createCommand()
                    ->batchInsert('{{%rekap_gaji_karyawan_pertransaksi}}', [
                        'id_karyawan',
                        'nama_karyawan',
                        'nama_bagian',
                        'jabatan',
                        'bulan',
                        'tahun',
                        'gaji_perbulan',
                        'gaji_perhari',
                        'gaji_perjam',
                    ], $rekapGajiRows)
                    ->execute();
            }

            // Simpan potongan alfa & WFH
            if (!empty($potonganAlfaWfhRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%potongan_alfa_and_wfh_penggajian}}', ['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun])
                    ->execute();
                Yii::$app->db->createCommand()
                    ->batchInsert('{{%potongan_alfa_and_wfh_penggajian}}', [
                        'id_karyawan',
                        'nama',
                        'bulan',
                        'tahun',
                        'jumlah_alfa',
                        'total_potongan_alfa',
                        'jumlah_wfh',
                        'persen_potongan_wfh',
                        'total_potongan_wfh',
                        'gaji_perhari',
                    ], $potonganAlfaWfhRows)
                    ->execute();
            }

            // Simpan terlambat
            if (!empty($rekapTerlambatRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%rekap_terlambat_transaksi_gaji}}', ['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun])
                    ->execute();
                Yii::$app->db->createCommand()
                    ->batchInsert('{{%rekap_terlambat_transaksi_gaji}}', [
                        'id_karyawan',
                        'bulan',
                        'tahun',
                        'nama',
                        'tanggal',
                        'lama_terlambat',
                        'potongan_permenit',
                    ], $rekapTerlambatRows)
                    ->execute();
            }

            // Simpan tunjangan
            if (!empty($tunjanganRekapRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%tunjangan_rekap_gaji}}', ['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun])
                    ->execute();
                Yii::$app->db->createCommand()
                    ->batchInsert('{{%tunjangan_rekap_gaji}}', [
                        'id_karyawan',
                        'bulan',
                        'tahun',
                        'id_tunjangan',
                        'nama_tunjangan',
                        'jumlah',
                        'satuan',
                        'nominal_final',
                    ], $tunjanganRekapRows)
                    ->execute();
            }

            // Simpan potongan
            if (!empty($potonganRekapRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%potongan_rekap_gaji}}', ['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun])
                    ->execute();
                Yii::$app->db->createCommand()
                    ->batchInsert('{{%potongan_rekap_gaji}}', [
                        'id_karyawan',
                        'bulan',
                        'tahun',
                        'id_potongan',
                        'nama_potongan',
                        'jumlah',
                        'satuan',
                        'nominal_final',
                    ], $potonganRekapRows)
                    ->execute();
            }

            // Simpan kasbon
            if (!empty($kasbonRekapRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%pembayaran_kasbon}}', ['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun])
                    ->execute();
                Yii::$app->db->createCommand()
                    ->batchInsert('{{%pembayaran_kasbon}}', [
                        'id_karyawan',
                        'id_kasbon',
                        'bulan',
                        'tahun',
                        'jumlah_kasbon',
                        'jumlah_potong',
                        'tanggal_potong',
                        'angsuran',
                        'status_potongan',
                        'sisa_kasbon',
                        'created_at',
                        'autodebt',
                        'deskripsi',
                    ], $kasbonRekapRows)
                    ->execute();

                // Update status kasbon jika lunas
                foreach ($kasbonRekapRows as $row) {
                    if ($row['sisa_kasbon'] <= 0) {
                        Yii::$app->db->createCommand()
                            ->update('{{%pembayaran_kasbon}}', ['status_potongan' => 1], ['id_kasbon' => $row['id_kasbon']])
                            ->execute();
                    }
                }
            }

            // Simpan dinas
            if (!empty($dinasDetailRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%dinas_detail_gaji}}', ['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun])
                    ->execute();
                Yii::$app->db->createCommand()
                    ->batchInsert('{{%dinas_detail_gaji}}', [
                        'id_karyawan',
                        'nama',
                        'bulan',
                        'tahun',
                        'keterangan',
                        'biaya',
                    ], $dinasDetailRows)
                    ->execute();
            }

            // Simpan lembur
            if (!empty($lemburGajiRows)) {
                Yii::$app->db->createCommand()
                    ->delete('{{%lembur_gaji}}', ['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun])
                    ->execute();
                Yii::$app->db->createCommand()
                    ->batchInsert('{{%lembur_gaji}}', [
                        'id_karyawan',
                        'nama',
                        'bulan',
                        'tahun',
                        'tanggal',
                        'hitungan_jam',
                    ], $lemburGajiRows)
                    ->execute();
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', "Gaji untuk karyawan ID {$id_karyawan} berhasil digenerate.");
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error("Gagal generate gaji untuk karyawan ID {$id_karyawan}: " . $e->getMessage(), 'gaji');
            Yii::$app->session->setFlash('error', "Gagal generate gaji: " . $e->getMessage());
        }

        return $this->redirect(['index', 'TransaksiGaji' => ['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun]]);
    }

    // ~ ========Delete=================
    public function actionDeleteAllData()
    {
        if (Yii::$app->request->isPost) {
            $bulan = Yii::$app->request->post('bulan');
            $tahun = Yii::$app->request->post('tahun');
            $this->removeAll($bulan, $tahun);
            return $this->redirect(['index', 'TransaksiGaji' => ['id_karyawan' => '', 'bulan' => $bulan, 'tahun' => $tahun]]);
        }
    }
    public function actionDeleterow($id_karyawan, $bulan, $tahun)
    {
        $this->deleteGajiData($id_karyawan, $bulan, $tahun);
        return $this->redirect(['index', 'TransaksiGaji' => ['id_karyawan' => '', 'bulan' => $bulan, 'tahun' => $tahun]]);
    }






    // * =============Slip Gaji & Email========================
    public function actionEmailGaji($id_transaksi_gaji, $id_karyawan)
    {
        $karyawan = Karyawan::find()
            ->select(['id_karyawan', 'email', 'nama'])
            ->where(['id_karyawan' => $id_karyawan])
            ->asArray()
            ->one();

        if (!$karyawan) {
            throw new \yii\web\NotFoundHttpException("Data karyawan tidak ditemukan.");
        }

        $transaksiData = TransaksiGaji::find()
            ->where(['id_transaksi_gaji' => $id_transaksi_gaji, 'id_karyawan' => $id_karyawan])
            ->asArray()
            ->one();

        if (!$transaksiData) {
            throw new \yii\web\NotFoundHttpException("Data slip gaji tidak ditemukan.");
        }

        // === 1. Render PDF content ===
        $content = $this->renderPartial('_slip_gaji', [
            'transaksiData' => $transaksiData,
        ]);

        // === 2. Generate PDF ke file sementara ===
        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_CORE,
            'format' => \kartik\mpdf\Pdf::FORMAT_A4,
            'orientation' => \kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            'destination' => \kartik\mpdf\Pdf::DEST_FILE,
            'filename' => Yii::getAlias('@runtime') . '/Slip_Gaji_' . $karyawan['nama'] . '.pdf',
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '
            table {width: 100%; border-collapse: collapse; font-size: 12px;}
            th, td {border: 1px solid #ddd; padding: 0px; text-align: left;}
            th {background-color: #f2f2f2; font-weight: bold;}
            .text-right {text-align: right;}
            .header {text-align: center; margin-bottom: 20px;}
            .title {font-size: 16px; font-weight: bold;}
        ',
        ]);

        $pdf->render(); // Generate dan simpan file PDF

        $filePath = Yii::getAlias('@runtime') . '/Slip_Gaji_' . $karyawan['nama'] . '.pdf';

        // === 3. Kirim Email ===
        $bodyMessage = $this->renderPartial('@backend/views/transaksi-gaji/email', [
            'karyawan' => $karyawan,
            'transaksiData' => $transaksiData,
        ]);


        $mail = Yii::$app->mailer->compose()
            ->setTo($karyawan['email'])
            ->setSubject('Slip Gaji Bulan ' . date('F Y'))
            ->setHtmlBody($bodyMessage)
            ->attach($filePath); // Lampirkan PDF



        if ($mail->send()) {
            Yii::$app->session->setFlash('success', 'Slip gaji berhasil dikirim ke ' . $karyawan['email']);
        } else {
            Yii::$app->session->setFlash('error', 'Gagal mengirim slip gaji ke ' . $karyawan['email']);
        }

        // === 4. (Opsional) Hapus file PDF setelah terkirim ===
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        return $this->redirect(['index']);
    }
    public function actionPendingPembayaran()
    {
        $request = Yii::$app->request;

        $bulan = $request->post('bulan');
        $tahun = $request->post('tahun');
        $idKaryawan = $request->post('id_karyawan');
        $idKasbon = $request->post('id_kasbon');

        // Validasi data dasar
        if (empty($idKaryawan)) {
            Yii::$app->session->setFlash('error', 'Karyawan belum dipilih.');
            return $this->redirect(['index']);
        }

        // Cek apakah sudah ada pending kasbon di bulan & tahun tersebut (opsional)
        $existing = PendingKasbon::find()
            ->where([
                'id_karyawan' => $idKaryawan,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])
            ->one();

        if ($existing) {
            Yii::$app->session->setFlash('warning', "Data pending sudah ada untuk karyawan ID $idKaryawan bulan $bulan/$tahun.");
            return $this->redirect(['index']);
        }

        // Simpan data baru
        $model = new PendingKasbon();
        $model->id_karyawan = $idKaryawan;
        $model->bulan = $bulan;
        $model->tahun = $tahun;
        // isi id_kasbon kalau kamu punya logika untuk itu, contoh:
        $model->id_kasbon = $idKasbon;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', "Berhasil menandai pending pembayaran untuk karyawan ID $idKaryawan bulan $bulan/$tahun.");
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menyimpan data pending pembayaran.');
            Yii::error($model->errors, __METHOD__);
        }

        return $this->redirect(['index', 'TransaksiGaji' => ['id_karyawan' => '', 'bulan' => $bulan, 'tahun' => $tahun]]);
    }
    public function actionBatalPending()
    {
        $request = Yii::$app->request;

        $bulan = $request->post('bulan');
        $tahun = $request->post('tahun');
        $idKaryawan = $request->post('id_karyawan');
        $idKasbon = $request->post('id_kasbon');

        if (empty($idKaryawan) || empty($idKasbon)) {
            Yii::$app->session->setFlash('error', 'Data karyawan atau kasbon tidak lengkap.');
            return $this->redirect(['index']);
        }

        // Cek apakah data pending ada
        $model = PendingKasbon::find()
            ->where([
                'id_karyawan' => $idKaryawan,
                'id_kasbon' => $idKasbon,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ])
            ->one();

        if ($model) {
            // Jika ditemukan, hapus
            if ($model->delete()) {
                Yii::$app->session->setFlash('success', "Pending pembayaran untuk karyawan ID $idKaryawan bulan $bulan/$tahun telah dibatalkan.");
            } else {
                Yii::$app->session->setFlash('error', 'Gagal menghapus data pending.');
            }
        } else {
            // Jika tidak ada data, tetap lanjut tanpa error
            Yii::$app->session->setFlash('info', 'Tidak ada data pending yang ditemukan, tidak ada perubahan.');
        }

        return $this->redirect(['index', 'TransaksiGaji' => ['id_karyawan' => '', 'bulan' => $bulan, 'tahun' => $tahun]]);
    }

    public function actionSlipGajiPdf($id_transaksi_gaji, $id_karyawan)
    {
        // Ambil data transaksi gaji
        $transaksiData = TransaksiGaji::find()
            ->where(['id_transaksi_gaji' => $id_transaksi_gaji, 'id_karyawan' => $id_karyawan])
            ->asArray()
            ->one();



        if (!$transaksiData) {
            throw new \yii\web\NotFoundHttpException("Data slip gaji tidak ditemukan.");
        }

        // Render partial view untuk content PDF
        $content = $this->renderPartial('_slip_gaji', [
            'transaksiData' => $transaksiData
        ]);

        // Setup kartik\mpdf\Pdf component
        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_CORE,
            'format' => \kartik\mpdf\Pdf::FORMAT_A4, // lebih kecil untuk slip gaji
            'orientation' => \kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '
            table {width: 100%; border-collapse: collapse; font-size: 12px;}
            th, td {border: 1px solid #ddd; padding: 0px; text-align: left;}
            th {background-color: #f2f2f2; font-weight: bold;}
            .text-right {text-align: right;}
            .header {text-align: center; margin-bottom: 20px;}
            .title {font-size: 16px; font-weight: bold;}
        ',
            'options' => ['title' => 'Slip Gaji ' . $transaksiData['nama']],
            'methods' => []
        ]);

        return $pdf->render();
    }
    public function actionReport($bulan = null, $tahun = null)
    {
        $model = new TransaksiGaji();
        $bulan = $bulan ?? date('m');
        $tahun = $tahun ?? date('Y');

        $searchModel = new TransaksiGajiSearch();
        $periode_gaji = new PeriodeGaji();
        $karyawanID = null;
        $periode_gajiID = PeriodeGajiHelper::getPeriodeGajiBulanIni();

        /**
         * 1. Ambil semua data dari SEARCH
         * (data lengkap + gaji_bersih)
         */
        $allDataFromSearch = $searchModel
            ->search([], null, $bulan, $tahun, null, null, null)
            ->getModels();

        // Indexing berdasarkan id_karyawan
        $searchIndexed = [];
        foreach ($allDataFromSearch as $item) {
            if (($item['visibility'] ?? 1) == 1) {
                $searchIndexed[$item['id_karyawan']] = $item;
            }
        }

        /**
         * 2. Ambil data TRANSAKSI yang sudah exist
         */
        $existsDataTransaksi = TransaksiGaji::find()
            ->where(['bulan' => $bulan, 'tahun' => $tahun])
            ->asArray()
            ->all();

        /**
         * 3. Gabungkan (prioritas transaksi)
         */
        $finalData = [];

        foreach ($existsDataTransaksi as $transaksi) {
            $idKaryawan = $transaksi['id_karyawan'];

            $dataSearch = $searchIndexed[$idKaryawan] ?? [];

            $merged = array_merge($dataSearch, $transaksi);

            // ⬇️ TAMBAHKAN INI
            $merged['status_laporan'] = 'fix';

            $finalData[] = $merged;

            unset($searchIndexed[$idKaryawan]);
        }



        // sisa dari search (belum ada transaksi)
        foreach ($searchIndexed as $sisa) {
            // ⬇️ TAMBAHKAN INI
            $sisa['status_laporan'] = 'draft';

            $finalData[] = $sisa;
        }


        /**
         * 4. Render PDF
         */
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Pastikan $bulan berupa angka 1-12
        $bulanNama = $namaBulan[(int)$bulan] ?? 'Unknown';

        $content = $this->renderPartial('_report', [
            'finalData' => $finalData,
            'searchModel' => $searchModel,
            'periode_gaji' => $periode_gaji,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'model' => $model,
            'karyawanID' => $karyawanID,
            'periode_gajiID' => $periode_gajiID
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '...',
            'options' => ['title' => 'Laporan Transaksi Gaji'],
            'methods' => [
                'SetHeader' => ['Laporan Transaksi Gaji'],
                'SetFooter' => ['{PAGENO}'],
            ],
            'filename' => 'Report_Payroll_Profaskes_Periode_' . $bulanNama  . '-' . $tahun . '.pdf',
        ]);

        return $pdf->render();
    }





    // todo ===========================query
    protected function findModel($id_transaksi_gaji)
    {
        if (($model = TransaksiGaji::findOne(['id_transaksi_gaji' => $id_transaksi_gaji])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    // delete ONe
    private function deleteGajiData($id_karyawan, $bulan, $tahun)
    {
        // Delete TransaksiGaji dengan findOne (karena mungkin ada relasi)
        TransaksiGaji::findOne(['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun])?->delete();

        // Delete semua data dari tabel lainnya
        $tables = [
            TunjanganRekapGaji::class,
            PotonganRekapGaji::class,
            LemburGaji::class,
            DinasDetailGaji::class,
            RekapGajiKaryawanPertransaksi::class,
            PotonganAlfaAndWfhPenggajian::class
        ];

        foreach ($tables as $table) {
            $table::deleteAll(['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun]);
        }
        PembayaranKasbon::deleteAll(['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun, 'deskripsi' => 'Pembayaran Kasbon']);
        PendingKasbon::deleteAll(['id_karyawan' => $id_karyawan, 'bulan' => $bulan, 'tahun' => $tahun]);
    }
    // delete All
    public function removeAll($bulan, $tahun)
    {
        TransaksiGaji::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        TunjanganRekapGaji::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        PotonganRekapGaji::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        LemburGaji::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        DinasDetailGaji::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        RekapGajiKaryawanPertransaksi::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        PotonganAlfaAndWfhPenggajian::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        PembayaranKasbon::deleteAll(['bulan' => $bulan, 'tahun' => $tahun, 'deskripsi' => 'Pembayaran Kasbon']);
        return;
    }
    public function actionGetTunjanganKaryawan($id_karyawan)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $oldTransaksi = TransaksiGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
        if ($oldTransaksi) {
            $oldTunjangan = TunjanganRekapGaji::find()->where(['id_karyawan' => $id_karyawan, 'bulan' => $oldTransaksi->bulan, 'tahun' => $oldTransaksi->tahun])->asArray()->all();
        }
        $gajiKaryawan = MasterGaji::find()->where(['id_karyawan' => $id_karyawan])->one();

        $nominalGaji = $gajiKaryawan ? $gajiKaryawan->nominal_gaji : 0;
        try {

            if (isset($oldTunjangan) && $oldTunjangan) {
                return [
                    'success' => true,
                    'nominal_gaji' => $nominalGaji,
                    'data' => $oldTunjangan
                ];
            } else {

                $tunjanganData = TunjanganDetail::getTunjanganKaryawan($id_karyawan, $nominalGaji);
                return [
                    'success' => true,
                    'nominal_gaji' => $nominalGaji,
                    'data' => $tunjanganData
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function actionGetPotonganKaryawan($id_karyawan)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $oldTransaksi = TransaksiGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
        if ($oldTransaksi) {
            $oldPotongan = PotonganRekapGaji::find()->where(['id_karyawan' => $id_karyawan, 'bulan' => $oldTransaksi->bulan, 'tahun' => $oldTransaksi->tahun])->asArray()->all();
        }
        $gajiKaryawan = MasterGaji::find()->where(['id_karyawan' => $id_karyawan])->one();

        $nominalGaji = $gajiKaryawan ? $gajiKaryawan->nominal_gaji : 0;



        try {

            if (isset($oldPotongan) && $oldPotongan) {
                return [
                    'success' => true,
                    'nominal_gaji' => $nominalGaji,
                    'data' => $oldPotongan
                ];
            } else {
                $potonganData = PotonganDetail::getPotonganKaryawan($id_karyawan, $nominalGaji);

                return [
                    'success' => true,
                    'nominal_gaji' => $nominalGaji,
                    'data' => $potonganData
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    public function actionGetPotonganTerlambatKaryawan($id_karyawan)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $oldTransaksi = TransaksiGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
        $oldPotongan = [];

        if ($oldTransaksi) {
            $oldPotongan = RekapTerlambatTransaksiGaji::find()
                ->where(['id_karyawan' => $id_karyawan, 'bulan' => $oldTransaksi->bulan, 'tahun' => $oldTransaksi->tahun])
                ->asArray()
                ->all();
        }


        // Jika ada data oldPotongan, langsung return dengan format yang diinginkan
        if (!empty($oldPotongan)) {
            $gajiKaryawan = MasterGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
            $nominalGaji = $gajiKaryawan ? $gajiKaryawan->nominal_gaji : 0;

            // Hitung gaji per menit
            $gajiPerMenit = ($nominalGaji / 173) / 60;
            $gajiPerMenitFloat = round($gajiPerMenit, 2);

            // Konversi data dari database ke format yang diinginkan
            $filteredTerlambat = [];
            $totalPotongan = 0;
            $totalLamaTerlambatMenit = 0; // Ubah ke float untuk menghindari precision loss

            foreach ($oldPotongan as $terlambat) {
                // Format data untuk filteredTerlambat
                $filteredTerlambat[] = [
                    'tanggal' => $terlambat['tanggal'],
                    'terlambat' => $terlambat['lama_terlambat']
                ];

                // Hitung total menit terlambat dengan presisi float
                list($jam, $menit, $detik) = explode(':', $terlambat['lama_terlambat']);
                $totalMenit = ((float)$jam * 60) + (float)$menit + ((float)$detik / 60);
                $totalPotongan += $totalMenit * (float)$terlambat['potongan_permenit'];

                // Hitung total lama terlambat dalam menit (float)
                $totalLamaTerlambatMenit += $totalMenit;
            }

            // Format total lama terlambat ke format HH:MM dengan pembulatan yang tepat
            $totalJam = floor($totalLamaTerlambatMenit / 60);
            $sisaMenit = $totalLamaTerlambatMenit - ($totalJam * 60);
            $totalMenitBulat = round($sisaMenit);

            // Handle jika menit >= 60 setelah pembulatan
            if ($totalMenitBulat >= 60) {
                $totalJam += 1;
                $totalMenitBulat = $totalMenitBulat - 60;
            }

            $lamaTerlambatFormatted = $totalJam . ":" . str_pad($totalMenitBulat, 2, '0', STR_PAD_LEFT);
            return [
                'filteredTerlambat' => $filteredTerlambat,
                'potonganPerMenit' => $gajiPerMenitFloat,
                'potonganSemuaTerlambat' => round($totalPotongan, 2),
                'lama_terlambat' => $lamaTerlambatFormatted
            ];
        }

        // Jika tidak ada data oldPotongan, lanjut dengan proses biasa
        $searchModel = new TransaksiGajiSearch();
        $allDataFromSearch = $searchModel->search($this->request->queryParams, $id_karyawan)->getModels()[0];

        $gajiKaryawan = MasterGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
        $nominalGaji = $gajiKaryawan ? $gajiKaryawan->nominal_gaji : 0;
        $gajiPerMenit = ($nominalGaji / 173) / 60;

        // Bulatkan ke 2 angka di belakang koma sebagai float
        $gajiPerMenitFloat = round($gajiPerMenit, 2);
        $getToleranceTerlambat = MasterKode::findOne(['nama_group' => Yii::$app->params['toleransi-keterlambatan']])['nama_kode'];
        $filteredTerlambat = [];

        foreach ($allDataFromSearch['terlambat_with_date'] as $data) {
            list($jam, $menit, $detik) = explode(':', $data['terlambat']);
            $totalDetik = ((float)$jam * 3600) + ((float)$menit * 60) + (float)$detik;

            if ($totalDetik > ((int)$getToleranceTerlambat * 60)) {
                $filteredTerlambat[] = $data;
            }
        }

        return [
            'filteredTerlambat' => $filteredTerlambat,
            'potonganPerMenit' => $gajiPerMenitFloat,
            'potonganSemuaTerlambat' => round($allDataFromSearch['potongan_terlambat'], 2),
            'lama_terlambat' => $allDataFromSearch['terlambat']
        ];
    }
    public function actionGetPotonganAbsensiKaryawan($id_karyawan, $total_alfa_range, $gaji_perhari, $jumlah_wfh)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $oldTransaksi = TransaksiGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
            $gajiKaryawan = MasterGaji::find()->where(['id_karyawan' => $id_karyawan])->one();

            if (!$gajiKaryawan) {
                throw new \Exception('Data gaji karyawan tidak ditemukan');
            }


            if ($oldTransaksi) {
                $oldPotongan = PotonganAlfaAndWfhPenggajian::find()
                    ->where(['id_karyawan' => $id_karyawan, 'bulan' => $oldTransaksi->bulan, 'tahun' => $oldTransaksi->tahun])
                    ->asArray()
                    ->one();



                if ($oldPotongan) {
                    return [
                        'success' => true,
                        'nominal_gaji' => $gajiKaryawan['nominal_gaji'], // konversi dari gaji_perhari ke nominal_gaji
                        'total_alfa_range' => (int)$oldPotongan['jumlah_alfa'],
                        'potongan_per_alfa' =>  $oldPotongan['gaji_perhari'],
                        'total_potongan_absensi' =>  $oldPotongan['total_potongan_alfa'],
                        'jumlah_wfh' => $oldPotongan['jumlah_wfh'],
                        'potonganwfhsehari' => $oldPotongan['persen_potongan_wfh'],
                        'gaji_perhari' => $oldPotongan['gaji_perhari']
                    ];
                }
            }


            $nominalGaji = $gajiKaryawan->nominal_gaji;
            $potonganwfhsehari = MasterKode::findOne(['nama_group' => Yii::$app->params['potongan-persen-wfh']])['nama_kode'];

            $totalPotonganAbsensi = ($gaji_perhari * $total_alfa_range) + ($gaji_perhari * $jumlah_wfh * ($potonganwfhsehari / 100));


            return [
                'success' => true,
                'nominal_gaji' => $nominalGaji,
                'total_alfa_range' => (int)$total_alfa_range,
                'potongan_per_alfa' => $gaji_perhari,
                'total_potongan_absensi' => (int) $total_alfa_range * $gaji_perhari,
                'jumlah_wfh' => $jumlah_wfh,
                'potonganwfhsehari' => (int)$potonganwfhsehari,
                'gaji_perhari' => $gaji_perhari,
                'total_all' => $totalPotonganAbsensi
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    public function actionGetDinasKaryawan($id_karyawan, $bulan, $tahun)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $oldTransaksi = TransaksiGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
        if ($oldTransaksi) {
            $oldDInas = DinasDetailGaji::find()->where(['id_karyawan' => $id_karyawan, 'bulan' => $oldTransaksi->bulan, 'tahun' => $oldTransaksi->tahun])->asArray()->all();
        }

        if ($oldDInas) {
            return [
                'success' => true,
                'data' => $oldDInas
            ];
        }
        $periode_gaji = PeriodeGaji::findOne(['bulan' => $bulan, 'tahun' => $tahun]);
        $tanggal_awal_periode = $periode_gaji->tanggal_awal;
        $tanggal_akhir_periode = $periode_gaji->tanggal_akhir;

        $pengajuandinas = PengajuanDinas::find()
            ->where(['id_karyawan' => $id_karyawan, 'status' => 1, 'status_dibayar' => 0])
            ->asArray()
            ->all();

        return [
            'success' => true,
            'data' => $pengajuandinas
        ];
    }
    public function actionGetLemburKaryawan($id_karyawan, $bulan, $tahun)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $periode_gaji = PeriodeGaji::findOne(['bulan' => $bulan, 'tahun' => $tahun]);

        if (!$periode_gaji) {
            return [
                'success' => false,
                'error' => 'Periode gaji tidak ditemukan'
            ];
        }

        $tanggal_awal_periode = $periode_gaji->tanggal_awal;
        $tanggal_akhir_periode = $periode_gaji->tanggal_akhir;

        try {
            // Gunakan fungsi getLemburData yang sudah ada
            $lemburData = $this->getLemburData($id_karyawan, $bulan, $tahun, $tanggal_awal_periode, $tanggal_akhir_periode);

            return [
                'success' => true,
                "data" => $lemburData
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    public function actionGetKasbonKaryawan($id_karyawan)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = PembayaranKasbon::find()
            ->where(['id_karyawan' => $id_karyawan])
            ->andWhere(['status_potongan' => 0, 'autodebt' => 1])
            ->asArray()
            ->orderBy(['id_pembayaran_kasbon' => SORT_DESC])
            ->one();

        return [
            'success' => true,
            "data" => $data
        ];
    }
    private function getLemburData($id_karyawan, $bulan, $tahun, $tanggal_awal, $tanggal_akhir)
    {
        try {
            $jenisPengambilanLembur = SettinganUmum::find()
                ->where(['kode_setting' => Yii::$app->params['ajukan_lembur']])
                ->one();

            if ($jenisPengambilanLembur && $jenisPengambilanLembur->nilai_setting == '0') {
                // Lembur tidak diajukan, ambil dari rekap langsung
                $jam_lembur = (new \yii\db\Query())
                    ->from('rekap_lembur')
                    ->where(['id_karyawan' => $id_karyawan])
                    ->andWhere(['between', 'tanggal', $tanggal_awal, $tanggal_akhir])
                    ->all();
            } else {
                $jam_lembur = (new \yii\db\Query())
                    ->from('pengajuan_lembur')
                    ->where([
                        'id_karyawan' => $id_karyawan,
                        'status' => 1, // Hanya yang disetujui
                    ])
                    ->andWhere(['between', 'tanggal', $tanggal_awal, $tanggal_akhir])
                    ->all();
            }

            // Format data lembur sesuai struktur yang diinginkan
            $formatted_jam_lembur = [];
            $total_hitungan_jam = 0;

            foreach ($jam_lembur as $lembur) {
                $hitungan_jam = floatval($lembur['hitungan_jam'] ?? 0);

                // Format durasi dari hitungan_jam
                $jam = floor($hitungan_jam);
                $menit = round(($hitungan_jam - $jam) * 60);
                $durasi = sprintf("%02d:%02d:00", $jam, $menit);

                // Default time values (bisa disesuaikan dengan data sebenarnya)
                $jam_mulai = $lembur['jam_mulai'] ?? "19:00:00";

                // Hitung jam selesai berdasarkan durasi
                $jam_selesai = date("H:i:s", strtotime($jam_mulai) + ($hitungan_jam * 3600));

                $formatted_jam_lembur[] = [
                    'id_pengajuan_lembur' => $lembur['id_pengajuan_lembur'] ?? $lembur['id'] ?? null,
                    'id_karyawan' => $lembur['id_karyawan'],
                    'pekerjaan' => $lembur['pekerjaan'] ?? "[\"pekerjaan lembur\"]",
                    'status' => $lembur['status'] ?? 1,
                    'jam_mulai' => $jam_mulai,
                    'jam_selesai' => $jam_selesai,
                    'durasi' => $durasi,
                    'tanggal' => $lembur['tanggal'],
                    'disetujui_oleh' => $lembur['disetujui_oleh'] ?? 1,
                    'disetujui_pada' => $lembur['disetujui_pada'] ?? date("Y-m-d"),
                    'catatan_admin' => $lembur['catatan_admin'] ?? "-",
                    'hitungan_jam' => $hitungan_jam
                ];

                $total_hitungan_jam += $hitungan_jam;
            }

            // Hitung gaji per jam
            $gajiKaryawan = MasterGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
            $gaji_perjam = $gajiKaryawan ? round($gajiKaryawan->nominal_gaji / 173, 2) : 0;

            // Hitung total pendapatan lembur
            $total_pendapatan_lembur = round($total_hitungan_jam * $gaji_perjam, 2);

            return [
                'jam_lembur' => $formatted_jam_lembur,
                'gaji_perjam' => $gaji_perjam,
                'total_hitungan_jam' => $total_hitungan_jam,
                'total_pendapatan_lembur' => $total_pendapatan_lembur
            ];
        } catch (\Exception $e) {
            Yii::error("Error getting lembur data: " . $e->getMessage());
            return [
                'jam_lembur' => [],
                'gaji_perjam' => 0,
                'total_hitungan_jam' => 0,
                'total_pendapatan_lembur' => 0
            ];
        }
    }




    public function actionGetPendapatanPotonganLainnya()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = PendapatanPotonganLainnya::find()
            ->where([
                'id_karyawan' => Yii::$app->request->get('id_karyawan'),
                'bulan' => Yii::$app->request->get('bulan'),
                'tahun' => Yii::$app->request->get('tahun'),
                'is_pendapatan' => Yii::$app->request->get('is_pendapatan') ?? 0,
                'is_potongan' => Yii::$app->request->get('is_potongan') ?? 0,

            ])
            ->orderBy(['created_at' => SORT_ASC])
            ->asArray()
            ->all();

        return [
            'success' => true,
            'data' => $data
        ];
    }
}
