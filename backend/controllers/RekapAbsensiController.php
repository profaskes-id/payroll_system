<?php

namespace backend\controllers;

use backend\models\Absensi;
use backend\models\AbsensiSearch;
use backend\models\AtasanKaryawan;
use backend\models\Karyawan;
use backend\models\TotalHariKerja;
use DateInterval;
use DateTime;
use Exception;
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


    function getTanggalKerjaSampaiHariIni($work_days_type = 5)
    {
        // ?megambil hariyang terlewati dari sekarang
        $result = [];
        $current_month = date('m');
        $current_year = date('Y');
        $current_day = date('d');

        // Define holiday days based on work days type
        $holiday_days = match ($work_days_type) {
            4 => [5, 6, 0], // Friday(5), Saturday(6), Sunday(0)
            5 => [6, 0],    // Saturday(6), Sunday(0)
            6 => [0],       // Sunday(0) only
            default => throw new Exception("Invalid work days type")
        };

        // Loop through dates from 1st until current day
        for ($i = 1; $i <= $current_day; $i++) {
            $date = mktime(0, 0, 0, $current_month, $i, $current_year);
            $day = date('w', $date); // Get day number (0=Sunday, 6=Saturday)

            // Add date to result if it's not a holiday
            if (!in_array($day, $holiday_days)) {
                $result[] = date('Y-m-d', $date);
            }
        }

        return $result;
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
                'absensi.is_wfh',
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
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'karyawan.kode_karyawan',
                'bg.id_bagian',
                'bg.nama_bagian',
                'dp.jabatan',
                'mk.nama_kode as jabatan',
                'thk.total_hari',
                'jk.nama_jam_kerja',
            ])
            ->asArray()
            ->where(['karyawan.is_aktif' => 1])
            ->leftJoin('jam_kerja_karyawan jkk', 'jkk.id_karyawan = karyawan.id_karyawan')
            ->leftJoin('jadwal_kerja jdk', 'jkk.id_jam_kerja = jdk.id_jam_kerja')
            // ->leftJoin('total_hari_kerja thk', 'thk.id_jam_kerja = jkk.id_jam_kerja AND thk.bulan = :bulan AND thk.tahun = :tahun', [':bulan' => $bulan, ':tahun' => $tahun])
            ->leftJoin('total_hari_kerja thk', 'thk.id_jam_kerja = jkk.id_jam_kerja ')
            ->leftJoin('jam_kerja jk', 'jk.id_jam_kerja = thk.id_jam_kerja')
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

        $keterlambatanPerTanggal = array_fill(1, $totalHari, 0);

        foreach ($dataKaryawan as $karyawan) {
            $totalHariKerja = $karyawan["total_hari"];
            $nama_jam_kerja = $karyawan["nama_jam_kerja"];
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
                $is_wfh = 0;
                $jamMasukKaryawan = null;
                $jamMasukKantor = null;

                if (!empty($absensiRecord)) {
                    $record = array_values($absensiRecord)[0];
                    $statusHadir = $record['kode_status_hadir'];
                    $is_lembur = $record['is_lembur'];
                    $is_wfh = $record['is_wfh'];
                    $jamMasukKaryawan = $record['jam_masuk'];
                    $jamMasukKantor = $record['jam_masuk_kerja'];

                    if ($statusHadir == 'H') {
                        $jamMasuk = strtotime($record['jam_masuk']);
                        $jamMasukKerja = strtotime($record['jam_masuk_kerja'] ?? "08:00:00");

                        if ($jamMasuk > $jamMasukKerja && $record['is_lembur'] == 0 && $record['is_wfh'] == 0) {
                            $totalTerlambat++;
                            $selisihDetik = $jamMasuk - $jamMasukKerja;
                            $detikTerlambat += $selisihDetik;
                            $keterlambatanPerTanggal[$i] = ($keterlambatanPerTanggal[$i] ?? 0) + 1;
                        }
                        $totalHadir++;
                    }
                    if ($statusHadir == 'DL') {
                        $totalHadir++;
                    }
                }

                $karyawanData[] = [
                    'status_hadir' => $statusHadir,
                    'is_lembur' => $is_lembur,
                    'is_wfh' => $is_wfh,
                    'jam_masuk_karyawan' => $jamMasukKaryawan,
                    'jam_masuk_kantor' => $jamMasukKantor,
                    'total_terlambat_hari_ini' => $keterlambatanPerTanggal[$i] ?? 0, // Tambahkan info keterlambatan per tanggal
                ];
            }

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

            //ambil data bulan ini yang sudah terlewati
            if($nama_jam_kerja == null) {
                                                            Yii::$app->session->setFlash('error', 'Tolong isi data jam kerja dari ' . strtoupper($karyawanData[0]['nama']) . ' terlebih dahulu , untuk saat ini data jam kerja adalah ' . "5 hari kerja yang diisi secara default");

                                                }
            $string = $nama_jam_kerja ??  "5 Hari Kerja";
            

            $work_days_type = match (true) {
                str_contains($string, "4") => 4,
                str_contains($string, "5") => 5,
                str_contains($string, "6") => 6,
                default => throw new Exception("Invalid work days type")
            };


            $today = new DateTime();
            $today->modify('+1 day');
            $currentMonth = $today->format('m');
            $currentYear = $today->format('Y');
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
            $validDates = [];
            $tomorrowDay = (int)$today->format('d');

            for ($day = $tomorrowDay; $day <= $daysInMonth; $day++) {
                $date = new DateTime("$currentYear-$currentMonth-$day");
                $string = $nama_jam_kerja ??  "5 Hari Kerja";
                $work_days_type = match (true) {
                    str_contains($string, "4") => 4,
                    str_contains($string, "5") => 5,
                    str_contains($string, "6") => 6,
                    default => throw new Exception("Invalid work days type")
                };
                $dayOfWeek = $date->format('N');

                if ($work_days_type === 5 && ($dayOfWeek == 6 || $dayOfWeek == 7)) {
                    continue;
                }
                if ($work_days_type === 6 && $dayOfWeek == 7) {
                    continue;
                }
                if ($work_days_type === 4 && ($dayOfWeek == 5 || $dayOfWeek == 6 || $dayOfWeek == 7)) {
                    continue;
                }

                $validDates[] = $date->format('Y-m-d');
            }


            if ($bulan == date('m')) {
                $totalTidakHadir = ($totalHariKerja  - count($validDates) - $totalHadir);
            } else {
                $totalTidakHadir = $totalHariKerja  - $totalHadir;
            }


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
        $rekapanAbsensi[] = 0;

        ksort($rekapanAbsensi);

        $keterlambatanPerTanggal[] = 0;


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
