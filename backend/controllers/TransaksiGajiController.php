<?php

namespace backend\controllers;

use backend\models\GajiPotongan;
use backend\models\GajiTunjangan;
use backend\models\helpers\PeriodeGajiHelper;
use backend\models\MasterGaji;
use backend\models\MasterKode;
use backend\models\PengajuanDinas;
use backend\models\PeriodeGaji;
use backend\models\PotonganDetail;
use backend\models\SettinganUmum;
use backend\models\TransaksiGaji;
use backend\models\TransaksiGajiSearch;
use backend\models\TunjanganDetail;
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
        $model = new TransaksiGaji();
        $bulan = 1;
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

















    public function actionGenerateGaji()
    {
        $bulan = 1;
        $tahun = date('Y');

        $searchModel = new TransaksiGajiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, null);

        $models = $dataProvider->getModels();
        $rows = [];
        $now = date('Y-m-d H:i:s');
        $userId = Yii::$app->user->id;
        $tunjanganRekapRows = [];
        $potonganRekapRows = [];
        $dinasDetailRows = [];
        $lemburGajiRows = []; // Tambahkan array untuk lembur

        if (!empty($models)) {
            foreach ($models as $modelData) {
                $idKaryawan = $modelData['id_karyawan'];
                $nominalGaji = $modelData['nominal_gaji'];
                $tunjanganList = TunjanganDetail::getTunjanganKaryawan($idKaryawan, $nominalGaji);
                $potonganList = PotonganDetail::getPotonganKaryawan($idKaryawan, $nominalGaji);
                $periode_gaji = PeriodeGaji::findOne(['bulan' => $bulan, 'tahun' => $tahun]);
                $tanggal_awal_periode = $periode_gaji->tanggal_awal;
                $tanggal_akhir_periode = $periode_gaji->tanggal_akhir;

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
                    'jam_lembur' => $total_hitungan_jam, // Update dengan total hitungan jam
                    'total_pendapatan_lembur' => $total_pendapatan_lembur, // Update dengan total pendapatan lembur
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

                // Simpan detail tunjangan
                foreach ($tunjanganList as $tunjangan) {
                    $tunjanganRekapRows[] = [
                        'id_karyawan' => $idKaryawan,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'id_tunjangan' => $tunjangan['id_tunjangan'],
                        'nama_tunjangan' => $tunjangan['nama_tunjangan'],
                        'jumlah' => $tunjangan['nominal_final'],
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
                        'jumlah' => $potongan['nominal_final'],
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
                        'bulan',
                        'tahun',
                        'tanggal',
                        'hitungan_jam',
                    ], $lemburGajiRows)->execute();
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

        $searchModel = new TransaksiGajiSearch();
        // kita ambil data search hanya untuk karyawan ini
        $dataProvider = $searchModel->search($this->request->queryParams, $id_karyawan);
        $models = $dataProvider->getModels();
        if (empty($models)) {
            Yii::$app->session->setFlash('warning', "Tidak ada data perhitungan gaji untuk karyawan id {$id_karyawan}.");
            return $this->redirect(['index']);
        }

        $modelData = reset($models); // ambil record pertama (harusnya cuma satu)

        $now = date('Y-m-d H:i:s');
        $userId = Yii::$app->user->id;

        // Cek apakah sudah ada di tabel
        $existsModel = TransaksiGaji::findOne([
            'id_karyawan' => $id_karyawan,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        if ($existsModel) {
            // update record existing
            $existsModel->nama = $modelData['nama'];
            $existsModel->id_bagian = $modelData['id_bagian'];
            $existsModel->nama_bagian = $modelData['nama_bagian'];
            $existsModel->jabatan = $modelData['jabatan'];
            $existsModel->total_absensi = $modelData['total_absensi'];
            $existsModel->terlambat = $modelData['terlambat'];
            $existsModel->nominal_gaji = $modelData['nominal_gaji'];
            $existsModel->potongan_karyawan = $modelData['potongan_karyawan'];
            $existsModel->tunjangan_karyawan = $modelData['tunjangan_karyawan'];
            $existsModel->gaji_perhari = $modelData['gaji_perhari'];
            $existsModel->jam_lembur = $modelData['jam_lembur'];
            $existsModel->total_pendapatan_lembur = $modelData['total_pendapatan_lembur'];
            $existsModel->potongan_terlambat = $modelData['potongan_terlambat'];
            $existsModel->total_alfa_range = $modelData['total_alfa_range'];
            $existsModel->potongan_absensi = $modelData['potongan_absensi'];
            $existsModel->dinas_luar_belum_terbayar = $modelData['dinas_luar_belum_terbayar'];

            $existsModel->updated_at = $now;
            $existsModel->updated_by = $userId;
            $existsModel->status = 1;

            if ($existsModel->save()) {
                Yii::$app->session->setFlash('success', "Gaji karyawan id {$id_karyawan} berhasil diperbarui.");
            } else {
                Yii::$app->session->setFlash('error', "Gagal memperbarui gaji: " . json_encode($existsModel->errors));
            }
        } else {
            // insert baru
            $new = new TransaksiGaji();
            $new->id_karyawan = $modelData['id_karyawan'];
            $new->nama = $modelData['nama'];
            $new->id_bagian = $modelData['id_bagian'];
            $new->nama_bagian = $modelData['nama_bagian'];
            $new->jabatan = $modelData['jabatan'];
            $new->bulan = $modelData['bulan'];
            $new->tahun = $modelData['tahun'];
            $new->tanggal_awal = $modelData['tanggal_awal'];
            $new->tanggal_akhir = $modelData['tanggal_akhir'];
            $new->total_absensi = $modelData['total_absensi'];
            $new->terlambat = $modelData['terlambat'];
            $new->nominal_gaji = $modelData['nominal_gaji'];
            $new->potongan_karyawan = $modelData['potongan_karyawan'];
            $new->tunjangan_karyawan = $modelData['tunjangan_karyawan'];
            $new->gaji_perhari = $modelData['gaji_perhari'];
            $new->jam_lembur = $modelData['jam_lembur'];
            $new->total_pendapatan_lembur = $modelData['total_pendapatan_lembur'];
            $new->potongan_terlambat = $modelData['potongan_terlambat'];
            $new->total_alfa_range = $modelData['total_alfa_range'];
            $new->potongan_absensi = $modelData['potongan_absensi'];
            $new->dinas_luar_belum_terbayar = $modelData['dinas_luar_belum_terbayar'];

            $new->created_at = $now;
            $new->updated_at = $now;
            $new->created_by = $userId;
            $new->updated_by = $userId;

            if ($new->save()) {
                Yii::$app->session->setFlash('success', "Gaji karyawan id {$id_karyawan} berhasil disimpan.");
            } else {
                Yii::$app->session->setFlash('error', "Gagal menyimpan gaji: " . json_encode($new->errors));
            }
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

    public function actionDelete($id_transaksi_gaji)
    {
        $model = $this->findModel($id_transaksi_gaji);
        // $karyawan = Karyawan::find()->where(['kode_karyawan' => $model->kode_karyawan])->asArray()->one();
        if ($model->delete()) {
            $gajiTunjangan = GajiTunjangan::find()->where(['id_transaksi_gaji' => $id_transaksi_gaji])->all();
            $gajiPotongan = GajiPotongan::find()->where(['id_transaksi_gaji' => $id_transaksi_gaji])->all();


            // Hapus semua data yang ditemukan
            foreach ($gajiTunjangan as $item) {
                $item->delete();
            }

            foreach ($gajiPotongan as $item) {
                $item->delete();
            }

            Yii::$app->session->setFlash('success', 'Data Berhasilsil Dihapus');
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Data Gagal Dihapus');
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

        try {
            $gajiKaryawan = MasterGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
            $nominalGaji = $gajiKaryawan ? $gajiKaryawan->nominal_gaji : 0;

            $tunjanganData = TunjanganDetail::getTunjanganKaryawan($id_karyawan, $nominalGaji);
            return [
                'success' => true,
                'nominal_gaji' => $nominalGaji,
                'data' => $tunjanganData
            ];
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

        try {
            // Ambil gaji karyawan
            $gajiKaryawan = MasterGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
            $nominalGaji = $gajiKaryawan ? $gajiKaryawan->nominal_gaji : 0;

            // Ambil data potongan dengan fungsi reusable
            $potonganData = PotonganDetail::getPotonganKaryawan($id_karyawan, $nominalGaji);

            return [
                'success' => true,
                'nominal_gaji' => $nominalGaji,
                'data' => $potonganData
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function actionGetPotonganAbsensiKaryawan($id_karyawan, $total_alfa_range, $gaji_perhari, $jumlah_wfh)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            // Ambil gaji karyawan
            $gajiKaryawan = MasterGaji::find()->where(['id_karyawan' => $id_karyawan])->one();

            if (!$gajiKaryawan) {
                throw new \Exception('Data gaji karyawan tidak ditemukan');
            }


            $nominalGaji = $gajiKaryawan->nominal_gaji;

            $potonganwfhsehari = MasterKode::findOne(['nama_group' => Yii::$app->params['potongan-persen-wfh']])['nama_kode'];

            $potonganPerAlfa = $nominalGaji * 0.01; // Contoh: 1% dari gaji per alfa
            $totalPotonganAbsensi = $potonganPerAlfa * $total_alfa_range;

            return [
                'success' => true,
                'nominal_gaji' => $nominalGaji,
                'total_alfa_range' => (int)$total_alfa_range,
                'potongan_per_alfa' => $potonganPerAlfa,
                'total_potongan_absensi' => $totalPotonganAbsensi,
                'gaji_perhari' => $gaji_perhari,
                'jumlah_wfh' => $jumlah_wfh,
                'potonganwfhsehari' => $potonganwfhsehari
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function actionGetPotonganTerlambatKaryawan($id_karyawan,)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $searchModel = new TransaksiGajiSearch();
        $allDataFromSearch = $searchModel->search($this->request->queryParams, $id_karyawan)->getModels()[0];
        $gajiBulanan = 3000000; // Contoh nominal gaji
        $gajiPerMenit = ($gajiBulanan / 173) / 60;

        // Bulatkan ke 2 angka di belakang koma sebagai float
        $gajiPerMenitFloat = round($gajiPerMenit, 2);
        $getToleranceTerlambat = MasterKode::findOne(['nama_group' => Yii::$app->params['teleransi-keterlambatan']])['nama_kode'];;
        $filteredTerlambat = [];


        foreach ($allDataFromSearch['terlambat_with_date'] as $data) {
            list($jam, $menit, $detik) = explode(':', $data['terlambat']);
            $totalDetik = ($jam * 3600) + ($menit * 60) + $detik;

            // Bandingkan dengan batas menit
            if ($totalDetik > ((int)$getToleranceTerlambat * 60)) {
                $filteredTerlambat[] = $data;
            }
        }


        return [
            'success' => true,
            'filteredTerlambat' => $filteredTerlambat,
            'potonganPerMenit' => $gajiPerMenitFloat,
            'potonganSemuaTerlambat' => round($allDataFromSearch['potongan_terlambat'], 2),
            'lama_terlmabat' => $allDataFromSearch['terlambat']
        ];
    }

    public function actionGetDinasKaryawan($id_karyawan, $bulan, $tahun)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
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
                        'status' => 1,
                    ])
                    ->andWhere(['between', 'tanggal', $tanggal_awal, $tanggal_akhir])
                    ->all();
            }

            // Hitung total hitungan jam dan pendapatan lembur
            $total_hitungan_jam = 0;
            foreach ($jam_lembur as $lembur) {
                $total_hitungan_jam += floatval($lembur['hitungan_jam'] ?? 0);
            }

            // Hitung gaji per jam
            $gajiKaryawan = MasterGaji::find()->where(['id_karyawan' => $id_karyawan])->one();
            $gaji_perjam = $gajiKaryawan ? round($gajiKaryawan->nominal_gaji / 173, 2) : 0;

            // Hitung total pendapatan lembur
            $total_pendapatan_lembur = round($total_hitungan_jam * $gaji_perjam, 2);

            return [
                'jam_lembur' => $jam_lembur,
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
