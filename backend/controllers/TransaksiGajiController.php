<?php

namespace backend\controllers;


use backend\models\DinasDetailGaji;
use backend\models\helpers\PeriodeGajiHelper;
use backend\models\LemburGaji;
use backend\models\MasterGaji;
use backend\models\MasterKode;
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
use yii\httpclient\debug\SearchModel;

/**
 * TransaksiGajiController implements the CRUD actions for TransaksiGaji model.
 */
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
    /**
     * Lists all TransaksiGaji models.
     *
     * @return string
     */
    public function actionIndex()
    {


        $id_karyawan = null;
        $bulan = date('m');
        $tahun = date('Y');
        if ($this->request->get('TransaksiGaji')) {
            $id_karyawan = $this->request->get('TransaksiGaji')['id_karyawan'] ?? null;
            $bulan = $this->request->get('TransaksiGaji')['bulan'] ?? date('m');
            $tahun = $this->request->get('TransaksiGaji')['tahun'] ?? date('Y');
        }
        $model = new TransaksiGaji();
        $searchModel = new TransaksiGajiSearch();

        $periode_gaji = new PeriodeGaji();
        $karyawanID = null;
        $periode_gajiID = PeriodeGajiHelper::getPeriodeGajiBulanIni();

        // Ambil semua dari search (data lengkap dengan gaji_bersih)
        $allDataFromSearch = $searchModel->search($this->request->queryParams, $id_karyawan, $bulan, $tahun)->getModels();

        // Indexing data search berdasarkan ID karyawan
        $searchIndexed = [];
        foreach ($allDataFromSearch as $item) {
            $searchIndexed[$item['id_karyawan']] = $item;
        }

        // Ambil data dari tabel transaksi_gaji
        $existsDataTransaksi = TransaksiGaji::find()
            ->where(['bulan' => $bulan, 'tahun' => $tahun])
            ->asArray()
            ->all();

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
            'karyawanID' => $karyawanID,
            'periode_gajiID' => $periode_gajiID
        ]);
    }

    public function actionReport()
    {
        $model = new TransaksiGaji();
        $bulan = date('m');
        $tahun = date('Y');
        $searchModel = new TransaksiGajiSearch();

        $periode_gaji = new PeriodeGaji();
        $karyawanID = null;
        $periode_gajiID = PeriodeGajiHelper::getPeriodeGajiBulanIni();

        // Ambil semua dari search (data lengkap dengan gaji_bersih)
        $allDataFromSearch = $searchModel->search($this->request->queryParams, null)->getModels();

        // Indexing data search berdasarkan ID karyawan
        $searchIndexed = [];
        foreach ($allDataFromSearch as $item) {
            $searchIndexed[$item['id_karyawan']] = $item;
        }

        // Ambil data dari tabel transaksi_gaji
        $existsDataTransaksi = TransaksiGaji::find()
            ->where(['bulan' => $bulan, 'tahun' => $tahun])
            ->asArray()
            ->all();

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

        // Handle POST request untuk filter
        if ($this->request->isPost) {
            $karyawanID = Yii::$app->request->post('Karyawan')['id_karyawan'];
            $periode_gajiID = intval(Yii::$app->request->post('PeriodeGaji')['id_periode_gaji']);

            if (!$karyawanID) $karyawanID = null;
            if (!$periode_gajiID) $periode_gajiID = null;

            $periode_gaji = PeriodeGaji::findOne($periode_gajiID);
            $bulan = $periode_gaji->bulan;
            $tahun = $periode_gaji->tahun;
        }

        // Render partial view untuk content PDF
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

        // Setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE, // landscape untuk tabel yang lebar
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '
            .kv-heading-1{font-size:18px}
            table {width: 100%; border-collapse: collapse; font-size: 10px;}
            th, td {border: 1px solid #ddd; padding: 6px; text-align: left;}
            th {background-color: #f2f2f2; font-weight: bold;}
            .text-right {text-align: right;}
            .text-center {text-align: center;}
            .header {text-align: center; margin-bottom: 20px;}
            .title {font-size: 16px; font-weight: bold;}
            .subtitle {font-size: 14px;}
        ',
            // set mPDF properties on the fly
            'options' => ['title' => 'Laporan Transaksi Gaji'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => ['Laporan Transaksi Gaji'],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        // Return the pdf object as response
        return $pdf->render();
    }



    public function actionGenerateGaji()
    {
        $bulan = date('m');
        $tahun = date('Y');
        $this->actionDeleteAll($bulan, $tahun);

        $searchModel = new TransaksiGajiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, null);

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

        if (!empty($models)) {
            foreach ($models as $modelData) {
                $idKaryawan = $modelData['id_karyawan'];
                $nominalGaji = $modelData['nominal_gaji'];
                $tunjanganList = TunjanganDetail::getTunjanganKaryawan($idKaryawan, $nominalGaji);
                $potonganList = PotonganDetail::getPotonganKaryawan($idKaryawan, $nominalGaji);
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

                $dinasList = PengajuanDinas::find()
                    ->where(['id_karyawan' => $idKaryawan, 'status' => 1, 'status_dibayar' => 0])
                    ->andWhere(['between', 'tanggal_mulai', $tanggal_awal_periode, $tanggal_akhir_periode])
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

                // Simpan detail dinas
                foreach ($dinasList as $dinas) {
                    $tanggalMulai = new \DateTime($dinas['tanggal_mulai']);
                    $tanggalSelesai = new \DateTime($dinas['tanggal_selesai']);

                    $interval = new \DateInterval('P1D');
                    $period = new \DatePeriod($tanggalMulai, $interval, $tanggalSelesai->modify('+1 day'));

                    foreach ($period as $tanggal) {
                        $dinasDetailRows[] = [
                            'id_karyawan' => $idKaryawan,
                            'nama' => $modelData['nama'],
                            'bulan' => $bulan,
                            'tahun' => $tahun,
                            'tanggal' => $tanggal->format('Y-m-d'),
                            'keterangan' => $dinas['keterangan_perjalanan'],
                            'biaya' => $dinas['biaya_yang_disetujui'] ?? 0,
                        ];
                    }
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
                        'tanggal',
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

        return $this->redirect(['index']);
    }


    public function actionGenerateGajiOne($id_karyawan)
    {
        $bulan = date('m');
        $tahun = date('Y');

        // 1. DELETE data lama dulu
        $this->actionDeleteOne($id_karyawan, $bulan, $tahun);

        $searchModel = new TransaksiGajiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $id_karyawan);
        $models = $dataProvider->getModels();

        if (empty($models)) {
            Yii::$app->session->setFlash('warning', "Tidak ada data perhitungan gaji untuk karyawan id {$id_karyawan}.");
            return $this->redirect(['index']);
        }

        $modelData = reset($models);
        $now = date('Y-m-d H:i:s');
        $userId = Yii::$app->user->id;

        // 2. AMBIL DATA KOMPONEN LAINNYA
        $nominalGaji = $modelData['nominal_gaji'];
        $tunjanganList = TunjanganDetail::getTunjanganKaryawan($id_karyawan, $nominalGaji);
        $potonganList = PotonganDetail::getPotonganKaryawan($id_karyawan, $nominalGaji);

        $periode_gaji = PeriodeGaji::findOne(['bulan' => $bulan, 'tahun' => $tahun]);
        $tanggal_awal_periode = $periode_gaji->tanggal_awal;
        $tanggal_akhir_periode = $periode_gaji->tanggal_akhir;

        // Ambil data lembur
        $lemburData = $this->getLemburData($id_karyawan, $bulan, $tahun, $tanggal_awal_periode, $tanggal_akhir_periode);
        $jam_lembur = $lemburData['jam_lembur'];
        $total_hitungan_jam = $lemburData['total_hitungan_jam'];
        $total_pendapatan_lembur = $lemburData['total_pendapatan_lembur'];

        $dinasList = PengajuanDinas::find()
            ->where(['id_karyawan' => $id_karyawan, 'status' => 1, 'status_dibayar' => 0])
            ->andWhere(['between', 'tanggal_mulai', $tanggal_awal_periode, $tanggal_akhir_periode])
            ->asArray()
            ->all();

        // Ambil data terlambat
        $terlambatData = $this->actionGetPotonganTerlambatKaryawan($id_karyawan);

        // Ambil data potongan absensi (alfa & WFH)
        $potonganAbsensiData = $this->actionGetPotonganAbsensiKaryawan(
            $id_karyawan,
            $modelData['total_alfa_range'],
            $modelData['gaji_perhari'],
            $modelData['jumlah_wfh'] ?? 0
        );

        // 3. SIMPAN KE TRANSAKSI_GAJI
        $new = new TransaksiGaji();
        $new->id_karyawan = $modelData['id_karyawan'];
        $new->nama = $modelData['nama'];
        $new->id_bagian = $modelData['id_bagian'];
        $new->nama_bagian = $modelData['nama_bagian'] ?? '-';
        $new->jabatan = $modelData['jabatan'];
        $new->bulan = $bulan;
        $new->tahun = $tahun;
        $new->tanggal_awal = $modelData['tanggal_awal'];
        $new->tanggal_akhir = $modelData['tanggal_akhir'];
        $new->total_absensi = $modelData['total_absensi'];
        $new->terlambat = $modelData['terlambat'];
        $new->nominal_gaji = $modelData['nominal_gaji'];
        $new->potongan_karyawan = $modelData['potongan_karyawan'];
        $new->tunjangan_karyawan = $modelData['tunjangan_karyawan'];
        $new->gaji_perhari = $modelData['gaji_perhari'];
        $new->jam_lembur = (int) $total_hitungan_jam;
        $new->total_pendapatan_lembur = $total_pendapatan_lembur;
        $new->potongan_terlambat = $modelData['potongan_terlambat'];
        $new->total_alfa_range = $modelData['total_alfa_range'];
        $new->potongan_absensi = $modelData['potongan_absensi'];
        $new->dinas_luar_belum_terbayar = $modelData['dinas_luar_belum_terbayar'];

        $new->created_at = $now;
        $new->updated_at = $now;
        $new->created_by = $userId;
        $new->updated_by = $userId;
        $new->status = 1;

        if ($new->save()) {
            $errorMessages = [];

            // Simpan rekap gaji
            $rekapGaji = new RekapGajiKaryawanPertransaksi();
            $rekapGaji->id_karyawan = $id_karyawan;
            $rekapGaji->nama_karyawan = $modelData['nama'];
            $rekapGaji->nama_bagian = $modelData['nama_bagian'] ?? '-';
            $rekapGaji->jabatan = $modelData['jabatan'];
            $rekapGaji->bulan = $bulan;
            $rekapGaji->tahun = $tahun;
            $rekapGaji->gaji_perbulan = $modelData['nominal_gaji'];
            $rekapGaji->gaji_perhari = $modelData['gaji_perhari'];
            $rekapGaji->gaji_perjam = $modelData['nominal_gaji'] / 173;
            if (!$rekapGaji->save()) {
                $errorMessages[] = "Rekap Gaji: " . json_encode($rekapGaji->errors);
            }

            // Simpan potongan alfa & WFH
            if ($potonganAbsensiData['success']) {
                $potonganAlfaWfh = new PotonganAlfaAndWfhPenggajian();
                $potonganAlfaWfh->id_karyawan = $id_karyawan;
                $potonganAlfaWfh->nama = $modelData['nama'];
                $potonganAlfaWfh->bulan = $bulan;
                $potonganAlfaWfh->tahun = $tahun;
                $potonganAlfaWfh->jumlah_alfa = $potonganAbsensiData['total_alfa_range'];
                $potonganAlfaWfh->total_potongan_alfa = $potonganAbsensiData['total_potongan_absensi'];
                $potonganAlfaWfh->jumlah_wfh = $potonganAbsensiData['jumlah_wfh'];
                $potonganAlfaWfh->persen_potongan_wfh = $potonganAbsensiData['potonganwfhsehari'];
                $potonganAlfaWfh->total_potongan_wfh = $potonganAbsensiData['jumlah_wfh'] * $potonganAbsensiData['potonganwfhsehari'];
                $potonganAlfaWfh->gaji_perhari = $potonganAbsensiData['gaji_perhari'];
                if (!$potonganAlfaWfh->save()) {
                    $errorMessages[] = "Potongan Alfa & WFH: " . json_encode($potonganAlfaWfh->errors);
                }
            }

            // Simpan tunjangan
            foreach ($tunjanganList as $index => $tunjangan) {
                $tunjanganRekap = new TunjanganRekapGaji();
                $tunjanganRekap->id_karyawan = $id_karyawan;
                $tunjanganRekap->bulan = $bulan;
                $tunjanganRekap->tahun = $tahun;
                $tunjanganRekap->id_tunjangan = $tunjangan['id_tunjangan'];
                $tunjanganRekap->nama_tunjangan = $tunjangan['nama_tunjangan'];
                $tunjanganRekap->jumlah = $tunjangan['nominal_final'];
                if (!$tunjanganRekap->save()) {
                    $errorMessages[] = "Tunjangan {$tunjangan['nama_tunjangan']}: " . json_encode($tunjanganRekap->errors);
                }
            }

            // Simpan potongan
            foreach ($potonganList as $index => $potongan) {
                $potonganRekap = new PotonganRekapGaji();
                $potonganRekap->id_karyawan = $id_karyawan;
                $potonganRekap->bulan = $bulan;
                $potonganRekap->tahun = $tahun;
                $potonganRekap->id_potongan = $potongan['id_potongan'];
                $potonganRekap->nama_potongan = $potongan['nama_potongan'];
                $potonganRekap->jumlah = $potongan['nominal_final'];
                if (!$potonganRekap->save()) {
                    $errorMessages[] = "Potongan {$potongan['nama_potongan']}: " . json_encode($potonganRekap->errors);
                }
            }

            // Simpan dinas
            foreach ($dinasList as $dinasIndex => $dinas) {
                $tanggalMulai = new \DateTime($dinas['tanggal_mulai']);
                $tanggalSelesai = new \DateTime($dinas['tanggal_selesai']);

                $interval = new \DateInterval('P1D');
                $period = new \DatePeriod($tanggalMulai, $interval, $tanggalSelesai->modify('+1 day'));

                foreach ($period as $dayIndex => $tanggal) {
                    $dinasDetail = new DinasDetailGaji();
                    $dinasDetail->id_karyawan = $id_karyawan;
                    $dinasDetail->nama = $modelData['nama'];
                    $dinasDetail->bulan = $bulan;
                    $dinasDetail->tahun = $tahun;
                    $dinasDetail->tanggal = $tanggal->format('Y-m-d');
                    $dinasDetail->keterangan = $dinas['keterangan_perjalanan'];
                    $dinasDetail->biaya = $dinas['biaya_yang_disetujui'] ?? 0;
                    if (!$dinasDetail->save()) {
                        $errorMessages[] = "Dinas {$dinas['keterangan_perjalanan']} ({$tanggal->format('Y-m-d')}): " . json_encode($dinasDetail->errors);
                    }
                }
            }

            // Simpan lembur
            foreach ($jam_lembur as $index => $lembur) {
                $lemburGaji = new LemburGaji();
                $lemburGaji->id_karyawan = $id_karyawan;
                $lemburGaji->nama = $modelData['nama'];
                $lemburGaji->bulan = $bulan;
                $lemburGaji->tahun = $tahun;
                $lemburGaji->tanggal = $lembur['tanggal'];
                $lemburGaji->hitungan_jam = $lembur['hitungan_jam'] ?? 0;
                if (!$lemburGaji->save()) {
                    $errorMessages[] = "Lembur {$lembur['tanggal']}: " . json_encode($lemburGaji->errors);
                }
            }

            // Simpan terlambat
            foreach ($terlambatData['filteredTerlambat'] as $index => $terlambat) {
                $rekapTerlambat = new RekapTerlambatTransaksiGaji();
                $rekapTerlambat->id_karyawan = $id_karyawan;
                $rekapTerlambat->bulan = $bulan;
                $rekapTerlambat->tahun = $tahun;
                $rekapTerlambat->nama = $modelData['nama'];
                $rekapTerlambat->tanggal = $terlambat['tanggal'];
                $rekapTerlambat->lama_terlambat = $terlambat['terlambat'];
                $rekapTerlambat->potongan_permenit = $terlambatData['potonganPerMenit'];
                if (!$rekapTerlambat->save()) {
                    $errorMessages[] = "Terlambat {$terlambat['tanggal']}: " . json_encode($rekapTerlambat->errors);
                }
            }

            // Tampilkan alert berdasarkan hasil
            if (empty($errorMessages)) {
                Yii::$app->session->setFlash('success', "Gaji karyawan berhasil digenerate ulang dengan semua data detail.");
            } else {
                $errorCount = count($errorMessages);
                Yii::$app->session->setFlash(
                    'warning',
                    "Gaji berhasil digenerate, tetapi {$errorCount} data detail gagal disimpan: " .
                        implode('; ', $errorMessages)
                );
            }
        } else {
            Yii::$app->session->setFlash('error', "Gagal menyimpan gaji: " . json_encode($new->errors));
        }

        return $this->redirect(['index']);
    }







    public function actionView($id_transaksi_gaji)
    {
        $model = TransaksiGaji::find()->where(['id_transaksi_gaji' => $id_transaksi_gaji])->one();
        if (!$model) {
            throw new NotFoundHttpException("Data dengan ID {$id_transaksi_gaji} tidak ditemukan.");
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionDeleteAll($bulan, $tahun)
    {
        TransaksiGaji::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        TunjanganRekapGaji::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        PotonganRekapGaji::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        LemburGaji::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        DinasDetailGaji::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        RekapGajiKaryawanPertransaksi::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        PotonganAlfaAndWfhPenggajian::deleteAll(['bulan' => $bulan, 'tahun' => $tahun]);
        return;
    }
    public function actionDeleteOne($id_karyawan, $bulan, $tahun)
    {
        $this->deleteGajiData($id_karyawan, $bulan, $tahun);
    }

    public function actionDeleterow($id_karyawan, $bulan, $tahun)
    {
        $this->deleteGajiData($id_karyawan, $bulan, $tahun);
        return $this->redirect(['index']);
    }

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
    }

    /**
     * Finds the TransaksiGaji model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_transaksi_gaji Id Transaksi Gaji
     * @return TransaksiGaji the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_transaksi_gaji)
    {
        if (($model = TransaksiGaji::findOne(['id_transaksi_gaji' => $id_transaksi_gaji])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }





    // ===================================================================
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
        $getToleranceTerlambat = MasterKode::findOne(['nama_group' => Yii::$app->params['teleransi-keterlambatan']])['nama_kode'];
        $filteredTerlambat = [];

        foreach ($allDataFromSearch['terlambat_with_date'] as $data) {
            list($jam, $menit, $detik) = explode(':', $data['terlambat']);
            $totalDetik = ((float)$jam * 3600) + ((float)$menit * 60) + (float)$detik;

            // Bandingkan dengan batas menit
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
                    ->one(); // gunakan one() karena cuma 1 data



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
            $totalPotonganAbsensi = $gaji_perhari * $total_alfa_range;


            return [
                'success' => true,
                'nominal_gaji' => $nominalGaji,
                'total_alfa_range' => (int)$total_alfa_range,
                'potongan_per_alfa' => $gaji_perhari,
                'total_potongan_absensi' => $totalPotonganAbsensi,
                'jumlah_wfh' => $jumlah_wfh,
                'potonganwfhsehari' => (int)$potonganwfhsehari,
                'gaji_perhari' => $gaji_perhari,
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
            ->andWhere(['>=', 'tanggal_mulai', $tanggal_awal_periode])
            ->andWhere(['<=', 'tanggal_selesai', $tanggal_akhir_periode])
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

        // Cari periode gaji berdasarkan bulan dan tahun
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

    /**
     * Mendapatkan data lembur untuk perhitungan gaji
     */
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
}
