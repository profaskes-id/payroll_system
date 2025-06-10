<?php

namespace backend\controllers;

use backend\models\Absensi;
use backend\models\AbsensiSearch;
use backend\models\AtasanKaryawan;
use backend\models\Karyawan;
use backend\models\MasterKode;
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
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [

                        [
                            'allow' => true,
                            'roles' => ['@'], // Allow authenticated users
                            'matchCallback' => function ($rule, $action) {
                                $user = Yii::$app->user;
                                // Check if the user does  have the 'admin' or 'super admin' role
                                return $user->can('admin') || $user->can('super_admin');
                            },
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
        // Ambil parameter dari GET jika tersedia
        $tanggal_awal = Yii::$app->request->get('tanggal_awal');
        $tanggal_akhir = Yii::$app->request->get('tanggal_akhir');

        $tanggal_cut_of = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one()['nama_kode'];



        // Jika GET tidak ada, set default: 27 bulan lalu - 26 bulan ini
        if (!$tanggal_awal || !$tanggal_akhir) {
            $today = new \DateTime();

            // Buat objek tanggal_awal dari tanggal 27 bulan lalu
            $tanggal_awal_dt = (new \DateTime('first day of last month'))->setDate(
                (int)$today->format('Y'),
                (int)$today->format('m') - 1,
                (int) $tanggal_cut_of
            );
            $tanggal_awal = $tanggal_awal_dt->format('Y-m-d');

            // Tanggal akhir: 26 bulan ini
            $tanggal_akhir_dt = (clone $today)->setDate(
                (int)$today->format('Y'),
                (int)$today->format('m'),
                (int)($tanggal_cut_of - $tanggal_cut_of - 1)
            );
            $tanggal_akhir = $tanggal_akhir_dt->format('Y-m-d');
        }

        // Debug untuk verifikasi
        // dd($tanggal_awal, $tanggal_akhir); // Uncomment jika ingin cek

        // Ambil data rekapan berdasarkan tanggal
        $data = $this->RekapData([
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ]);

        return $this->render('index', [
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'hasil' => $data['hasil'],
            'rekapanAbsensi' => $data['rekapanAbsensi'],
            'tanggal_bulanan' => $data['tanggal_bulanan'],
            'karyawanTotal' => $data['karyawanTotal'],
            'keterlambatanPerTanggal' => $data['keterlambatanPerTanggal'],
        ]);
    }






    public function actionExel()
    {

        $tanggal_awal = Yii::$app->request->get('tanggal_awal');
        $tanggal_akhir = Yii::$app->request->get('tanggal_akhir');

        $tanggal_cut_of = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one()['nama_kode'];


        // Jika GET tidak ada, set default: 27 bulan lalu - 26 bulan ini
        if (!$tanggal_awal || !$tanggal_akhir) {
            $today = new \DateTime();

            // Buat objek tanggal_awal dari tanggal 27 bulan lalu
            $tanggal_awal_dt = (new \DateTime('first day of last month'))->setDate(
                (int)$today->format('Y'),
                (int)$today->format('m') - 1,
                (int) $tanggal_cut_of

            );
            $tanggal_awal = $tanggal_awal_dt->format('Y-m-d');

            // Tanggal akhir: 26 bulan ini
            $tanggal_akhir_dt = (clone $today)->setDate(
                (int)$today->format('Y'),
                (int)$today->format('m'),
                (int)($tanggal_cut_of - $tanggal_cut_of - 1)
            );
            $tanggal_akhir = $tanggal_akhir_dt->format('Y-m-d');
        }


        $data = $this->RekapData([
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ]);



        return $this->renderPartial('exel2', [
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'hasil' => $data['hasil'],
            'rekapanAbsensi' => $data['rekapanAbsensi'],
            'tanggal_bulanan' => $data['tanggal_bulanan'],
            'karyawanTotal' => $data['karyawanTotal'],
            'keterlambatanPerTanggal' => $data['keterlambatanPerTanggal'],

        ]);
    }

    public function actionReport()
    {
        $tanggal_awal = Yii::$app->request->get('tanggal_awal');
        $tanggal_akhir = Yii::$app->request->get('tanggal_akhir');

        $tanggal_cut_of = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one()['nama_kode'];


        // Jika GET tidak ada, set default: 27 bulan lalu - 26 bulan ini

        if (!$tanggal_awal || !$tanggal_akhir) {
            $today = new \DateTime();

            // Buat objek tanggal_awal dari tanggal 27 bulan lalu
            $tanggal_awal_dt = (new \DateTime('first day of last month'))->setDate(
                (int)$today->format('Y'),
                (int)$today->format('m') - 1,
                (int) $tanggal_cut_of

            );
            $tanggal_awal = $tanggal_awal_dt->format('Y-m-d');

            // Tanggal akhir: 26 bulan ini
            $tanggal_akhir_dt = (clone $today)->setDate(
                (int)$today->format('Y'),
                (int)$today->format('m'),
                (int)($tanggal_cut_of - $tanggal_cut_of - 1)
            );
            $tanggal_akhir = $tanggal_akhir_dt->format('Y-m-d');
        }


        // Ambil data rekapan berdasarkan tanggal
        $data = $this->RekapData([
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ]);


        $content = $this->renderPartial('_report', [
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
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
            'options' => ['title' => 'Report Rekap Absensi  dari ' . $tanggal_awal . ' sampai ' . $tanggal_akhir],
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
        $model  = new Absensi();
        $karyawan = new Karyawan();


        $firstDayOfMonth = $params['tanggal_awal'];
        $lastDayOfMonth = $params['tanggal_akhir'];

        // ! Get total karyawan
        $karyawanTotal = $karyawan::find()->where(['is_aktif' => 1])->count();
        // dd($karyawanTotal);
        //! mendapatkan seluruh data absensi karyawan,jam-karyawan dari firstDayOfMonth - lastDayOfMonth
        $absensi = $model->getAllAbsensiFromFirstAndLastMonth($model, $firstDayOfMonth, $lastDayOfMonth);
        // dd($absensi);
        //    ! get all data dari tanggal awal dan akhir bulan
        $tanggal_bulanan = $model->getTanggalFromFirstAndLastMonth($firstDayOfMonth, $lastDayOfMonth);
        $dataKaryawan = $model->getAllDetailDataKaryawan($karyawan);

        // memasukan absensi ke masing masing data karyawan
        $absensiAndTelat = $model->getIncludeKaryawanAndAbsenData($dataKaryawan, $absensi, $firstDayOfMonth, $lastDayOfMonth, $tanggal_bulanan);
        $keterlambatanPerTanggal = $absensiAndTelat['keterlambatanPerTanggal'];

        $rekapanAbsensi = [];
        $tanggalBulan = $tanggal_bulanan;
        $firstDayOfMonth = $params['tanggal_awal'];  // "2025-01-27"
        $lastDayOfMonth = $params['tanggal_akhir'];  // "2025-02-26"

        // Ambil data absensi
        $dataAbsensiHadir = $model->getAbsnesiDataWereHadir($model, $firstDayOfMonth, $lastDayOfMonth);

        // Siapkan tanggal bulanan (semua tanggal dari awal ke akhir)
        $tanggalBulan = [];
        $start = new DateTime($firstDayOfMonth);
        $end = new DateTime($lastDayOfMonth);
        while ($start <= $end) {
            $tanggalBulan[] = $start->format('Y-m-d');
            $start->modify('+1 day');
        }

        // Hitung jumlah absensi hadir per tanggal
        $rekapanAbsensi = [];
        foreach ($dataAbsensiHadir as $absensi) {
            $tanggal = $absensi['tanggal'];
            $rekapanAbsensi[$tanggal] = isset($rekapanAbsensi[$tanggal]) ? $rekapanAbsensi[$tanggal] + 1 : 1;
        }

        // Pastikan setiap tanggal ada, kalau tidak, isi 0
        foreach ($tanggalBulan as $tanggal) {
            if (!isset($rekapanAbsensi[$tanggal])) {
                $rekapanAbsensi[$tanggal] = 0;
            }
        }

        // Urutkan berdasarkan tanggal
        ksort($rekapanAbsensi);

        // Hitung total hadir
        $totalAbsensiHadir = count($dataAbsensiHadir);
        $rekapanAbsensi[] = $totalAbsensiHadir;



        return [
            'tanggal_bulanan' => $tanggal_bulanan,
            'hasil' => $absensiAndTelat['hasil'],
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
