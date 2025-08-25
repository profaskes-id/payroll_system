<?php

namespace backend\controllers;

use backend\models\Absensi;
use backend\models\AtasanKaryawan;
use backend\models\Karyawan;
use backend\models\MasterKode;
use backend\models\Tanggal;
use DateTime;
use Exception;
use kartik\mpdf\Pdf;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;
    use PhpOffice\PhpSpreadsheet\Style\Fill;

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
            $tanggal_awal_dt = (new \DateTime('first day of last month'))
                ->modify('+' . ($tanggal_cut_of - 1) . ' days');
            $tanggal_awal = $tanggal_awal_dt->format('Y-m-d');

            // Tanggal akhir: 26 bulan ini
            $tanggal_akhir_dt = (new \DateTime('first day of this month'))
                ->modify('+' . ($tanggal_cut_of - 1 - 1) . ' days');
            $tanggal_akhir = $tanggal_akhir_dt->format('Y-m-d');
        }


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

    // dd($data['hasil']);
    
    
    
    $spreadsheet = new Spreadsheet();
    $spreadsheet->removeSheetByIndex(0); // Hapus sheet default
    
    $tanggal = new Tanggal();
    $sheetIndex = 0;
    
    foreach ($data['hasil'] as $karyawan) {
        if (is_array($karyawan) && count($karyawan) > 0) {
            // Buat sheet baru untuk setiap karyawan
            $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
                $spreadsheet, 
                substr($karyawan[0]['nama'], 0, 31) // Excel membatasi 31 karakter untuk nama sheet
            );
            $spreadsheet->addSheet($sheet, $sheetIndex);
            
            // Isi data ke sheet
            $this->fillSheetData($sheet, $karyawan, $data['tanggal_bulanan'], $tanggal, $tanggal_awal, $tanggal_akhir);
            
            $sheetIndex++;
        }
    }
    
    // Set header dan kirim file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Rekapan-absensi-per-karyawan(' . 
           $tanggal->getIndonesiaFormatTanggal($tanggal_awal) . ' - ' . 
           $tanggal->getIndonesiaFormatTanggal($tanggal_akhir) . ').xls"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

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

private function fillSheetData($sheet, $karyawan, $tanggal_bulanan, $tanggal, $tanggal_awal, $tanggal_akhir)
{
    // Set judul
    $sheet->mergeCells('A1:' . $this->getColumnName(count($tanggal_bulanan) * 2 + 4) . '1');
    $sheet->setCellValue('A1', 'Rekapan Absensi dari ' . 
        $tanggal->getIndonesiaFormatTanggal($tanggal_awal) . ' S/D ' . 
        $tanggal->getIndonesiaFormatTanggal($tanggal_akhir));
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    // Header informasi karyawan
    $sheet->setCellValue('A3', 'Nama');
    $sheet->setCellValue('B3', $karyawan[0]['nama']);
    $sheet->setCellValue('A4', 'Kode Karyawan');
    $sheet->setCellValue('B4', $karyawan[0]['kode_karyawan']);
    $sheet->setCellValue('A5', 'Bagian');
    $sheet->setCellValue('B5', $karyawan[0]['bagian']);
    $sheet->setCellValue('A6', 'Jabatan');
    $sheet->setCellValue('B6', $karyawan[0]['jabatan']);
    
    // Header tabel absensi
    $row = 8;
    $sheet->setCellValue('A' . $row, 'Tanggal');
    $sheet->setCellValue('B' . $row, 'Hari');
    $sheet->setCellValue('C' . $row, 'Masuk');
    $sheet->setCellValue('D' . $row, 'Pulang');
    $sheet->setCellValue('E' . $row, 'Total Jam');
    $sheet->setCellValue('F' . $row, 'Jenis Shift');
    $sheet->setCellValue('G' . $row, 'Keterangan');
    
    // Style header
    $headerStyle = [
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E0E0E0']
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN
            ]
        ]
    ];
    $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray($headerStyle);
    
    // Isi data absensi per tanggal
    $row++;
    $total_hadir = 0;
    // $total_terlambat = 0;
    // $total_detik_terlambat = 0;
    // $total_lembur = 0;
    // $total_jam_lembur = 0;
    $total_jam_kerja = 0; // Variabel untuk menghitung total jam kerja
    
    foreach ($tanggal_bulanan as $index => $tgl) {
        $data_index = $index + 1; // Index data dimulai dari 1 (0 adalah info karyawan)
        
        if (isset($karyawan[$data_index]) && $karyawan[$data_index] !== null) {
            $data = $karyawan[$data_index];
            
            $day_of_week = date('w', strtotime($tgl));
            $hari = $this->getHariIndonesia($day_of_week);
            
            $sheet->setCellValue('A' . $row, date('d-m-Y', strtotime($tgl)));
            $sheet->setCellValue('B' . $row, $hari);
            $sheet->setCellValue('C' . $row, isset($data['jam_masuk_karyawan']) ? $data['jam_masuk_karyawan'] : '');
            $sheet->setCellValue('D' . $row, isset($data['jam_pulang']) ? $data['jam_pulang'] : '');

            // Hitung total jam kerja
            $total_jam_hari = '';
            if (!empty($data['jam_masuk_karyawan']) && !empty($data['jam_pulang'])) {
                $total_jam_hari = $this->hitungTotalJam($data['jam_masuk_karyawan'], $data['jam_pulang']);
                
                // Tambahkan ke total jam kerja keseluruhan (dalam menit)
                list($jam, $menit) = explode(':', $total_jam_hari);
                // $total_jam_kerja += ($jam * 60) + $menit;
            }
            $sheet->setCellValue('E' . $row, $total_jam_hari);
$sheet->setCellValue('F' . $row, isset($data['id_shift']) ? $data['id_shift'] : '');
$sheet->setCellValue('G' . $row, isset($data['keterangan']) ? $data['keterangan'] : '');

            
            // Warna weekend
            if ($day_of_week == 0) {
                $sheet->getStyle('A' . $row . ':G' . $row)
                      ->getFill()
                      ->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()
                      ->setRGB('AAAAAA');
            }
            
            // Hitung total hadir
            if (!empty($data['jam_masuk_karyawan'])) {
                $total_hadir++;
            }
            
            $row++;
        }
    }
    
    // Baris total
    $row += 2;
    // $sheet->setCellValue('A' . $row, 'Total Hadir');
    // $sheet->setCellValue('B' . $row, $karyawan[count($karyawan) - 5]['total_hadir']);
    
    // $row++;
    // $sheet->setCellValue('A' . $row, 'Jumlah Terlambat');
    // $sheet->setCellValue('B' . $row, $karyawan[count($karyawan) - 4]['total_terlambat']);
    
    // $row++;
    // $sheet->setCellValue('A' . $row, 'Total Terlambat');
    // $sheet->setCellValue('B' . $row, gmdate('H:i:s', $karyawan[count($karyawan) - 3]['detik_terlambat']));
    
    // $row++;
    // $sheet->setCellValue('A' . $row, 'Total Lembur');
    // $sheet->setCellValue('B' . $row, $karyawan[count($karyawan) - 2]['total_lembur']);
    
    // $row++;
    // $sheet->setCellValue('A' . $row, 'Jumlah Jam Lembur');
    // $sheet->setCellValue('B' . $row, $karyawan[count($karyawan) - 1]['jumlah_jam_lembur']);
    
    // Tambahkan baris total jam kerja
    // $row++;
    // $sheet->setCellValue('A' . $row, 'Total Jam Kerja');
    // $total_jam = floor($total_jam_kerja / 60); // Jam
    // $total_menit = $total_jam_kerja % 60; // Menit
    // $sheet->setCellValue('B' . $row, sprintf('%d jam %02d menit', $total_jam, $total_menit));
    
    // Auto size columns
  foreach (range('A', 'G') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

}

private function hitungTotalJam($jam_masuk, $jam_pulang)
{
    if (empty($jam_masuk) || empty($jam_pulang)) {
        return '';
    }
    
    // Pastikan format jam benar (HH:MM:SS)
    $jam_masuk = $this->formatWaktu($jam_masuk);
    $jam_pulang = $this->formatWaktu($jam_pulang);
    
    $masuk = strtotime($jam_masuk);
    $pulang = strtotime($jam_pulang);
    
    // Jika jam pulang lebih kecil dari jam masuk (lembur malam)
    if ($pulang < $masuk) {
        $pulang = strtotime($jam_pulang . ' +1 day');
    }
    
    $selisih = $pulang - $masuk;
    
    $jam = floor($selisih / 3600);
    $menit = floor(($selisih % 3600) / 60);
    
    return sprintf('%02d:%02d', $jam, $menit);
}

// Fungsi bantu untuk memastikan format waktu benar
private function formatWaktu($waktu)
{
    // Jika format sudah HH:MM:SS, langsung return
    if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $waktu)) {
        return $waktu;
    }
    
    // Jika format HH:MM, tambahkan :00
    if (preg_match('/^\d{2}:\d{2}$/', $waktu)) {
        return $waktu . ':00';
    }
    
    // Default return
    return $waktu;
}
    
    private function getColumnName($index)
    {
        $letters = range('A', 'Z');
        $result = '';
        
        while ($index >= 0) {
            $result = $letters[$index % 26] . $result;
            $index = floor($index / 26) - 1;
        }
        
        return $result;
    }
    
    private function getHariIndonesia($dayOfWeek)
    {
        $hari = [
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        ];
        
        return $hari[$dayOfWeek];
    }


    public function RekapData($params = null)
    {
        $model  = new Absensi();
        $karyawan = new Karyawan();


        $firstDayOfMonth = $params['tanggal_awal'];
        $lastDayOfMonth = $params['tanggal_akhir'];

        // ! Get total karyawan
        $karyawanTotal = $karyawan::find()->where(['is_aktif' => 1])->count();

        //! mendapatkan seluruh data absensi karyawan,jam-karyawan dari firstDayOfMonth - lastDayOfMonth
        $absensi = $model->getAllAbsensiFromFirstAndLastMonth($model, $firstDayOfMonth, $lastDayOfMonth);

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
        // $totalAbsensiHadir = count($dataAbsensiHadir);
        // $rekapanAbsensi[] = $totalAbsensiHadir;



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
