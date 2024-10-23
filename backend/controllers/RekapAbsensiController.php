<?php

namespace backend\controllers;

use backend\models\Absensi;
use backend\models\AbsensiSearch;
use backend\models\AtasanKaryawan;
use backend\models\Karyawan;
use kartik\mpdf\Pdf;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RekapAbsensiController implements the CRUD actions for Absensi model.
 */
class RekapAbsensiController extends Controller
{
    /**
     * @inheritDoc
     */

    public function beforeAction($action)
    {
        if ($action->id == 'index' || $action->id == 'report') {

            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
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
                'access' => [
                    'class' => \yii\filters\AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Absensi models.
     *
     * @return string
     */
    public function actionIndex()
    {

        if (Yii::$app->request->isPost) {

            $query =  $this->RekapData(Yii::$app->request->post());
            $bulan = Yii::$app->request->post('bulan');
            $tahun = Yii::$app->request->post('tahun');
            $data = $query;
        } else {
            $bulan = date('m');
            $tahun = date('Y');
            $data = $this->RekapData();
        }


        return $this->render('index', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'hasil' => $data['hasil'],
            'rekapanAbsensi' => $data['rekapanAbsensi'],
            'tanggal_bulanan' => $data['tanggal_bulanan'],
            'karyawanTotal' => $data['karyawanTotal'],
            'keterlambatanPerTanggal' => $data['keterlambatanPerTanggal'],

        ]);
    }

    public function actionReport()
    {
        $bulan = date('m');
        $tahun = date('Y');
        $data = $this->RekapData();

        $content = $this->renderPartial('_report', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'hasil' => $data['hasil'],
            'rekapanAbsensi' => $data['rekapanAbsensi'],
            'tanggal_bulanan' => $data['tanggal_bulanan'],
            'karyawanTotal' => $data['karyawanTotal'],
            'keterlambatanPerTanggal' => $data['keterlambatanPerTanggal'],
        ]);


        $pdf = new Pdf([

            'mode' => Pdf::MODE_CORE,

            'format' => Pdf::FORMAT_A4,

            'orientation' => Pdf::ORIENT_LANDSCAPE,

            'destination' => Pdf::DEST_BROWSER,

            'content' => $content,


            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',

            'cssInline' => '.kv-heading-1{font-size:18px}',

            'options' => ['title' => 'Report Rekap Absensi ' . date('F')],
            'methods' => [

                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }


    public function RekapData($params = null)
    {

        //! mengambil parameter
        if ($params != null) {
            $bulan = $params['bulan'];
            $tahun = $params['tahun'];
        } else {

            $bulan = date('m');
            $tahun = date('Y');
        }

        // !inisiasi awaldan akhir bulan
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, 1, $tahun));
        $lastDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, date('t', mktime(0, 0, 0, $bulan, 1, $tahun)), $tahun));


        // ! Get total karyawan
        $karyawanTotal = Karyawan::find()->where(['is_aktif' => 1])->count();


        //! get all absensi
        $absensi = Absensi::find()
            ->select([
                'absensi.id_karyawan',
                'absensi.jam_masuk',
                'absensi.tanggal',
                'absensi.is_lembur',
                'absensi.kode_status_hadir',
                'jkk.id_jam_kerja',
                'jdk.id_jam_kerja',
                'jdk.jam_masuk as jam_masuk_kerja',
                'jdk.nama_hari'
            ])
            ->asArray()
            ->leftJoin('jam_kerja_karyawan jkk', 'jkk.id_karyawan = absensi.id_karyawan')
            ->leftJoin('jadwal_kerja jdk', 'jkk.id_jam_kerja = jdk.id_jam_kerja AND jdk.nama_hari = DAYOFWEEK(absensi.tanggal) - 1')
            ->andWhere(['>=', 'absensi.tanggal', $firstDayOfMonth])
            ->andWhere(['<=', 'absensi.tanggal', $lastDayOfMonth])
            ->all();



        //    ! get all data tanggal awal dan akhir bulan
        $tanggal_bulanan = array();
        for ($i = 1; $i <= date('t', mktime(0, 0, 0, $bulan, 1, $tahun)); $i++) {
            $tanggal_bulanan[] = date('d', mktime(0, 0, 0, $bulan, $i, $tahun));
        }


        //! get karyawan data
        $dataKaryawan = Karyawan::find()
            ->select(['karyawan.id_karyawan', 'karyawan.nama', 'karyawan.kode_karyawan', 'bg.id_bagian', 'bg.nama_bagian', 'dp.jabatan', 'mk.nama_kode as jabatan'])
            ->asArray()
            ->where(['karyawan.is_aktif' => 1])
            ->leftJoin('{{%data_pekerjaan}} dp', 'karyawan.id_karyawan = dp.id_karyawan')
            ->leftJoin('{{%bagian}} bg', 'dp.id_bagian = bg.id_bagian')
            ->leftJoin('{{%master_kode}} mk', 'mk.nama_group = "jabatan" and dp.jabatan = mk.kode')
            ->orderBy(['nama' => SORT_ASC])
            ->all();

        $hasil = [];

        // !masukan absensi ke karyawan
        $totalHari = date('t', mktime(0, 0, 0, $bulan, 1, $tahun));
        $totalTerlambat = 0;
        $totalHadir = 0;
        $detikTerlambat = 0;
        $totalTidakHadir = 0; // Variabel untuk menyimpan total tidak hadir

        // Array untuk menyimpan total terlambat per tanggal
        $keterlambatanPerTanggal = array_fill(1, $totalHari, 0);

        foreach ($dataKaryawan as $karyawan) {
            $karyawanData = [
                [
                    "id_karyawan" => $karyawan["id_karyawan"],
                    "nama" => $karyawan["nama"],
                    "kode_karyawan" => $karyawan["kode_karyawan"],
                    "id_bagian" => $karyawan["id_bagian"],
                    "bagian" => $karyawan["nama_bagian"],
                    "jabatan" => $karyawan["jabatan"],
                ],
            ];

            for ($i = 1; $i <= $totalHari; $i++) {
                $tanggal = date('Y-m-d', mktime(0, 0, 0, $bulan, $i, $tahun));
                $absensiRecord = array_filter($absensi, function ($record) use ($karyawan, $tanggal) {
                    return $record['id_karyawan'] == $karyawan['id_karyawan'] && $record['tanggal'] == $tanggal;
                });

                $statusHadir = null;
                $is_lembur = 0;
                $jamMasukKaryawan = null;
                $jamMasukKantor = null;

                if (!empty($absensiRecord)) {
                    $record = array_values($absensiRecord)[0]; // Ambil record pertama
                    $statusHadir = $record['kode_status_hadir'];
                    $is_lembur = $record['is_lembur'];
                    $jamMasukKaryawan = $record['jam_masuk'];
                    $jamMasukKantor = $record['jam_masuk_kerja'];

                    if ($statusHadir == 'H') {
                        $jamMasuk = strtotime($record['jam_masuk']);
                        $jamMasukKerja = strtotime($record['jam_masuk_kerja'] ?? "08:00:00");

                        if ($jamMasuk > $jamMasukKerja && $record['is_lembur'] == 0) {
                            $totalTerlambat++;
                            $selisihDetik = $jamMasuk - $jamMasukKerja;
                            $detikTerlambat += $selisihDetik;

                            // Tambahkan ke keterlambatan per tanggal
                            $keterlambatanPerTanggal[$i]++;
                        }
                        $totalHadir++;
                    }
                } else {
                    // Jika tidak ada record absensi, anggap sebagai tidak hadir
                    if ($i <= date('j')) { // Pastikan hanya untuk hari yang sudah berlalu
                        $totalTidakHadir++;
                    }
                }

                // Jika status hadir tidak termasuk H, S, DL, C
                if ($i <= date('j') && !in_array($statusHadir, ['H', 'S', 'DL', 'C'])) {
                    $totalTidakHadir++;
                }

                $karyawanData[] = [
                    'status_hadir' => $statusHadir,
                    'is_lembur' => $is_lembur,
                    'jam_masuk_karyawan' => $jamMasukKaryawan,
                    'jam_masuk_kantor' => $jamMasukKantor,
                    'total_terlambat_hari_ini' => $keterlambatanPerTanggal[$i] ?? 0, // Tambahkan info keterlambatan per tanggal
                ];
            }

            // Tambahkan total ke karyawanData
            $karyawanData[] = [
                'status_hadir' => null,
                'jam_masuk_karyawan' => null,
                'jam_masuk_kantor' => null,
                'total_hadir' => $totalHadir,
            ];
            $karyawanData[] = [
                'status_hadir' => null,
                'jam_masuk_karyawan' => null,
                'jam_masuk_kantor' => null,
                'total_terlambat' => $totalTerlambat,
            ];
            $karyawanData[] = [
                'status_hadir' => null,
                'jam_masuk_karyawan' => null,
                'jam_masuk_kantor' => null,
                'detik_terlambat' => $detikTerlambat,
            ];
            // $karyawanData[] = [
            //     'status_hadir' => null,
            //     'jam_masuk_karyawan' => null,
            //     'jam_masuk_kantor' => null,
            //     'total_tidak_hadir' => $totalTidakHadir, // Tambahkan total tidak hadir
            // ];

            // dd($karyawanData);
            $hasil[] = $karyawanData;

            // Reset variabel untuk karyawan berikutnya
            $totalTerlambat = 0;
            $totalHadir = 0;
            $detikTerlambat = 0;
            $totalTidakHadir = 0; // Reset total tidak hadir
        }



        $rekapanAbsensi = [];
        $tanggalBulan = range(1, date('t', strtotime("$tahun-$bulan-01")));

        $dataAbsensiHadir = Absensi::find()
            ->select(['absensi.id_absensi', 'absensi.tanggal', 'absensi.kode_status_hadir'])
            ->asArray()
            ->leftJoin('{{%karyawan}} k', 'absensi.id_karyawan = k.id_karyawan')
            ->where(['kode_status_hadir' => 'H'])
            ->andWhere(['k.is_aktif' => 1])
            ->andWhere(['between', 'tanggal', "$tahun-$bulan-01", "$tahun-$bulan-" . date('t', strtotime("$tahun-$bulan-01"))])
            ->all();


        foreach ($dataAbsensiHadir as $absensi) {
            $tanggal = date('j', strtotime($absensi['tanggal']));
            $rekapanAbsensi[$tanggal] = isset($rekapanAbsensi[$tanggal]) ? $rekapanAbsensi[$tanggal] + 1 : 1;
        }


        foreach ($tanggalBulan as $tanggal) {
            if (!isset($rekapanAbsensi[$tanggal])) {
                $rekapanAbsensi[$tanggal] = 0;
            }
        }
        $totalAbsensiHadir = Absensi::find()
            ->leftJoin('{{%karyawan}} k', 'absensi.id_karyawan = k.id_karyawan')
            ->where(['kode_status_hadir' => 'H'])
            ->andWhere(['between', 'tanggal', "$tahun-$bulan-01", "$tahun-$bulan-" . date('t', strtotime("$tahun-$bulan-01"))])
            ->andWhere(['k.is_aktif' => 1])
            ->count();
        $rekapanAbsensi[] = $totalAbsensiHadir;
        ksort($rekapanAbsensi);




        return [
            'tanggal_bulanan' => $tanggal_bulanan,
            'hasil' => $hasil,
            'rekapanAbsensi' => $rekapanAbsensi,
            'karyawanTotal' => $karyawanTotal,
            'keterlambatanPerTanggal' => $keterlambatanPerTanggal,

        ];
    }



    public function actionView($id_absensi)
    {
        $model = $this->findModel($id_absensi);
        $atasanKaryawan = AtasanKaryawan::find()->where(['id_karyawan' => $model['id_karyawan']])->one();
        if ($atasanKaryawan == null) {
            Yii::$app->session->setFlash('error', 'Mohon Untuk Menambahkan Data Atasan Karyawan dan Penempatan Terlebih Dahulu');
            return $this->redirect(['index']);
        }
        $alamat = $atasanKaryawan->masterLokasi;

        return $this->render('view', [
            'model' => $model,
            'alamat' => $alamat
        ]);
    }


    public function actionCreate()
    {
        $model = new Absensi();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_absensi' => $model->id_absensi]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing Absensi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_absensi Id Absensi
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_absensi)
    {
        $model = $this->findModel($id_absensi);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_absensi' => $model->id_absensi]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Absensi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_absensi Id Absensi
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_absensi)
    {
        $this->findModel($id_absensi)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Absensi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_absensi Id Absensi
     * @return Absensi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_absensi)
    {
        if (($model = Absensi::findOne(['id_absensi' => $id_absensi])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
