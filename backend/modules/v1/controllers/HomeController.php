<?php

namespace app\modules\v1\controllers;

use backend\models\Absensi as Absensi;
use backend\models\AtasanKaryawan;
use backend\models\IzinPulangCepat;
use backend\models\JadwalKerja;
use backend\models\JamKerjaKaryawan;
use backend\models\Karyawan;
use backend\models\MasterHaribesar;
use backend\models\PengajuanLembur;
use backend\models\PengajuanWfh;
use backend\models\ShiftKerja;
use yii\rest\ActiveController;
use yii\web\Response;
use Yii;
use yii\helpers\Json;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

class HomeController extends ActiveController
{

    /**
     * Inisialisasi absensi karyawan.
     * 
     * Method ini akan mengecek beberapa kondisi absensi karyawan dan mengembalikan respons yang sesuai.
     * Kondisi-kondisi yang diperiksa adalah:
     * 1. Cek 24 jam.
     * 2. Cek shift kerja.
     * 3. Cek lembur.
     * 4. Cek WFH.
     * 
     * @param int $id_karyawan ID karyawan.
     * @return array Respons yang sesuai dengan kondisi absensi karyawan.
     */
    public function actionInitAbsensi($id_karyawan)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $dataAbsensiHariIni = $this->getAbsensiHariIni($id_karyawan);
        $lokasiPenempatan = $this->getPenempatanKaryawan($id_karyawan);
        $jamKerjaKaryawan = $this->getJamKerjaKaryawan($id_karyawan);
        $isPulangCepat = $this->getIzinPulangCepat($id_karyawan);
        $hasilLembur = $this->getLemburAktif($id_karyawan);

        // Cek 24 jam
        $is24Jam = $this->cekAbsensi24Jam($id_karyawan, $jamKerjaKaryawan);
        if ($is24Jam) {
            return $this->formatRespons24Jam($jamKerjaKaryawan, $lokasiPenempatan, $isPulangCepat);
        }

        // Cek shift kerja
        $jamKerjaToday = $this->getJamKerjaToday($jamKerjaKaryawan);
        $dataJam = [
            'karyawan' => $jamKerjaKaryawan,
            'lembur' => $hasilLembur,
        ];

        // Cek lembur
        if ($this->cekLemburHariIni($hasilLembur)) {
            return $this->formatResponsLembur($dataAbsensiHariIni, $jamKerjaKaryawan, $dataJam, $isPulangCepat);
        }

        // Cek WFH
        $isWfh = $this->cekWfhHariIni($id_karyawan);
        if ($isWfh) {
            return $this->formatResponsWfh(
                $dataAbsensiHariIni,
                $jamKerjaKaryawan,
                $dataJam,
                $isPulangCepat,
                $jamKerjaToday,
                $lokasiPenempatan
            );
        }

        // Respons default
        return $this->formatResponsDefault(
            $dataAbsensiHariIni,
            $jamKerjaKaryawan,
            $dataJam,
            $isPulangCepat,
            $jamKerjaToday,
            $lokasiPenempatan
        );
    }

    /**
     * Menampilkan riwayat absensi 30 hari terakhir.
     * @param int $id_karyawan ID karyawan.
     * @return array Riwayat absensi karyawan.
     */
    public function actionHistoryAbsensi($id_karyawan)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = $this->modelClass::find()
            ->where(['id_karyawan' => $id_karyawan])
            // ->andWhere(['between', 'tanggal', date('Y-m-d', strtotime('-30 days')), date('Y-m-d')])
            ->all();
        return $query;
    }

    /**
     * Membuat absensi masuk untuk karyawan yang diidentifikasi berdasarkan ID karyawan.
     * @param int $id_karyawan ID karyawan.
     * @return array Hasil operasi absensi masuk.
     */
    public function actionAbsenMasuk($id_karyawan)
    {
        // Set response format to JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Ambil input raw JSON
        $rawInput = Yii::$app->request->getRawBody();
        $data = Json::decode($rawInput);

        // Validasi input
        if (empty($data)) {
            return [
                'success' => false,
                'message' => 'Data tidak valid'
            ];
        }

        try {
            // Mulai transaksi database
            $transaction = Yii::$app->db->beginTransaction();

            // Buat model absensi baru
            $model = new Absensi();

            // Set data absensi
            $model->id_karyawan = $id_karyawan;
            $model->tanggal = date('Y-m-d');
            $model->kode_status_hadir = "H";
            $model->jam_masuk = date('H:i:s');

            // Ambil data dari input JSON dengan nilai default
            $model->is_lembur = $data['is_lembur'] ?? 0;
            $model->is_wfh = $data['is_wfh'] ?? 0;
            $model->is_24jam = $data['is_24jam'] ?? 0;
            $model->keterangan = $model->is_lembur ? 'Lembur' : '-';
            $model->latitude = $data['latitude'] ?? null;
            $model->longitude = $data['longitude'] ?? null;

            // Validasi model
            if (!$model->validate()) {
                return [
                    'success' => false,
                    'errors' => $model->errors
                ];
            }

            // Simpan model
            if ($model->save()) {
                // Commit transaksi
                $transaction->commit();

                return [
                    'success' => true,
                    'message' => 'Absen Masuk Berhasil',
                    'data' => $model
                ];
            } else {
                // Rollback transaksi jika gagal
                $transaction->rollBack();

                return [
                    'success' => false,
                    'message' => 'Gagal menyimpan absensi'
                ];
            }
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            if (isset($transaction)) {
                $transaction->rollBack();
            }

            // Log error
            Yii::error('Error dalam absen masuk: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    public function actionTidakMasuk($id_karyawan)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        // Nonaktifkan CSRF validation untuk API
        $this->enableCsrfValidation = false;

    
        // Ambil ID karyawan dari parameter URL
        if (!$id_karyawan) {
            return [
                'success' => false,
                'message' => 'ID Karyawan tidak ditemukan'
            ];
        }
    
        // Validasi karyawan
        $karyawan = Karyawan::findOne($id_karyawan);
        if (!$karyawan) {
            return [
                'success' => false,
                'message' => 'Karyawan tidak ditemukan'
            ];
        }
    
        $model = new Absensi();
    
        
        // Ambil data dari berbagai sumber
        $keterangan = Yii::$app->request->post('keterangan');
        $statusHadir = Yii::$app->request->post('kode_status_hadir');
    
        // Jika data kosong, coba ambil dari raw input
        if ($keterangan === null || $statusHadir === null) {
            $rawInput = Yii::$app->request->getRawBody();
            $inputData = json_decode($rawInput, true);
            
            $keterangan = $inputData['keterangan'] ?? $keterangan;
            $statusHadir = $inputData['kode_status_hadir'] ?? $statusHadir;
        }
    
        // Proses upload lampiran
        $uploadedFile = null;
        
        // Coba ambil file dari beberapa sumber
        if (isset($_FILES['lampiran'])) {
            // Dari $_FILES (multipart form-data)
            $uploadedFile = UploadedFile::getInstanceByName('lampiran');
        } elseif (Yii::$app->request->post('lampiran')) {
            // Dari POST data (base64)
            $base64File = Yii::$app->request->post('lampiran');
            $uploadedFile = $this->base64ToUploadedFile($base64File);
        }
    
        // Debug informasi file
        \Yii::info([
            'FILES' => $_FILES,
            'POST' => Yii::$app->request->post(),
            'uploadedFile' => $uploadedFile ? $uploadedFile->name : 'No file'
        ], 'file_upload_debug');
    
        // Proses upload file
        if ($uploadedFile) {

            
            // Tentukan direktori upload
            $uploadPath = \Yii::getAlias('@webroot/uploads/lampiran/');
            
            // Pastikan direktori ada
            FileHelper::createDirectory($uploadPath, 0775, true);
    
            // Generate nama file unik
            $fileName = $this->generateUniqueFileName($uploadedFile);
            $fullPath = $uploadPath . $fileName;
    
            // Simpan file
            if ($uploadedFile->saveAs($fullPath)) {
                // Set nama file ke model
                $model->lampiran = "uploads/lampiran/" . $fileName;
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal menyimpan lampiran'
                ];
            }
        }
    
        // Set atribut model
        $model->id_karyawan = $id_karyawan;
        $model->jam_masuk = "00:00:00";
        $model->jam_pulang = "00:00:00";
        $model->tanggal = date('Y-m-d');
        $model->keterangan = $keterangan;
        $model->kode_status_hadir = $statusHadir;
    
        // Cek jam kerja karyawan
        $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $id_karyawan])->one();
        if ($jamKerjaKaryawan && $jamKerjaKaryawan->jamKerja) {
            $hariIni = date('w') == '0' ? 0 : date('w');
            $jadwalKerja = JadwalKerja::find()->asArray()
                ->where(['id_jam_kerja' => $jamKerjaKaryawan->id_jam_kerja, 'nama_hari' => $hariIni])
                ->one();
    
            if ($jadwalKerja) {
                $model->jam_masuk = $jadwalKerja['jam_masuk'];
                $model->jam_pulang = $jadwalKerja['jam_keluar'];
            } else {
                $model->jam_masuk = "00:00:00";
                $model->jam_pulang = "00:00:00";
            }
        }
    
        // Simpan model
        if ($model->save()) {
            return [
                'success' => true,
                'message' => 'Absensi berhasil disimpan',
                'data' => $model
            ];
        } else {
            // Hapus file yang sudah diupload jika model gagal disimpan
            if (isset($fullPath) && file_exists($fullPath)) {
                unlink($fullPath);
            }
    
            return [
                'success' => false,
                'message' => 'Gagal menyimpan absensi',
                'errors' => $model->errors
            ];
        }
    }
    
    /**
     * Generate nama file unik
     * 
     * @param UploadedFile $uploadedFile
     * @return string
     */
    private function generateUniqueFileName($uploadedFile)
    {
        // Generate nama file unik dengan timestamp dan ekstensi asli
        return uniqid() . '_' . time() . '.' . $uploadedFile->extension;
    }
    
    /**
     * Konversi base64 ke UploadedFile
     * 
     * @param string $base64String
     * @return UploadedFile|null
     */
    private function base64ToUploadedFile($base64String)
    {
        if (!$base64String) return null;
    
        // Decode base64
        $fileData = base64_decode($base64String);
        
        // Simpan sementara
        $tempFile = tempnam(sys_get_temp_dir(), 'upload_');
        file_put_contents($tempFile, $fileData);
    
        // Buat UploadedFile
        return new UploadedFile([
            'name' => 'uploaded_file',
            'tempName' => $tempFile,
            'type' => mime_content_type($tempFile),
            'size' => filesize($tempFile),
            'error' => UPLOAD_ERR_OK
        ]);
    }
    /**
     * Action untuk menambahkan absen terlambat.
     * Action ini akan menambahkan absen terlambat berdasarkan input JSON yang diterima.
     * Input JSON harus berisi data berikut:
     * - latitude: latitude absensi
     * - longitude: longitude absensi
     * - alasan_terlambat: alasan terlambat
     *
     * Response dari action ini berupa JSON yang berisi data sebagai berikut:
     * - success: boolean yang menunjukkan apakah operasi berhasil atau tidak
     * - message: pesan yang menjelaskan hasil operasi
     * - data: data absensi yang berhasil disimpan
     */
    public function actionAbsenTerlambat($id_karyawan)
    {
        // Set response format to JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Ambil input raw JSON
        $rawInput = Yii::$app->request->getRawBody();
        $data = Json::decode($rawInput);

        // Validasi input
        if (empty($data)) {
            return [
                'success' => false,
                'message' => 'Data tidak valid'
            ];
        }

        try {
            // Mulai transaksi database
            $transaction = Yii::$app->db->beginTransaction();

            // Cek apakah sudah ada absen hari ini
            $isAda = Absensi::find()
                ->where([
                    'id_karyawan' => $id_karyawan,
                    'tanggal' => date('Y-m-d')
                ])
                ->exists();

            if ($isAda) {
                return [
                    'success' => false,
                    'message' => 'Absen Masuk Anda Sudah Ada'
                ];
            }


            // Buat model absensi baru
            $model = new Absensi();

            // Cari data karyawan
            $karyawan = Karyawan::findOne($id_karyawan);
            if (!$karyawan) {
                return [
                    'success' => false,
                    'message' => 'Karyawan tidak ditemukan'
                ];
            }

            // Set data absensi
            $model->id_karyawan = $id_karyawan;
            $model->tanggal = date('Y-m-d');
            $model->kode_status_hadir = "H";
            $model->jam_masuk = date('H:i:s');


            // Ambil data dari input JSON
            $model->latitude = $data['latitude'] ?? null;
            $model->longitude = $data['longitude'] ?? null;
            $model->alasan_terlambat = $data['alasan_terlambat'] ?? null;

            // Cari jam kerja karyawan
            $jamKerjaKaryawan = JamKerjaKaryawan::find()
                ->where(['id_karyawan' => $id_karyawan])
                ->one();

            // Cek keterlambatan
            if ($jamKerjaKaryawan && $jamKerjaKaryawan->id_shift_kerja !== null) {
                // Cek berdasarkan shift kerja
                $shift = ShiftKerja::findOne($jamKerjaKaryawan->id_shift_kerja);

                if ($shift && strtotime($model->jam_masuk) > strtotime($shift->jam_masuk)) {
                    $model->is_terlambat = 1;
                    $lamaTerlambat = gmdate('H:i:s', strtotime($model->jam_masuk) - strtotime($shift->jam_masuk));
                    $model->lama_terlambat = $lamaTerlambat;
                }
            } else {
                // Cek berdasarkan jadwal kerja
                $jadwalKerja = JadwalKerja::find()
                    ->where([
                        'nama_hari' => date('N'),
                        'id_jam_kerja' => $jamKerjaKaryawan->id_jam_kerja
                    ])
                    ->one();

                if ($jadwalKerja && strtotime($model->jam_masuk) > strtotime($jadwalKerja->jam_masuk)) {
                    $model->is_terlambat = 1;
                    $lamaTerlambat = gmdate('H:i:s', strtotime($model->jam_masuk) - strtotime($jadwalKerja->jam_masuk));
                    $model->lama_terlambat = $lamaTerlambat;
                }
            }

            // Validasi model
            if (!$model->validate()) {
                return [
                    'success' => false,
                    'errors' => $model->errors
                ];
            }

            // Simpan model
            if ($model->save()) {
                // Commit transaksi
                $transaction->commit();

                return [
                    'success' => true,
                    'message' => $model->is_terlambat ?
                        'Absen Masuk Berhasil, Anda Terlambat' :
                        'Absen Masuk Berhasil'
                ];
            } else {
                // Rollback transaksi jika gagal
                $transaction->rollBack();

                return [
                    'success' => false,
                    'message' => 'Gagal menyimpan absensi'
                ];
            }
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            if (isset($transaction)) {
                $transaction->rollBack();
            }

            // Log error
            Yii::error('Error dalam absen terlambat: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }


    /**
     * Membuat absensi masuk untuk karyawan yang diidentifikasi berdasarkan ID karyawan dan alasan terlalu jauh.
     * @param int $id_karyawan ID karyawan.
     * @return array Hasil operasi absensi masuk.
     */
    public function actionAbsenTerlaluJauh($id_karyawan)
    {
        // Set response format to JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Ambil input raw JSON
        $rawInput = Yii::$app->request->getRawBody();
        $data = Json::decode($rawInput);

        // Validasi input
        if (empty($data)) {
            return [
                'success' => false,
                'message' => 'Data tidak valid'
            ];
        }

        try {
            // Mulai transaksi database
            $transaction = Yii::$app->db->beginTransaction();

            // Cek apakah sudah ada absen hari ini
            $isAda = Absensi::find()
                ->where([
                    'id_karyawan' => $id_karyawan,
                    'tanggal' => date('Y-m-d')
                ])
                ->exists();

            if ($isAda) {
                return [
                    'success' => false,
                    'message' => 'Absen Masuk Anda Sudah Ada'
                ];
            }

            // Buat model absensi baru
            $model = new Absensi();

            // Cari data karyawan
            $karyawan = Karyawan::findOne($id_karyawan);
            if (!$karyawan) {
                return [
                    'success' => false,
                    'message' => 'Karyawan tidak ditemukan'
                ];
            }

            // Set data absensi
            $model->id_karyawan = $id_karyawan;
            $model->tanggal = date('Y-m-d');
            $model->kode_status_hadir = "H";
            $model->jam_masuk = date('H:i:s');


            // Ambil data dari input JSON
            $model->latitude = $data['latitude'] ?? null;
            $model->longitude = $data['longitude'] ?? null;
            $model->keterangan = $data['keterangan'] ?? 'null';

            // Cari jam kerja karyawan
            $jamKerjaKaryawan = JamKerjaKaryawan::find()
                ->where(['id_karyawan' => $id_karyawan])
                ->one();

            // Cek keterlambatan
            if ($jamKerjaKaryawan && $jamKerjaKaryawan->id_shift_kerja !== null) {
                // Cek berdasarkan shift kerja
                $shift = ShiftKerja::findOne($jamKerjaKaryawan->id_shift_kerja);

                if ($shift && strtotime($model->jam_masuk) > strtotime($shift->jam_masuk)) {
                    $model->is_terlambat = 1;
                    $lamaTerlambat = gmdate('H:i:s', strtotime($model->jam_masuk) - strtotime($shift->jam_masuk));
                    $model->lama_terlambat = $lamaTerlambat;
                }
            } else {
                // Cek berdasarkan jadwal kerja
                $jadwalKerja = JadwalKerja::findOne($jamKerjaKaryawan->id_jam_kerja);

                if ($jadwalKerja && strtotime($model->jam_masuk) > strtotime($jadwalKerja->jam_masuk)) {
                    $model->is_terlambat = 1;
                    $lamaTerlambat = gmdate('H:i:s', strtotime($model->jam_masuk) - strtotime($jadwalKerja->jam_masuk));
                    $model->lama_terlambat = $lamaTerlambat;
                }
            }

            // Validasi model
            if (!$model->validate()) {
                return [
                    'success' => false,
                    'errors' => $model->errors
                ];
            }

            // Simpan model

            if ($model->save()) {
                // Commit transaksi
                $transaction->commit();

                return [
                    'success' => true,
                    'message' => 'Absen Masuk Berhasil, Anda Terlalu Jauh'
                ];
            } else {
                // Rollback transaksi jika gagal
                $transaction->rollBack();

                return [
                    'success' => false,
                    'message' => 'Gagal menyimpan absensi'
                ];
            }
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            if (isset($transaction)) {
                $transaction->rollBack();
            }

            // Log error
            Yii::error('Error dalam absen terlalu jauh: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Membuat absensi pulang untuk karyawan yang diidentifikasi berdasarkan ID karyawan.
     * @param int $id_karyawan ID karyawan.
     * @return array Hasil operasi absensi pulang.
     */
    public function actionAbsenPulang($id_karyawan)
    {
        // Set response format to JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            // Mulai transaksi database
            $transaction = Yii::$app->db->beginTransaction();

            // Cari absensi hari ini untuk karyawan
            $model = $this->modelClass::find()
                ->where([
                    'id_karyawan' => $id_karyawan,
                    'tanggal' => date('Y-m-d')
                ])
                ->one();

            // Validasi apakah absensi hari ini ada
            if (!$model) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada absensi masuk hari ini'
                ];
            }

            // Cek apakah sudah pernah absen pulang
            if ($model->jam_pulang !== null) {
                return [
                    'success' => false,
                    'message' => 'Anda sudah melakukan absen pulang hari ini'
                ];
            }

            // Set jam pulang
            $model->jam_pulang = date('H:i:s');


            // Validasi model
            if (!$model->validate()) {
                return [
                    'success' => false,
                    'errors' => $model->errors
                ];
            }

            // Simpan model
            if ($model->save()) {
                // Commit transaksi
                $transaction->commit();

                return [
                    'success' => true,
                    'message' => 'Absen Pulang Berhasil',
                    'data' => [
                        'jam_pulang' => $model->jam_pulang
                    ]
                ];
            } else {
                // Rollback transaksi jika gagal
                $transaction->rollBack();

                return [
                    'success' => false,
                    'message' => 'Gagal menyimpan absen pulang'
                ];
            }
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            if (isset($transaction)) {
                $transaction->rollBack();
            }

            // Log error
            Yii::error('Error dalam absen pulang: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }






    // ===========================


    // Fungsi-fungsi pendukung
    private function cekWfhHariIni($id_karyawan)
    {
        // Ambil data WFH
        $wfhData = $this->wfhData($id_karyawan);

        // Ambil tanggal hari ini
        $today = date('Y-m-d');

        // Inisialisasi status WFH
        $is_wfh = false;

        // Loop through WFH data
        foreach ($wfhData as $data) {
            // Decode tanggal array
            $tanggalArray = json_decode($data['tanggal_array'], true);

            // Pastikan tanggal array tidak kosong
            if (!empty($tanggalArray)) {
                // Periksa apakah tanggal hari ini ada di dalam array
                if (in_array($today, $tanggalArray)) {
                    $is_wfh = true;
                    break;
                }
            }
        }

        return $is_wfh;
    }



    private function formatResponsLembur(
        $dataAbsensiHariIni,
        $jamKerjaKaryawan,
        $dataJam,
        $isPulangCepat
    ) {
        return [
            'is24jam' => false,
            'isWfh' => false,
            'isLembur' => true,
            'absensiToday' => $dataAbsensiHariIni,
            'jamKerjaKaryawan' => $jamKerjaKaryawan,
            'dataJam' => $dataJam,
            'isPulangCepat' => $isPulangCepat,
        ];
    }


    private function formatResponsWfh(
        $dataAbsensiHariIni,
        $jamKerjaKaryawan,
        $dataJam,
        $isPulangCepat,
        $jamKerjaToday,
        $lokasiPenempatan
    ) {
        return [
            'is24jam' => false,
            'isWfh' => true,
            'isLembur' => false,
            'absensiToday' => $dataAbsensiHariIni,
            'jamKerjaKaryawan' => $jamKerjaKaryawan,
            'dataJam' => $dataJam,
            'isPulangCepat' => $isPulangCepat,
            'jamKerjaToday' => $jamKerjaToday,
            'masterLokasi' => $lokasiPenempatan,
        ];
    }

    private function formatResponsDefault(
        $dataAbsensiHariIni,
        $jamKerjaKaryawan,
        $dataJam,
        $isPulangCepat,
        $jamKerjaToday,
        $lokasiPenempatan
    ) {
        return [
            'is24jam' => false,
            'isWfh' => false,
            'isLembur' => false,
            'absensiToday' => $dataAbsensiHariIni,
            'jamKerjaKaryawan' => $jamKerjaKaryawan,
            'dataJam' => $dataJam,
            'isTerlambatActive' => false, // Sesuaikan dengan kebutuhan
            'isPulangCepat' => $isPulangCepat,
            'jamKerjaToday' => $jamKerjaToday,
            'masterLokasi' => $lokasiPenempatan,
        ];
    }

    private function cekLemburHariIni($hasilLembur)
    {
        if (empty($hasilLembur)) {
            return false;
        }

        $tanggalHariIni = date('Y-m-d');
        $adaLemburHariIni = false;

        foreach ($hasilLembur as $lembur) {
            // Periksa apakah ada lembur di tanggal hari ini
            if (isset($lembur['tanggal']) && $lembur['tanggal'] == $tanggalHariIni) {
                $adaLemburHariIni = true;
                break;
            }
        }

        return $adaLemburHariIni;
    }

    private function getJamKerjaToday($jamKerjaKaryawan)
    {
        $hariIni = date('w') == '0' ? 0 : date('w');
        // Untuk shift kerja
        if ($jamKerjaKaryawan['id_shift_kerja'] != null) {
            $jadwalKerjaKaryawan = JadwalKerja::find()->asArray()
                ->where([
                    'id_jam_kerja' => $jamKerjaKaryawan['id_jam_kerja'],
                    'nama_hari' => date('N'),
                    'id_shift_kerja' => $jamKerjaKaryawan['id_shift_kerja']
                ])
                ->one();

            $shifKerja = ShiftKerja::find()->asArray()
                ->where(['id_shift_kerja' => $jadwalKerjaKaryawan['id_shift_kerja']])
                ->one();

            // Jika jam masuk lebih besar dari jam keluar (melewati tengah malam)
            if (strtotime($shifKerja['jam_masuk']) > strtotime($shifKerja['jam_keluar'])) {
                return $shifKerja;
            }

            return $shifKerja;
        }

        // Untuk non-shift
        if ($hariIni != 0) {
            // Cek hari besar
            $hariBesar = MasterHaribesar::find()->select('tanggal')->asArray()->all();
            $tanggalHariIni = date('Y-m-d');
            $adaHariBesar = false;

            foreach ($hariBesar as $hari) {
                if ($hari['tanggal'] === $tanggalHariIni) {
                    $adaHariBesar = true;
                    break;
                }
            }

            // Ambil jadwal kerja hari ini
            $jamKerjaHari = $jamKerjaKaryawan->jamKerja->jadwalKerjas;
            if ($adaHariBesar) {
                return null;
            } else {
               $filteredJamKerja = array_filter($jamKerjaHari, function ($item) use ($hariIni) {
                return $item['nama_hari'] == $hariIni;
            });
            return $filteredJamKerja;
            }
        }

        return null;
    }

    private function cekAbsensi24Jam($id_karyawan, $jamKerjaKaryawan)
    {
        // Ambil jadwal kerja hari ini
        $jamKerjaHari = $jamKerjaKaryawan->jamKerja->jadwalKerjas;

        // Cek apakah hari ini adalah shift 24 jam
        foreach ($jamKerjaHari as $key => $value) {
            // Periksa apakah hari ini adalah hari kerja 24 jam
            if ($value['nama_hari'] == date("N") && $value['is_24jam'] == 1) {
                return true;
            }
        }

        return false;
    }
    private function getAbsensiHariIni($id_karyawan)
    {
        return $this->modelClass::find()
            ->where(['id_karyawan' => $id_karyawan, 'tanggal' => date('Y-m-d')])
            ->one();
    }

    private function getPenempatanKaryawan($id_karyawan)
    {
        $atasanPenempatan = AtasanKaryawan::find()->where(['id_karyawan' => $id_karyawan])->one();
        return $atasanPenempatan->masterLokasi ?? null;
    }

    private function getJamKerjaKaryawan($id_karyawan)
    {
        return JamKerjaKaryawan::find()->where(['id_karyawan' => $id_karyawan])->one();
    }

    private function getIzinPulangCepat($id_karyawan)
    {
        return IzinPulangCepat::find()->where([
            'id_karyawan' => $id_karyawan,
            'tanggal' => date('Y-m-d')
        ])->one();
    }

    private function getLemburAktif($id_karyawan)
    {
        $_lembur = PengajuanLembur::find()->asArray()->where(['id_karyawan' => $id_karyawan])->all();

        $hasilLembur = [];
        foreach ($_lembur as $l) {
            if (isset($l['tanggal']) && $l['tanggal'] >= date('Y-m-d') && $l['status'] == '1') {
                $hasilLembur[] = $l;
            }
        }

        return $hasilLembur;
    }


    // Fungsi-fungsi format respons
    private function formatRespons24Jam($jamKerjaKaryawan, $lokasiPenempatan, $isPulangCepat)
    {
        $absensiTerakhir = Absensi::find()
            ->where(['id_karyawan' => $jamKerjaKaryawan->id_karyawan])
            ->orderBy(['tanggal' => SORT_DESC])
            ->one();

        return [
            'is24jam' => true,
            'isWfh' => false,
            'isLembur' => false,
            'jamKerjaKaryawan' => $jamKerjaKaryawan,
            'absensiTerakhir' => $absensiTerakhir,
            'isPulangCepat' => $isPulangCepat,
            'masterLokasi' => $lokasiPenempatan,
        ];
    }



    public function wfhData($id_karyawan)
    {
        $wfhData = PengajuanWfh::find()->asArray()->where(['id_karyawan' => $id_karyawan, 'status' => 1])->all();
        $today = date('Y-m-d'); // Ambil tanggal hari ini

        $result = [];

        foreach ($wfhData as $data) {
            // Decode tanggal_array
            $tanggalArray = json_decode($data['tanggal_array'], true);

            // Pastikan tanggal_array tidak kosong
            if (!empty($tanggalArray)) {
                // Cek apakah ada tanggal yang lebih besar atau sama dengan hari ini
                foreach ($tanggalArray as $tanggal) {
                    if ($tanggal >= $today) {
                        $result[] = $data; // Simpan data yang memenuhi syarat
                        break; // Keluar dari loop setelah menemukan tanggal yang sesuai
                    }
                }
            }
        }

        return $result;
    }
}
