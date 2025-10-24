<?php

namespace backend\controllers;

use backend\models\Absensi;
use backend\models\Karyawan;
use backend\models\MasterKode;
use backend\models\Tanggal;
use DateInterval;
use DatePeriod;
use Yii;
use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use kartik\mpdf\Pdf;

class RekapPerKaryawanController extends \yii\web\Controller
{


    public function actionIndex()
    {
        $request = Yii::$app->request;

        $model = new Karyawan();
        $id_karyawan = $request->get('id_karyawan'); // <- dari form Select2

        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
        $tanggal_awal =  Yii::$app->request->get() == [] ? $firstDayOfMonth :  Yii::$app->request->get()['tanggal_awal'];
        $tanggal_akhir =  Yii::$app->request->get() == [] ? $lastdate :  Yii::$app->request->get()['tanggal_akhir'];



        $karyawan = null;
        $dataAbsensi = [];

        if ($id_karyawan) {
            $karyawan = Karyawan::find()
                ->select([
                    'karyawan.id_karyawan',
                    'karyawan.nama',
                    'karyawan.kode_karyawan',
                    'bg.nama_bagian',
                    'dp.jabatan',
                    'mk.nama_kode as jabatan',
                    'jk.nama_jam_kerja',
                ])
                ->asArray()
                ->where(['karyawan.id_karyawan' => $id_karyawan, 'karyawan.is_aktif' => 1])
                ->leftJoin('jam_kerja_karyawan jkk', 'jkk.id_karyawan = karyawan.id_karyawan')
                ->leftJoin('jam_kerja jk', 'jk.id_jam_kerja = jkk.id_jam_kerja')
                ->leftJoin('{{%data_pekerjaan}} dp', 'karyawan.id_karyawan = dp.id_karyawan')
                ->leftJoin('{{%bagian}} bg', 'dp.id_bagian = bg.id_bagian')
                ->leftJoin('{{%master_kode}} mk', 'mk.nama_group = "jabatan" and dp.jabatan = mk.kode')
                ->orderBy(['bg.id_bagian' => SORT_DESC, 'karyawan.nama' => SORT_ASC])
                ->one();

            if ($karyawan) {
                $dataAbsensi = Absensi::find()
                    ->select([
                        'absensi.id_absensi',
                        'absensi.tanggal',
                        'absensi.jam_masuk',
                        'absensi.jam_pulang',
                        'absensi.kode_status_hadir',
                        'absensi.keterangan',
                        'shift_kerja.nama_shift',
                    ])
                    ->leftJoin('shift_kerja', 'absensi.id_shift = shift_kerja.id_shift_kerja')
                    ->where(['id_karyawan' => $karyawan['id_karyawan']])
                    ->andWhere(['between', 'tanggal', $tanggal_awal, $tanggal_akhir])
                    ->asArray()
                    ->all();
            }
        }

        return $this->render('index', [
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'karyawan' => $karyawan,
            'model' => $model,
            'dataAbsensi' => $dataAbsensi,
        ]);
    }




    public function actionExelOne()
    {
        $request = Yii::$app->request;

        $id_karyawan = $request->get('id_karyawan');
        $tanggal_awal = $request->get('tanggal_awal') ?? '2025-01-01';
        $tanggal_akhir = $request->get('tanggal_akhir') ?? '2025-01-31';

        if (!$id_karyawan) {
            throw new \yii\web\BadRequestHttpException("ID Karyawan harus diisi.");
        }

        // Ambil data karyawan
        $karyawanData = Karyawan::find()
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'karyawan.kode_karyawan',
                'bg.nama_bagian AS bagian',
                'dp.jabatan',
            ])
            ->where(['karyawan.id_karyawan' => $id_karyawan])
            ->leftJoin('data_pekerjaan dp', 'dp.id_karyawan = karyawan.id_karyawan')
            ->leftJoin('bagian bg', 'dp.id_bagian = bg.id_bagian')
            ->asArray()
            ->one();

        if (!$karyawanData) {
            throw new \yii\web\NotFoundHttpException("Data karyawan tidak ditemukan.");
        }

        // Ambil data absensi
        $absensi = Absensi::find()
            ->select([
                'tanggal',
                'jam_masuk',
                'jam_pulang',
                'id_shift',
                'keterangan'
            ])
            ->where(['id_karyawan' => $id_karyawan])
            ->andWhere(['between', 'tanggal', $tanggal_awal, $tanggal_akhir])
            ->orderBy(['tanggal' => SORT_ASC])
            ->asArray()
            ->all();

        // Buat array tanggal bulanan
        $tanggal_bulanan = [];
        $period = new DatePeriod(
            new DateTime($tanggal_awal),
            new DateInterval('P1D'),
            (new DateTime($tanggal_akhir))->modify('+1 day')
        );

        foreach ($period as $dt) {
            $tanggal_bulanan[] = $dt->format('Y-m-d');
        }

        // Gabungkan data karyawan + absensi
        $karyawan = [];
        $karyawan[] = $karyawanData;

        // Isi data per tanggal
        foreach ($tanggal_bulanan as $tgl) {
            $found = null;
            foreach ($absensi as $ab) {
                if ($ab['tanggal'] === $tgl) {
                    $found = [
                        'jam_masuk_karyawan' => $ab['jam_masuk'],
                        'jam_pulang' => $ab['jam_pulang'],
                        'id_shift' => $ab['id_shift'],
                        'keterangan' => $ab['keterangan']
                    ];
                    break;
                }
            }
            $karyawan[] = $found;
        }

        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Panggil fungsi isi data
        $tanggal = new Tanggal();

        $this->fillSheetData($sheet, $karyawan, $tanggal_bulanan, $tanggal, $tanggal_awal, $tanggal_akhir);

        // Output Excel
        $filename = 'Rekap_Absensi_' . $karyawanData['kode_karyawan'] . '.xlsx';

        // Clean buffer sebelum output
        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }



    public function actionExelAll()
    {
        $request = Yii::$app->request;

        $id_karyawan_array = $request->get('Karyawan')['id_karyawan'] ?? [];
        $tanggal_awal = $request->get('tanggal_awal') ?? '2025-01-01';
        $tanggal_akhir = $request->get('tanggal_akhir') ?? '2025-01-31';

        if (empty($id_karyawan_array)) {
            throw new \yii\web\BadRequestHttpException("Minimal pilih satu karyawan.");
        }

        // HANYA 1 KARYAWAN: Tampilkan ke view
        if (count($id_karyawan_array) === 1) {
            $id_karyawan = $id_karyawan_array[0];

            // Ambil data karyawan
            $karyawanData = Karyawan::find()
                ->select(['karyawan.id_karyawan', 'karyawan.nama', 'karyawan.kode_karyawan', 'bg.nama_bagian AS bagian', 'mk.nama_kode AS jabatan'])
                ->where(['karyawan.id_karyawan' => $id_karyawan])
                ->leftJoin('data_pekerjaan dp', 'dp.id_karyawan = karyawan.id_karyawan')
                ->leftJoin('bagian bg', 'dp.id_bagian = bg.id_bagian')
                ->leftJoin('master_kode mk', 'mk.nama_group = "jabatan" and dp.jabatan = mk.kode')
                ->asArray()
                ->one();



            // Ambil absensi

            $absensi = Absensi::find()
                ->select(['tanggal', 'jam_masuk', 'jam_pulang', 'id_shift', 'keterangan'])
                ->where(['id_karyawan' => $id_karyawan])
                ->andWhere(['between', 'tanggal', $tanggal_awal, $tanggal_akhir])
                ->orderBy(['tanggal' => SORT_ASC])
                ->asArray()
                ->all();



            return $this->renderPartial('_exel', [
                'karyawan' => $karyawanData,
                'absensi' => $absensi,
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
            ]);
        }

        // JIKA LEBIH DARI 1 KARYAWAN:
        $maxPerFile = 5;
        $chunks = array_chunk($id_karyawan_array, $maxPerFile);
        $files = [];
        $tanggal = new Tanggal();

        foreach ($chunks as $chunkIndex => $chunk) {
            $spreadsheet = new Spreadsheet();
            $sheetIndex = 0;

            foreach ($chunk as $id_karyawan) {
                // Ambil data karyawan
                $karyawanData = Karyawan::find()
                    ->select(['karyawan.id_karyawan', 'karyawan.nama', 'karyawan.kode_karyawan', 'bg.nama_bagian AS bagian', 'dp.jabatan'])
                    ->where(['karyawan.id_karyawan' => $id_karyawan])
                    ->leftJoin('data_pekerjaan dp', 'dp.id_karyawan = karyawan.id_karyawan')
                    ->leftJoin('bagian bg', 'dp.id_bagian = bg.id_bagian')
                    ->asArray()
                    ->one();

                if (!$karyawanData) {
                    continue;
                }

                // Ambil absensi
                $absensi = Absensi::find()
                    ->select(['tanggal', 'jam_masuk', 'jam_pulang', 'id_shift', 'keterangan'])
                    ->where(['id_karyawan' => $id_karyawan])
                    ->andWhere(['between', 'tanggal', $tanggal_awal, $tanggal_akhir])
                    ->orderBy(['tanggal' => SORT_ASC])
                    ->asArray()
                    ->all();

                // Buat array tanggal bulanan
                $tanggal_bulanan = [];
                $period = new \DatePeriod(
                    new \DateTime($tanggal_awal),
                    new \DateInterval('P1D'),
                    (new \DateTime($tanggal_akhir))->modify('+1 day')
                );

                foreach ($period as $dt) {
                    $tanggal_bulanan[] = $dt->format('Y-m-d');
                }

                // Gabungkan absensi ke karyawan
                $karyawan = [];
                $karyawan[] = $karyawanData;

                foreach ($tanggal_bulanan as $tgl) {
                    $found = null;
                    foreach ($absensi as $ab) {
                        if ($ab['tanggal'] === $tgl) {
                            $found = [
                                'jam_masuk_karyawan' => $ab['jam_masuk'],
                                'jam_pulang' => $ab['jam_pulang'],
                                'id_shift' => $ab['id_shift'],
                                'keterangan' => $ab['keterangan']
                            ];
                            break;
                        }
                    }
                    $karyawan[] = $found;
                }

                // Tambahkan sheet
                if ($sheetIndex === 0) {
                    $sheet = $spreadsheet->getActiveSheet();
                } else {
                    $sheet = $spreadsheet->createSheet($sheetIndex);
                }

                $sheetTitle = substr($karyawanData['nama'], 0, 31);
                $sheet->setTitle($sheetTitle);
                $this->fillSheetData($sheet, $karyawan, $tanggal_bulanan, $tanggal, $tanggal_awal, $tanggal_akhir);
                $sheetIndex++;
            }

            // Simpan ke file sementara
            $filename = 'Rekap_Absensi_Karyawan_' . ($chunkIndex + 1) . '.xlsx';
            $filePath = Yii::getAlias('@runtime') . '/' . $filename;

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filePath);

            $files[] = $filePath;
        }

        // Jika hanya 1 file, langsung download
        if (count($files) === 1) {
            Yii::$app->response->sendFile($files[0])->send();
            return;
        }

        // Jika lebih dari 1 file, zip semua
        $zipPath = Yii::getAlias('@runtime') . '/Rekap_Absensi_Multi_File.zip';
        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        Yii::$app->response->sendFile($zipPath)->send();
        return;
    }



    public function actionPdf()
    {
        $request = Yii::$app->request;

        $id_karyawan = $request->get('id_karyawan');
        $tanggal_awal = $request->get('tanggal_awal') ?? '2025-01-01';
        $tanggal_akhir = $request->get('tanggal_akhir') ?? '2025-01-31';

        $karyawan = null;
        $dataAbsensi = [];

        if ($id_karyawan) {
            $karyawan = Karyawan::find()
                ->select([
                    'karyawan.id_karyawan',
                    'karyawan.nama',
                    'karyawan.kode_karyawan',
                    'bg.nama_bagian',
                    'dp.jabatan',
                    'mk.nama_kode as jabatan',
                    'jk.nama_jam_kerja',
                ])
                ->asArray()
                ->where(['karyawan.id_karyawan' => $id_karyawan, 'karyawan.is_aktif' => 1])
                ->leftJoin('jam_kerja_karyawan jkk', 'jkk.id_karyawan = karyawan.id_karyawan')
                ->leftJoin('jam_kerja jk', 'jk.id_jam_kerja = jkk.id_jam_kerja')
                ->leftJoin('{{%data_pekerjaan}} dp', 'karyawan.id_karyawan = dp.id_karyawan')
                ->leftJoin('{{%bagian}} bg', 'dp.id_bagian = bg.id_bagian')
                ->leftJoin('{{%master_kode}} mk', 'mk.nama_group = "jabatan" and dp.jabatan = mk.kode')
                ->one();

            if ($karyawan) {
                $dataAbsensi = Absensi::find()
                    ->select([
                        'absensi.tanggal',
                        'absensi.jam_masuk',
                        'absensi.jam_pulang',
                        'absensi.kode_status_hadir',
                        'absensi.keterangan',
                        'shift_kerja.nama_shift',
                    ])
                    ->leftJoin('shift_kerja', 'absensi.id_shift = shift_kerja.id_shift_kerja')
                    ->where(['id_karyawan' => $karyawan['id_karyawan']])
                    ->andWhere(['between', 'tanggal', $tanggal_awal, $tanggal_akhir])
                    ->orderBy(['tanggal' => SORT_ASC])
                    ->asArray()
                    ->all();
            }
        }

        // Render to PDF using _report partial
        $content = $this->renderPartial('pdf', [
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'karyawan' => $karyawan,
            'dataAbsensi' => $dataAbsensi,
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => 'Report Rekap Absensi dari ' . $tanggal_awal . ' sampai ' . $tanggal_akhir],
            'methods' => [
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
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
        $detik = $selisih % 60;

        return sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
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
}
