<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use backend\models\PengajuanWfh;
use backend\models\AtasanKaryawan;
use backend\models\JamKerjaKaryawan;
use backend\models\Karyawan;
use backend\models\MasterKode;
use backend\models\PengajuanCuti;
use backend\models\PengajuanCutiSearch;
use backend\models\PengajuanDinas;
use backend\models\PengajuanDinasSearch;
use backend\models\PengajuanLembur;
use backend\models\PengajuanLemburSearch;
use backend\models\PengajuanWfhSearch;
use backend\models\RekapCuti;
use DateTime;
use yii\web\Response;

class TanggapanController extends Controller
{

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // Matikan CSRF untuk aksi tertentu
            if (in_array($action->id, ['wfh-index', 'cuti-index', 'lembur-index', 'dinas-index'])) {
                Yii::$app->request->enableCsrfValidation = false;
            }
            return true;
        }
        return false;
    }

    public function actionWfhIndex($id_admin)
    {
        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');

        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
        $tgl_mulai = $firstDayOfMonth;
        $tgl_selesai = $lastdate;

        $status = null;
        $idKaryawanFilter = null;

        if (Yii::$app->request->isPost) {
            // Mendapatkan raw body yang dikirim dalam format JSON
            $requestData = json_decode(Yii::$app->request->getRawBody(), true);

            // Memeriksa apakah data JSON ada dan memprosesnya
            if (isset($requestData['tanggal_mulai'])) {
                $tgl_mulai = $requestData['tanggal_mulai'];
            }
            if (isset($requestData['tanggal_selesai'])) {
                $tgl_selesai = $requestData['tanggal_selesai'];
            }
            if (isset($requestData['status'])) {
                $status = $requestData['status'];
            }
            if (isset($requestData['id_karyawan'])) {
                $idKaryawanFilter = $requestData['id_karyawan'];
            }
        }



        $searchModel = new PengajuanWfhSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $tgl_mulai, $tgl_selesai,);

        $dataProvider->query->andWhere(['pengajuan_wfh.id_karyawan' => $idKaryawanList]);


        if ($idKaryawanFilter) {
            $dataProvider->query->andWhere(['pengajuan_wfh.id_karyawan' => $idKaryawanFilter]);
        }

        if (isset($status)) {
            $dataProvider->query->andWhere(['status' => $status]);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $this->asJson($dataProvider->getModels());
    }


    public function actionWfhView($id_pengajuan_wfh)
    {

        $model = PengajuanWfh::find()
            ->select(['pengajuan_wfh.*', 'karyawan.nama'])
            ->leftJoin('karyawan', 'pengajuan_wfh.id_karyawan = karyawan.id_karyawan')
            ->where(['id_pengajuan_wfh' => $id_pengajuan_wfh])
            ->asArray()
            ->one();


        if ($model) {
            return $this->asJson($model);
        }
        return $this->asJson(['error' => 'Pengajuan WFH not found.']);
    }

    public function actionWfhCreate()
    {
        $model = new PengajuanWfh();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $mulai = strtotime($model->tanggal_mulai);
                $selesai = strtotime($model->tanggal_selesai);

                if ($mulai > $selesai) {
                    return $this->asJson(['error' => 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.']);
                }

                $tanggalArray = [];
                $startDate = new DateTime($model->tanggal_mulai);
                $endDate = new DateTime($model->tanggal_selesai);

                while ($startDate <= $endDate) {
                    $tanggalArray[] = $startDate->format('d-m-Y');
                    $startDate->modify('+1 day');
                }

                $model->tanggal_array = json_encode($tanggalArray);
                $model->tanggal_mulai = date('Y-m-d', $mulai);
                $model->tanggal_selesai = date('Y-m-d', $selesai);

                if ($model->save()) {
                    return $this->asJson(['success' => 'Berhasil menyimpan data pengajuan WFH.']);
                } else {
                    return $this->asJson(['error' => 'Gagal menyimpan data pengajuan WFH, pastikan data yang anda masukkan benar.']);
                }
            }
        }
        return $this->asJson(['error' => 'Invalid data.']);
    }

    public function actionWfhUpdate($id_pengajuan_wfh, $id_admin)
    {
        $model = PengajuanWfh::findOne($id_pengajuan_wfh);
        if ($model) {
            // Menggunakan Yii::$app->request->isPut atau Yii::$app->request->isPatch untuk memeriksa metode
            if (Yii::$app->request->isPut || Yii::$app->request->isPatch) {
                // Mengambil data mentah dari request
                $rawBody = Yii::$app->getRequest()->getRawBody();
                $decodedArray = json_decode($rawBody, true); // Mengonversi ke array asosiatif

                if (json_last_error() !== JSON_ERROR_NONE) {
                    return $this->asJson(['error' => 'Gagal mendecode JSON: ' . json_last_error_msg()]);
                }

                // Memeriksa apakah decodedArray tidak kosong dan memiliki field yang diperlukan
                if (empty($decodedArray) || !isset($decodedArray['catatan_admin']) || !isset($decodedArray['status'])) {
                    return $this->asJson(['error' => 'Field catatan_admin dan status harus ada.']);
                }

                // Menggunakan operator null coalescing untuk memberikan nilai default jika field tidak ada
                $model->catatan_admin = $decodedArray['catatan_admin'] ?? null; // Atau nilai default lain
                $model->status = $decodedArray['status'] ?? null; // Atau nilai default lain
                $model->disetujui_pada = date('Y-m-d H:i:s');
                $model->disetujui_oleh = $id_admin;


                if ($model->save()) {
                    return $this->asJson(['success' => 'Berhasil menyimpan data pengajuan WFH.']);
                } else {
                    return $this->asJson(['error' => 'Gagal menyimpan data pengajuan WFH, pastikan data yang anda masukkan benar.']);
                }
            }
            return $this->asJson(['error' => 'Invalid request method.']);
        }
        return $this->asJson(['error' => 'Pengajuan WFH not found.']);
    }

    public function actionWfhDelete($id_pengajuan_wfh)
    {
        $model = PengajuanWfh::findOne($id_pengajuan_wfh);
        if ($model) {
            $model->delete();
            return $this->asJson(['success' => 'Pengajuan WFH Berhasil Dihapus']);
        }
        return $this->asJson(['error' => 'Gagal Menghapus Pengajuan WFH']);
    }



    public function actionLemburIndex($id_admin)
    {
        // Mendapatkan daftar karyawan bawahan dari atasan
        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');

        // Mengambil tanggal cut-off untuk memfilter data
        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
        $tgl_mulai = $firstDayOfMonth;
        $tgl_selesai = $lastdate;

        $status = null;
        $idKaryawanFilter = null;

        // Memeriksa apakah request POST diterima dan memproses raw body JSON
        if (Yii::$app->request->isPost) {
            $requestData = json_decode(Yii::$app->request->getRawBody(), true);

            if (isset($requestData['tanggal_mulai'])) {
                $tgl_mulai = $requestData['tanggal_mulai'];
            }
            if (isset($requestData['tanggal_selesai'])) {
                $tgl_selesai = $requestData['tanggal_selesai'];
            }
            if (isset($requestData['status'])) {
                $status = $requestData['status'];
            }
            if (isset($requestData['id_karyawan'])) {
                $idKaryawanFilter = $requestData['id_karyawan'];
            }
        }

        // Mencari data berdasarkan filter yang diberikan
        $searchModel = new PengajuanLemburSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $tgl_mulai, $tgl_selesai);

        $dataProvider->query->andWhere(['pengajuan_lembur.id_karyawan' => $idKaryawanList]);

        if ($idKaryawanFilter) {
            $dataProvider->query->andWhere(['pengajuan_lembur.id_karyawan' => $idKaryawanFilter]);
        }

        if (isset($status)) {
            $dataProvider->query->andWhere(['status' => $status]);
        }

        // Mengatur format response menjadi JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $this->asJson($dataProvider->getModels());
    }

    public function actionLemburView($id_pengajuan_lembur)
    {
        $model = PengajuanLembur::find()->select(['pengajuan_lembur.*', 'karyawan.nama',])->where(['id_pengajuan_lembur' => $id_pengajuan_lembur])->leftJoin('karyawan', 'pengajuan_lembur.id_karyawan = karyawan.id_karyawan')->asArray()->one();

        if ($model) {
            return $this->asJson($model);
        }
        return $this->asJson(['error' => 'Pengajuan lembur not found.']);
    }

    public function actionLemburCreate()
    {
        $model = new PengajuanLembur();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $model->pekerjaan = json_encode(Yii::$app->request->post('pekerjaan'));
                if ($model->status == Yii::$app->params['disetujui']) {
                    $model->disetujui_oleh = null;
                    $model->disetujui_pada = null;
                }
                $jamMulai = strtotime($model->jam_mulai);
                $jamSelesai = strtotime($model->jam_selesai);
                $selisihDetik = $jamSelesai - $jamMulai;
                $durasi = gmdate('H:i', $selisihDetik);
                $model->durasi = $durasi;

                if ($model->save()) {
                    return $this->asJson(['success' => 'Pengajuan Lembur Berhasil Ditambahkan']);
                }
                return $this->asJson(['error' => 'Pengajuan Lembur Gagal Ditambahkan']);
            }
        }
        return $this->asJson(['error' => 'Invalid data.']);
    }

    public function actionLemburUpdate($id_pengajuan_lembur, $id_admin)
    {
        $model = PengajuanLembur::findOne($id_pengajuan_lembur);
        if ($model) {
            if (Yii::$app->request->isPut || Yii::$app->request->isPatch) {
                $rawBody = Yii::$app->getRequest()->getRawBody();
                $decodedArray = json_decode($rawBody, true); // Mengonversi ke array asosiatif

                if (json_last_error() !== JSON_ERROR_NONE) {
                    return $this->asJson(['error' => 'Gagal mendecode JSON: ' . json_last_error_msg()]);
                }
                $model->status = $decodedArray['status'] ?? null; // Atau nilai default lain
                $model->disetujui_oleh = $id_admin;
                $model->disetujui_pada = date('Y-m-d H:i:s');
                $model->catatan_admin = $decodedArray['catatan_admin'] ?? null; // Atau nilai default lains
                if ($model->save()) {
                    return $this->asJson(['success' => 'Pengajuan Lembur Berhasil Diubah']);
                } else {
                    return $this->asJson(['error' => 'Gagal Mengubah Pengajuan Lembur']);
                }
            }
            return $this->asJson(['error' => 'Invalid request method.']);
        }
        return $this->asJson(['error' => 'Pengajuan lembur not found.']);
    }


    public function actionLemburDelete($id_pengajuan_lembur)
    {
        $model = PengajuanLembur::findOne($id_pengajuan_lembur);
        if ($model && $model->delete()) {
            return $this->asJson(['success' => 'Pengajuan Lembur Berhasil Dihapus']);
        }
        return $this->asJson(['error' => 'Gagal Menghapus Pengajuan Lembur']);
    }



    public function actionCutiIndex($id_admin)
    {
        // Mendapatkan daftar karyawan bawahan dari atasan
        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        // Menyimpan ID karyawan bawahan
        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');

        // Mengambil tanggal cut-off untuk memfilter data
        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
        $tgl_mulai = $firstDayOfMonth;
        $tgl_selesai = $lastdate;

        $status = null;
        $idKaryawanFilter = null;

        // Memeriksa apakah request POST diterima dan memproses raw body JSON
        if (Yii::$app->request->isPost) {
            // Mendapatkan data raw body yang dikirim dalam format JSON
            $requestData = json_decode(Yii::$app->request->getRawBody(), true);

            // Memeriksa apakah data ada dan memprosesnya
            if (isset($requestData['tanggal_mulai'])) {
                $tgl_mulai = $requestData['tanggal_mulai'];
            }
            if (isset($requestData['tanggal_selesai'])) {
                $tgl_selesai = $requestData['tanggal_selesai'];
            }
            if (isset($requestData['status'])) {
                $status = $requestData['status'];
            }
            if (isset($requestData['id_karyawan'])) {
                $idKaryawanFilter = $requestData['id_karyawan'];
            }
        }

        // Mencari data berdasarkan filter yang diberikan
        $searchModel = new PengajuanCutiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $tgl_mulai, $tgl_selesai);

        // Menambahkan filter berdasarkan ID karyawan
        $dataProvider->query->andWhere(['pengajuan_cuti.id_karyawan' => $idKaryawanList]);

        if ($idKaryawanFilter) {
            $dataProvider->query->andWhere(['pengajuan_cuti.id_karyawan' => $idKaryawanFilter]);
        }

        if (isset($status)) {
            $dataProvider->query->andWhere(['pengajuan_cuti.status' => $status]);
        }

        // Mengatur format response menjadi JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Mengembalikan data dalam format JSON
        return $this->asJson($dataProvider->getModels());
    }



    public function actionCutiView($id_pengajuan_cuti)
    {
        $model = PengajuanCuti::findOne($id_pengajuan_cuti);
        if ($model) {
            return $this->asJson($model);
        }
        return $this->asJson(['error' => 'Pengajuan cuti not found.']);
    }

    public function actionCutiCreate()
    {
        $model = new PengajuanCuti();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $model->tanggal_pengajuan = date('Y-m-d');
                $model->sisa_hari = 0;
                $model->status = 0;

                if ($model->save()) {
                    return $this->asJson(['success' => 'Pengajuan Cuti Berhasil']);
                } else {
                    return $this->asJson(['error' => 'Gagal Membuat Pengajuan Cuti']);
                }
            }
        }
        return $this->asJson(['error' => 'Invalid data.']);
    }

    public function actionCutiUpdate($id_pengajuan_cuti, $id_admin)
    {
        $model = PengajuanCuti::findOne($id_pengajuan_cuti);

        if (!$model) {
            return $this->asJson(['error' => 'Pengajuan cuti not found.']);
        }

        // Memeriksa apakah request menggunakan PUT atau PATCH
        if (!Yii::$app->request->isPut && !Yii::$app->request->isPatch) {
            return $this->asJson(['error' => 'Invalid request method.']);
        }

        // Mengambil data mentah dari request
        $rawBody = Yii::$app->getRequest()->getRawBody();
        $decodedArray = json_decode($rawBody, true); // Mengonversi ke array asosiatif

        // Memeriksa apakah JSON dapat didecode dengan benar
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->asJson(['error' => 'Gagal mendecode JSON: ' . json_last_error_msg()]);
        }

        // Memeriksa apakah decodedArray tidak kosong dan memiliki field yang diperlukan
        if (empty($decodedArray) || !isset($decodedArray['status'])) {
            return $this->asJson(['error' => 'Field status harus ada.']);
        }

        // Update hanya field status, catatan_admin, dan disetujui_oleh
        $model->status = $decodedArray['status'] ?? null;
        $model->catatan_admin = $decodedArray['catatan_admin'] ?? null;
        $model->sisa_hari = 0;
        $model->ditanggapi_pada = date('Y-m-d');
        $model->ditanggapi_oleh = $id_admin;

        // Menghitung hari kerja berdasarkan tanggal mulai dan selesai
        $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $model->id_karyawan])->one();
        if ($jamKerjaKaryawan) {
            $containsNumber = preg_match('/\d+/', $jamKerjaKaryawan->jamKerja->nama_jam_kerja, $matches) !== false;
            $hari_kerja = $this->hitungHariKerja($model->tanggal_mulai, $model->tanggal_selesai, $containsNumber);
        } else {
            $hari_kerja = 0; // Default value if no jam kerja is found
        }

        // Jika statusnya disetujui, update RekapCuti
        if ($model->status == Yii::$app->params['disetujui']) {
            $rekapan = RekapCuti::find()->where([
                'id_karyawan' => $model->id_karyawan,
                'id_master_cuti' => $model->jenis_cuti,
                'tahun' => date('Y', strtotime($model->tanggal_mulai))
            ])->one();

            if ($rekapan) {
                // Jika rekap sudah ada, tambahkan total hari terpakai
                $rekapan->total_hari_terpakai += $hari_kerja;
                $rekapan->save();
            } else {
                // Jika rekap belum ada, buat rekap baru
                $timestamp_mulai = strtotime($model->tanggal_mulai);
                $timestamp_selesai = strtotime($model->tanggal_selesai);
                $selisih_detik = $timestamp_selesai - $timestamp_mulai;
                $selisih_hari = max($selisih_detik / (60 * 60 * 24), 1); // Default at least 1 day if same date

                $NewrekapAsensi = new RekapCuti();
                $NewrekapAsensi->id_karyawan = $model->id_karyawan;
                $NewrekapAsensi->id_master_cuti = $model->jenis_cuti;
                $NewrekapAsensi->total_hari_terpakai = $selisih_hari;
                $NewrekapAsensi->tahun = date('Y', strtotime($model->tanggal_mulai));
                $NewrekapAsensi->save();
            }
        }

        // Menyimpan perubahan data
        if ($model->save()) {
            return $this->asJson(['success' => 'Pengajuan Cuti Berhasil Diubah']);
        } else {
            return $this->asJson(['error' => 'Gagal Mengubah Pengajuan Cuti']);
        }
    }



    public function actionCutiDelete($id_pengajuan_cuti)
    {
        $model = PengajuanCuti::findOne($id_pengajuan_cuti);
        if ($model && $model->delete()) {
            return $this->asJson(['success' => 'Pengajuan Cuti Berhasil Dihapus']);
        }
        return $this->asJson(['error' => 'Gagal Menghapus Pengajuan Cuti']);
    }






    public function actionDinasIndex($id_admin)
    {
        // Mendapatkan daftar karyawan bawahan dari atasan
        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        // Menyimpan ID karyawan bawahan
        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');

        // Mengambil tanggal cut-off untuk memfilter data
        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
        $tgl_mulai = $firstDayOfMonth;
        $tgl_selesai = $lastdate;

        $status = null;
        $idKaryawanFilter = null;

        // Memeriksa apakah request POST diterima dan memproses raw body JSON
        if (Yii::$app->request->isPost) {
            // Mendapatkan data raw body yang dikirim dalam format JSON
            $requestData = json_decode(Yii::$app->request->getRawBody(), true);

            // Memeriksa apakah data ada dan memprosesnya
            if (isset($requestData['tanggal_mulai'])) {
                $tgl_mulai = $requestData['tanggal_mulai'];
            }
            if (isset($requestData['tanggal_selesai'])) {
                $tgl_selesai = $requestData['tanggal_selesai'];
            }
            if (isset($requestData['status'])) {
                $status = $requestData['status'];
            }
            if (isset($requestData['id_karyawan'])) {
                $idKaryawanFilter = $requestData['id_karyawan'];
            }
        }

        // Mencari data berdasarkan filter yang diberikan
        $searchModel = new PengajuanDinasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $tgl_mulai, $tgl_selesai);

        // Menambahkan filter berdasarkan ID karyawan
        $dataProvider->query->andWhere(['pengajuan_dinas.id_karyawan' => $idKaryawanList]);

        if ($idKaryawanFilter) {
            $dataProvider->query->andWhere(['pengajuan_dinas.id_karyawan' => $idKaryawanFilter]);
        }

        if (isset($status)) {
            $dataProvider->query->andWhere(['status' => $status]);
        }

        // Mengatur format response menjadi JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Mengembalikan data dalam format JSON
        return $this->asJson($dataProvider->getModels());
    }

    public function actionDinasView($id_pengajuan_dinas)
    {
        $model = PengajuanDinas::find()
            ->select(['pengajuan_dinas.*', 'karyawan.nama'])
            ->where(['id_pengajuan_dinas' => $id_pengajuan_dinas])
            ->leftJoin('karyawan', 'pengajuan_dinas.id_karyawan = karyawan.id_karyawan')
            ->asArray()->one();
        if ($model) {
            return $this->asJson($model);
        }
        return $this->asJson(['error' => 'Pengajuan dinas not found.']);
    }

    public function actionDinasCreate()
    {
        $model = new PengajuanDinas();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                if ($model['status'] == Yii::$app->params['disetujui'] || $model['status'] == Yii::$app->params['ditolak']) {
                    $model->disetujui_oleh = null;
                    $model->disetujui_pada = null;
                    $model->biaya_yang_disetujui = $model->estimasi_biaya;
                }
                if ($model->save()) {
                    return $this->asJson(['success' => 'Berhasil Menambahkan Data']);
                } else {
                    return $this->asJson(['error' => 'Gagal Menambahkan Data']);
                }
            }
        }
        return $this->asJson(['error' => 'Invalid data.']);
    }

    public function actionDinasUpdate($id_pengajuan_dinas, $id_admin)
    {
        $model = PengajuanDinas::findOne($id_pengajuan_dinas);
        if ($model) {
            // Memeriksa apakah request menggunakan PUT atau PATCH
            if (Yii::$app->request->isPut || Yii::$app->request->isPatch) {
                // Mengambil data mentah dari request
                $rawBody = Yii::$app->getRequest()->getRawBody();
                $decodedArray = json_decode($rawBody, true); // Mengonversi ke array asosiatif

                if (json_last_error() !== JSON_ERROR_NONE) {
                    return $this->asJson(['error' => 'Gagal mendecode JSON: ' . json_last_error_msg()]);
                }

                // Memeriksa apakah decodedArray tidak kosong dan memiliki field yang diperlukan
                if (empty($decodedArray) || !isset($decodedArray['status'])) {
                    return $this->asJson(['error' => 'Field status harus ada.']);
                }

                // Update hanya field status, catatan_admin, dan disetujui_oleh
                $model->status = $decodedArray['status'] ?? null;
                $model->catatan_admin = $decodedArray['catatan_admin'] ?? null;
                $model->disetujui_oleh = $id_admin;
                $model->disetujui_pada = date('Y-m-d H:i:s'); // Waktu persetujuan

                // Menyimpan perubahan data
                if ($model->save()) {
                    return $this->asJson(['success' => 'Pengajuan Dinas Berhasil Diubah']);
                } else {
                    return $this->asJson(['error' => 'Gagal Mengubah Pengajuan Dinas']);
                }
            }
            return $this->asJson(['error' => 'Invalid request method.']);
        }
        return $this->asJson(['error' => 'Pengajuan dinas not found.']);
    }


    public function actionDinasDelete($id_pengajuan_dinas)
    {
        $model = PengajuanDinas::findOne($id_pengajuan_dinas);
        if ($model) {
            if ($model->files != null) {
                $files = json_decode($model->files, true);
                if ($files) {
                    foreach ($files as $file) {
                        if (file_exists(Yii::getAlias('@webroot') . '/' . $file)) {
                            unlink(Yii::getAlias('@webroot') . '/' . $file);
                        }
                    }
                }
            }
            $model->delete();
            return $this->asJson(['success' => 'Pengajuan Dinas Berhasil Dihapus']);
        }
        return $this->asJson(['error' => 'Gagal Menghapus Pengajuan Dinas']);
    }


    public  function hitungHariKerja($tanggal_mulai, $tanggal_selesai, $containsNumber)
    {
        // Konversi tanggal menjadi timestamp
        $timestamp_mulai = strtotime($tanggal_mulai);
        $timestamp_selesai = strtotime($tanggal_selesai);

        // Inisialisasi variabel untuk menghitung hari kerja
        $hari_kerja = 0;

        // Loop melalui semua hari antara tanggal mulai dan tanggal selesai
        for ($timestamp = $timestamp_mulai; $timestamp <= $timestamp_selesai; $timestamp += 86400) { // 86400 detik = 1 hari
            // Ambil nama hari dalam seminggu (contoh: "Sunday", "Monday", dll.)
            $hari = date('l', $timestamp);

            if ($containsNumber) {
                if ($hari != 'Saturday' && $hari != 'Sunday') {
                    $hari_kerja++;
                }
            } else {
                if ($hari != 'Sunday') {
                    $hari_kerja++;
                }
            }
            // Periksa apakah hari tersebut bukan Sabtu atau Minggu
        }

        return $hari_kerja;
    }
}
