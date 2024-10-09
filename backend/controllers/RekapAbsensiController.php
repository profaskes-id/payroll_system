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
            // Menonaktifkan CSRF verification untuk aksi 'view'
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

        ]);
    }

    public function actionReport()
    {
        $bulan = date('m');
        $tahun = date('Y');
        $data = $this->RekapData();



        // MASUKAN KE PDF
        $content = $this->renderPartial('_report', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'hasil' => $data['hasil'],
            'rekapanAbsensi' => $data['rekapanAbsensi'],
            'tanggal_bulanan' => $data['tanggal_bulanan'],
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Report Rekap Absensi ' . date('F')],
            'methods' => [
                // 'SetHeader' => ['Data Rekap Absensi ' . date('F')],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }




    public function RekapData($params = null)
    {
        // Jika params tidak null, maka ambil bulan dan tahun dari params
        if ($params != null) {
            $bulan = $params['bulan'];
            $tahun = $params['tahun'];
        } else {
            // Jika params null, maka ambil bulan dan tahun sekarang
            $bulan = date('m');
            $tahun = date('Y');
        }



        // dd($bulan, $tahun);
        // Buat tanggal awal dan akhir bulan
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, 1, $tahun));
        $lastDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, date('t', mktime(0, 0, 0, $bulan, 1, $tahun)), $tahun));

        // Mengambil data absensi bulan ini
        $absensi = Absensi::find()
            ->select(['absensi.id_karyawan', 'absensi.tanggal', 'absensi.jam_masuk', 'absensi.kode_status_hadir', 'absensi.jam_masuk', 'jkk.id_jam_kerja',   'jdk.jam_masuk AS jam_masuk_kerja', 'jdk.nama_hari'])
            ->asArray()
            ->leftJoin('{{%jam_kerja_karyawan}} jkk', 'absensi.id_karyawan = jkk.id_karyawan')
            ->leftJoin('{{%jadwal_kerja}} jdk', 'jkk.id_jam_kerja = jdk.id_jam_kerja')
            ->where(['jdk.nama_hari' => date('l', strtotime('absensi.tanggal'))])
            ->where(['>=', 'tanggal', $firstDayOfMonth])
            ->andWhere(['<=', 'tanggal', $lastDayOfMonth])
            ->all();

        // dd($absensi);
        // Buat array tanggal bulanan
        $tanggal_bulanan = array();
        for ($i = 1; $i <= date('t', mktime(0, 0, 0, $bulan, 1, $tahun)); $i++) {
            $tanggal_bulanan[] = date('d', mktime(0, 0, 0, $bulan, $i, $tahun));
        }
        // dd($tanggal_bulanan);

        // Mengambil data karyawan
        $dataKaryawan = Karyawan::find()->select(['karyawan.id_karyawan', 'karyawan.nama', 'karyawan.kode_karyawan', 'bg.id_bagian', 'bg.nama_bagian', 'dp.jabatan', 'mk.nama_kode as jabatan'])
            ->asArray()
            ->leftJoin('{{%data_pekerjaan}} dp', 'dp.id_karyawan = dp.id_karyawan')
            ->leftJoin('{{%bagian}} bg', 'dp.id_bagian = bg.id_bagian')
            ->leftJoin('{{%master_kode}} mk', 'mk.nama_group = "jabatan" and dp.jabatan = mk.kode')
            ->orderBy(['nama' => SORT_ASC])
            ->all();

        // dd($dataKaryawan[0]->dataPekerjaans[0]->bagian);
        // Buat array hasild
        $hasil = [];
        $totalHari = date('t', mktime(0, 0, 0, $bulan, 1, $tahun));
        foreach ($dataKaryawan as $karyawan) {

            $karyawanData = [
                [
                    "id_karyawan" => ["id_karyawan"],
                    "nama" =>   $karyawan["nama"],
                    "kode_karyawan" =>  $karyawan["kode_karyawan"],
                    "id_bagian" => $karyawan["id_bagian"],
                    "bagian" => $karyawan["nama_bagian"],
                    "jabatan" => $karyawan["jabatan"],
                ],
            ];
            for ($i = 1; $i <= $totalHari; $i++) {
                $tanggal = date('Y-m-d', mktime(0, 0, 0, $bulan, $i, $tahun));
                $statusHadir = null; // Default jika tidak ada data
                $jamMasukKaryawan = null; // Default jika tidak ada data
                $jamMasukKantor = null; // Default jika tidak ada data
                foreach ($absensi as $record) {
                    if ($record['id_karyawan'] == $karyawan['id_karyawan'] && $record['tanggal'] == $tanggal) {
                        $statusHadir = $record['kode_status_hadir'];
                        $jamMasukKaryawan = $record['jam_masuk'];
                        $jamMasukKantor = $record['jam_masuk_kerja'];
                        break;
                    }
                }
                $karyawanData[] = [
                    'status_hadir' => $statusHadir,
                    'jam_masuk_karyawan' => $jamMasukKaryawan,
                    'jam_masuk_kantor' => $jamMasukKantor
                ]; // Wrap status hadir in an array
            }
            $hasil[] = $karyawanData;
        }



        $rekapanAbsensi = [];
        $tanggalBulan = range(1, date('t', strtotime("$tahun-$bulan-01"))); // Mendapatkan semua tanggal dalam bulan

        // Ambil data kehadiran dalam satu query
        $dataAbsensiHadir = Absensi::find()->where(['kode_status_hadir' => 'H'])
            ->andWhere(['between', 'tanggal', "$tahun-$bulan-01", "$tahun-$bulan-" . date('t', strtotime("$tahun-$bulan-01"))])
            ->all();

        // Hitung kehadiran dan simpan dalam array
        foreach ($dataAbsensiHadir as $absensi) {
            $tanggal = date('j', strtotime($absensi->tanggal)); // Ambil tanggal dari record absensi
            $rekapanAbsensi[$tanggal] = isset($rekapanAbsensi[$tanggal]) ? $rekapanAbsensi[$tanggal] + 1 : 1;
        }

        // Pastikan setiap tanggal terisi, jika tidak ada kehadiran, isi dengan 0
        foreach ($tanggalBulan as $tanggal) {
            if (!isset($rekapanAbsensi[$tanggal])) {
                $rekapanAbsensi[$tanggal] = 0; // Isi 0 jika tidak ada kehadiran
            }
        }
        // Mengurutkan array berdasarkan tanggal
        ksort($rekapanAbsensi);
        return ['tanggal_bulanan' => $tanggal_bulanan, 'hasil' => $hasil, 'rekapanAbsensi' => $rekapanAbsensi];
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

    /**
     * Creates a new Absensi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
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
