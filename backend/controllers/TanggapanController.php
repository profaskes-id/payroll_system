<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\models\AtasanKaryawan;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\NotificationHelper;
use backend\models\JamKerjaKaryawan;
use backend\models\MasterKode;
use backend\models\PengajuanCuti;
use backend\models\PengajuanDinas;
use backend\models\PengajuanLembur;
use backend\models\PengajuanShift;
use backend\models\PengajuanWfh;
use backend\models\PengajuanWfhSearch;
use backend\models\RekapCuti;
use DateTime;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

class TanggapanController extends Controller
{

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
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                $user = Yii::$app->user;

                                return !$user->can('admin') && !$user->can('super_admin');
                            },
                        ],
                    ],
                ],
            ]
        );
    }


    public function beforeAction($action)
    {
        if ($action->id == 'wfh-delete' || $action->id == 'view' || $action->id == 'expirience-pekerjaan-delete' || $action->id == 'expirience-pendidikan-delete' || $action->id == 'data-keluarga-delete' || $action->id == 'riwayat-pelatihan-delete' || $action->id == 'riwayat-kesehatan-delete') {

            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionWfh()
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;

        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');
        $pengajuanWfhList = PengajuanWfh::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->all();

        $model = new PengajuanWfh();
        $this->layout = 'mobile-main';



        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
        $tgl_mulai =  Yii::$app->request->get() == [] ? $firstDayOfMonth :  Yii::$app->request->get()['PengajuanWfhSearch']['tanggal_mulai'];
        $tgl_selesai =  Yii::$app->request->get() == [] ? $lastdate :  Yii::$app->request->get()['PengajuanWfhSearch']['tanggal_selesai'];

        // $searchModel = new PengajuanWfhSearch();
        // $dataProvider = $searchModel->search($this->request->queryParams, $tgl_mulai, $tgl_selesai);


        return $this->render('/home/tanggapan/wfh/index', compact('pengajuanWfhList', 'model', 'tgl_mulai', 'tgl_selesai'));
        // return $this->render('/home/tanggapan/wfh/index', compact('searchModel', 'dataProvider', 'tgl_mulai', 'tgl_selesai'));
    }

    public function actionWfhCreate()
    {
        $this->layout = 'mobile-main';
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $model = new PengajuanWfh();

        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {
                $tanggalArray = [];
                $startDate = new DateTime($model->tanggal_mulai);
                $endDate = new DateTime($model->tanggal_selesai);

                while ($startDate <= $endDate) {
                    $tanggalArray[] = $startDate->format('Y-m-d');
                    $startDate->modify('+1 day');
                }

                $model->tanggal_array = json_encode($tanggalArray);
                if ($model->save()) {

                    Yii::$app->session->setFlash('success', 'Berhasil Membuat Pengajuan');


                    return $this->redirect(['/tanggapan/wfh']);
                } else {

                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/tanggapan/wfh']);
                }
            }
        }


        return $this->render('/home/tanggapan/wfh/create', compact('model', 'karyawanBawahanAdmin'));
    }

    public function actionWfhView($id_pengajuan_wfh)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanWfh::find()->where(['id_pengajuan_wfh' => $id_pengajuan_wfh])->one();
        return $this->render('/home/tanggapan/wfh/view', compact('model'));
    }
    public function actionWfhUpdate($id)
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $pengajuanWfh = PengajuanWfh::find()->where(['id_pengajuan_wfh' => $id])->one();

        if ($pengajuanWfh) {
            $tanggal_array = json_decode($pengajuanWfh['tanggal_array']);
            $tanggal_awal = $tanggal_array[0] ?? null;
            $tanggal_akhir = end($tanggal_array) ?? null;
        }
        if ($this->request->isPost) {
            if ($pengajuanWfh->load($this->request->post())) {
                if ($pengajuanWfh->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Mengubah Pengajuan');


                    $adminUsers = User::find()->where(['id_karyawan' => $pengajuanWfh->id_karyawan])->all();
                    $sender = Yii::$app->user->identity->id;

                    $params = [
                        'judul' => 'Pengajuan wfh',
                        'deskripsi' => 'Pengajuan Lembur Anda telah ditanggapi oleh atasan.',
                        'nama_transaksi' => "wfh",
                        'id_transaksi' => $pengajuanWfh['id_pengajuan_wfh'],
                    ];

                    $this->sendNotif($params, $sender, $pengajuanWfh, $adminUsers, "Pengajuan wfh Baru Dari " . $pengajuanWfh->karyawan->nama);

                    // Redirect ke halaman view setelah berhasil diupdate
                    return $this->redirect(['/tanggapan/wfh-view', 'id_pengajuan_wfh' => $pengajuanWfh->id_pengajuan_wfh,]);

                    return $this->redirect(['/tanggapan/wfh']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal , Pastikan data yang anda masukkan benar');
                }
            }
        }

        $this->layout = 'mobile-main';
        return $this->render('/home/tanggapan/wfh/update', [
            'model' => $pengajuanWfh,
            'karyawanBawahanAdmin' => $karyawanBawahanAdmin,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ]);
    }


    public function actionWfhDelete()
    {
        $id = Yii::$app->request->get('id_pengajuan_wfh');
        $model = PengajuanWfh::find()->where(['id_pengajuan_wfh' => $id])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Pengajuan');
            return $this->redirect(['/tanggapan/wfh']);
        }

        Yii::$app->session->setFlash('error', 'Gagal Menghapus Pengajuan');
        return $this->redirect(['/tanggapan/wfh']);
    }


    // Lembur
    public function actionLembur()
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;

        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();
        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');
        $pengajuanLemburList = PengajuanLembur::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->all();

        $this->layout = 'mobile-main';
        $model = new PengajuanLembur();
        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
        $tgl_mulai =  Yii::$app->request->get() == [] ? $firstDayOfMonth :  Yii::$app->request->get()['PengajuanWfhSearch']['tanggal_mulai'];
        $tgl_selesai =  Yii::$app->request->get() == [] ? $lastdate :  Yii::$app->request->get()['PengajuanWfhSearch']['tanggal_selesai'];

        return $this->render('/home/tanggapan/lembur/index', compact('pengajuanLemburList',  'tgl_mulai', 'tgl_selesai', 'model'));
    }

    public function actionLemburCreate()
    {
        $this->layout = 'mobile-main';
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $model = new PengajuanLembur();

        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {
                $tanggalArray = [];
                $startDate = new DateTime($model->tanggal_mulai);
                $endDate = new DateTime($model->tanggal_selesai);

                while ($startDate <= $endDate) {
                    $tanggalArray[] = $startDate->format('Y-m-d');
                    $startDate->modify('+1 day');
                }

                $model->tanggal_array = json_encode($tanggalArray);
                if ($model->save()) {

                    Yii::$app->session->setFlash('success', 'Berhasil Membuat Pengajuan');


                    return $this->redirect(['/tanggapan/lembur']);
                } else {

                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/tanggapan/lembur']);
                }
            }
        }


        return $this->render('/home/tanggapan/lembur/create', compact('model', 'karyawanBawahanAdmin'));
    }

    public function actionLemburView($id_pengajuan_lembur)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanLembur::findOne($id_pengajuan_lembur);
        return $this->render('/home/tanggapan/lembur/view', [
            'model' => $model,
        ]);
    }


    public function actionLemburUpdate($id_pengajuan_lembur)
    {
        $this->layout = 'mobile-main';
        $id_admin = Yii::$app->user->identity->id_karyawan;
        $model =  PengajuanLembur::findOne($id_pengajuan_lembur);

        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();


        $poinArray = json_decode($model->pekerjaan);


        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->pekerjaan = json_encode(Yii::$app->request->post('pekerjaan'));
            $model->disetujui_oleh = Yii::$app->user->identity->id;
            $model->disetujui_pada = date('Y-m-d H:i:s');

            // Menghitung durasi lembur
            $jamMulai = strtotime($model->jam_mulai);
            $jamSelesai = strtotime($model->jam_selesai);
            $selisihDetik = $jamSelesai - $jamMulai;
            $model->durasi = gmdate('H:i', $selisihDetik);

            if ($model->save()) {

                $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                $sender = Yii::$app->user->identity->id;

                $params = [
                    'judul' => 'Pengajuan lembur',
                    'deskripsi' => 'Pengajuan Lembur Anda telah ditanggapi oleh atasan.',
                    'nama_transaksi' => "lembur",
                    'id_transaksi' => $model['id_pengajuan_lembur'],
                ];

                $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan lembur Baru Dari " . $model->karyawan->nama);
                return $this->redirect(['/tanggapan/lembur-view', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur,]);
            }
        }



        return $this->render('/home/tanggapan/lembur/update', [
            'model' => $model,
            'poinArray' => $poinArray,
            'karyawanBawahanAdmin' => $karyawanBawahanAdmin,

        ]);
    }

    public function actionLemburDelete($id_pengajuan_lembur)
    {
        $model = PengajuanLembur::find()->where(['id_pengajuan_lembur' => $id_pengajuan_lembur])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Pengajuan');
            return $this->redirect(['/tanggapan/lembur']);
        }

        Yii::$app->session->setFlash('error', 'Gagal Menghapus Pengajuan');
        return $this->redirect(['/tanggapan/lembur']);
    }




    // Cuti

    public function actionCuti()
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;

        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');
        $pengajuanCutiList = PengajuanCuti::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->all();

        $model = new PengajuanCuti();
        $this->layout = 'mobile-main';



        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
        $tgl_mulai =  Yii::$app->request->get() == [] ? $firstDayOfMonth :  Yii::$app->request->get()['PengajuanCutiSearch']['tanggal_mulai'];
        $tgl_selesai =  Yii::$app->request->get() == [] ? $lastdate :  Yii::$app->request->get()['PengajuanCutiSearch']['tanggal_selesai'];

        return $this->render('/home/tanggapan/cuti/index', compact('pengajuanCutiList', 'model', 'karyawanBawahanAdmin', 'tgl_mulai', 'tgl_selesai'));
    }

    public function actionCutiCreate()
    {
        $this->layout = 'mobile-main';
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $model = new PengajuanCuti();

        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {
                $tanggalArray = [];
                $startDate = new DateTime($model->tanggal_mulai);
                $endDate = new DateTime($model->tanggal_selesai);

                while ($startDate <= $endDate) {
                    $tanggalArray[] = $startDate->format('Y-m-d');
                    $startDate->modify('+1 day');
                }

                $model->tanggal_array = json_encode($tanggalArray);
                if ($model->save()) {



                    Yii::$app->session->setFlash('success', 'Berhasil Membuat Pengajuan');


                    return $this->redirect(['/tanggapan/cuti']);
                } else {

                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/tanggapan/cuti']);
                }
            }
        }


        return $this->render('/home/tanggapan/cuti/create', compact('model', 'karyawanBawahanAdmin'));
    }

    public function actionCutiView($id_pengajuan_cuti)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanCuti::find()->where(['id_pengajuan_cuti' => $id_pengajuan_cuti])->one();
        return $this->render('/home/tanggapan/cuti/view', compact('model'));
    }
    public function actionCutiUpdate($id_pengajuan_cuti)
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $pengajuanCuti = PengajuanCuti::find()->where(['id_pengajuan_cuti' => $id_pengajuan_cuti])->one();

        $model = PengajuanCuti::find()->where(['id_pengajuan_cuti' => $id_pengajuan_cuti])->one();
        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {


                $model->sisa_hari = 0;


                if ($model->save()) {

                    $rekapAsensi = new RekapCuti();
                    $rekapAsensi->id_karyawan = $model->id_karyawan;
                    $rekapAsensi->id_master_cuti = $model->jenis_cuti;



                    // $timestamp_mulai = strtotime($model->tanggal_mulai);
                    // $timestamp_selesai = strtotime($model->tanggal_selesai);
                    $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $model->id_karyawan])->one();
                    $containsNumber = strpos($jamKerjaKaryawan->jamKerja->nama_jam_kerja, preg_match('/\d+/', "5", $matches) ? $matches[0] : '') !== false;

                    $hari_kerja = $this->hitungHariKerja($model->tanggal_mulai, $model->tanggal_selesai, $containsNumber);
                    // Menghitung selisih hari
                    // $selisih_detik = $timestamp_selesai - $timestamp_mulai;
                    // $selisih_hari = $selisih_detik / (60 * 60 * 24);



                    if ($model->status == Yii::$app->params['disetujui']) {
                        $rekapan = RekapCuti::find()->where(['id_karyawan' => $model->id_karyawan, 'id_master_cuti' => $model->jenis_cuti, 'tahun' => date('Y', strtotime($model->tanggal_mulai))])->one();
                        if ($rekapan) {
                            $rekapan->total_hari_terpakai += $hari_kerja;
                            $rekapan->save();
                        } else {
                            $timestamp_mulai = strtotime($model->tanggal_mulai);
                            $timestamp_selesai = strtotime($model->tanggal_selesai);
                            $selisih_detik = $timestamp_selesai - $timestamp_mulai == 0 ? (1 * 60 * 60 * 24) : $timestamp_selesai - $timestamp_mulai;
                            $selisih_hari = $selisih_detik / (60 * 60 * 24);
                            $NewrekapAsensi = new RekapCuti();
                            $NewrekapAsensi->id_karyawan = $model->id_karyawan;
                            $NewrekapAsensi->id_master_cuti = $model->jenis_cuti;
                            $NewrekapAsensi->total_hari_terpakai = $selisih_hari;
                            $NewrekapAsensi->tahun = date('Y', strtotime($model->tanggal_mulai));
                            $NewrekapAsensi->save();
                        }
                    }


                    $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                    $sender = Yii::$app->user->identity->id;

                    $params = [
                        'judul' => 'Pengajuan cuti',
                        'deskripsi' => 'Pengajuan Lembur Anda telah ditanggapi oleh atasan.',
                        'nama_transaksi' => "cuti",
                        'id_transaksi' => $model['id_pengajuan_cuti'],
                    ];

                    $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan cuti Baru Dari " . $model->karyawan->nama);
                    return $this->redirect(['/tanggapan/cuti-view', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti,]);
                } else {

                    Yii::$app->session->setFlash('error', 'Gagal Mengupdate Pengajuan');
                    return $this->redirect(['/tanggapan/cuti']);
                }
            }
        }



        $this->layout = 'mobile-main';
        return $this->render('/home/tanggapan/cuti/update', [
            'model' => $pengajuanCuti,
            'karyawanBawahanAdmin' => $karyawanBawahanAdmin,

        ]);
    }


    public function actionCutiDelete()
    {
        $id = Yii::$app->request->get('id_pengajuan_cuti');
        $model = PengajuanCuti::find()->where(['id_pengajuan_cuti' => $id])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Pengajuan');
            return $this->redirect(['/tanggapan/cuti']);
        }

        Yii::$app->session->setFlash('error', 'Gagal Menghapus Pengajuan');
        return $this->redirect(['/tanggapan/cuti']);
    }








    // Dinas
    public function actionDinas()
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;

        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();
        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');
        $pengajuanDinasList = PengajuanDinas::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->all();

        $this->layout = 'mobile-main';

        $model = new PengajuanDinas();
        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
        $tgl_mulai =  Yii::$app->request->get() == [] ? $firstDayOfMonth :  Yii::$app->request->get()['PengajuanWfhSearch']['tanggal_mulai'];
        $tgl_selesai =  Yii::$app->request->get() == [] ? $lastdate :  Yii::$app->request->get()['PengajuanWfhSearch']['tanggal_selesai'];

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->disetujui_oleh = Yii::$app->user->identity->id;
            $model->disetujui_pada = date('Y-m-d H:i:s');
            $model->save();
            $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
            $sender = Yii::$app->user->identity->id;

            $params = [
                'judul' => 'Pengajuan dinas',
                'deskripsi' => 'Pengajuan Dinas luar Anda Telah Ditanggapi Oleh Atasan.',
                'nama_transaksi' => "dinas",
                'id_transaksi' => $model['id_pengajuan_dinas'],
            ];
            $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan dinas Baru Dari " . $model->karyawan->nama);


            return $this->redirect(['view', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]);
        }


        return $this->render('/home/tanggapan/dinas/index', compact('pengajuanDinasList', 'model', 'tgl_mulai', 'tgl_selesai'));
    }

    public function actionDinasCreate()
    {
        $this->layout = 'mobile-main';
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $model = new PengajuanDinas();

        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {
                $tanggalArray = [];
                $startDate = new DateTime($model->tanggal_mulai);
                $endDate = new DateTime($model->tanggal_selesai);

                while ($startDate <= $endDate) {
                    $tanggalArray[] = $startDate->format('Y-m-d');
                    $startDate->modify('+1 day');
                }

                $model->tanggal_array = json_encode($tanggalArray);
                if ($model->save()) {



                    Yii::$app->session->setFlash('success', 'Berhasil Membuat Pengajuan');


                    return $this->redirect(['/tanggapan/dinas']);
                } else {

                    Yii::$app->session->setFlash('error', 'Gagal Membuat Pengajuan');
                    return $this->redirect(['/tanggapan/dinas']);
                }
            }
        }


        return $this->render('/home/tanggapan/dinas/create', compact('model', 'karyawanBawahanAdmin'));
    }


    public function actionDinasView($id_pengajuan_dinas)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanDinas::find()->where(['id_pengajuan_dinas' => $id_pengajuan_dinas])->one();
        return $this->render('/home/tanggapan/dinas/view', compact('model'));
    }
    public function actionDinasUpdate($id)
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $pengajuanDinas = PengajuanDinas::find()->where(['id_pengajuan_dinas' => $id])->one();


        $model = PengajuanDinas::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->disetujui_oleh = Yii::$app->user->identity->id;
            $model->disetujui_pada = date('Y-m-d H:i:s');

            if ($model->save()) {
                # code...

                $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                $sender = Yii::$app->user->identity->id;

                $params = [
                    'judul' => 'Pengajuan dinas',
                    'deskripsi' => 'Pengajuan Dinas luar Anda Telah Ditanggapi Oleh Atasan.',
                    'nama_transaksi' => "dinas",
                    'id_transaksi' => $model['id_pengajuan_dinas'],
                ];
                $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan dinas Baru Dari " . $model->karyawan->nama);


                return $this->redirect(['/tanggapan/dinas-view', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]);
            }
            Yii::$app->session->setFlash('error', 'gagal mengupdate pengajuan');
        }


        $this->layout = 'mobile-main';
        return $this->render('/home/tanggapan/dinas/update', [
            'model' => $pengajuanDinas,
            'karyawanBawahanAdmin' => $karyawanBawahanAdmin,
        ]);
    }


    public function actionDinasDelete($id_pengajuan_dinas)
    {
        $model = PengajuanDinas::find()->where(['id_pengajuan_dinas' => $id_pengajuan_dinas])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Pengajuan');
            return $this->redirect(['/tanggapan/dinas']);
        }

        Yii::$app->session->setFlash('error', 'Gagal Menghapus Pengajuan');
        return $this->redirect(['/tanggapan/dinas']);
    }





    public function actionShift()
    {
        $this->layout = 'mobile-main';
        $id_admin = Yii::$app->user->identity->id_karyawan;
        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');
        $pengajuanShiftList = PengajuanShift::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->all();
        $model = new PengajuanShift();
        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
        $tgl_mulai =  Yii::$app->request->get() == [] ? $firstDayOfMonth :  Yii::$app->request->get()['PengajuanCutiSearch']['tanggal_mulai'];
        $tgl_selesai =  Yii::$app->request->get() == [] ? $lastdate :  Yii::$app->request->get()['PengajuanCutiSearch']['tanggal_selesai'];

        return $this->render('/home/tanggapan/shift/index', compact('pengajuanShiftList', 'model', 'karyawanBawahanAdmin', 'tgl_mulai', 'tgl_selesai'));
    }


    public function actionShiftView($id_pengajuan_shift)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanShift::find()->where(['id_pengajuan_shift' => $id_pengajuan_shift])->one();
        return $this->render('/home/tanggapan/shift/view', compact('model'));
    }


    public function actionShiftUpdate($id)
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $pengajuanDinas = PengajuanShift::find()->where(['id_pengajuan_shift' => $id])->one();


        $model = PengajuanShift::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->ditanggapi_oleh = Yii::$app->user->identity->id;
            $model->ditanggapi_pada = date('Y-m-d H:i:s');

            if ($model->save()) {
                # code...

                $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                $sender = Yii::$app->user->identity->id;

                $params = [
                    'judul' => 'Pengajuan Perubahan shift',
                    'deskripsi' => 'Pengajuan Perubahan Shift luar Anda Telah Ditanggapi Oleh Atasan.',
                    'nama_transaksi' => "shift",
                    'id_transaksi' => $model['id_pengajuan_shift'],
                ];
                $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan perubahan shift Baru Dari " . $model->karyawan->nama);


                return $this->redirect(['/tanggapan/shift-view', 'id_pengajuan_shift' => $model->id_pengajuan_shift]);
            }
            Yii::$app->session->setFlash('error', 'gagal mengupdate pengajuan');
        }


        $this->layout = 'mobile-main';
        return $this->render('/home/tanggapan/shift/update', [
            'model' => $pengajuanDinas,
            'karyawanBawahanAdmin' => $karyawanBawahanAdmin,
        ]);
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

    public function sendNotif($params, $sender,  $model, $adminUsers, $subject = "Pengajuan Di Tanggapi")
    {
        try {
            NotificationHelper::sendNotification($params, $adminUsers, $sender);
        } catch (\InvalidArgumentException $e) {
            // Handle invalid argument exception
            Yii::error("Invalid argument: " . $e->getMessage());
        } catch (\RuntimeException $e) {
            // Handle runtime exception
            Yii::error("Runtime error: " . $e->getMessage());
        }

        // return $this->renderPartial('@backend/views/home/pengajuan/email', compact('model', 'adminUsers', 'subject'));
        $msgToCheck = $this->renderPartial('@backend/views/home/pengajuan/email_user', compact('model', 'params'));

        // $msgToCheck = $this->renderPartial('@backend/views/home/pengajuan/email');
        // Mengirim email ke setiap pengguna
        foreach ($adminUsers as $user) {
            $to = $user->email;
            if (EmailHelper::sendEmail($to, $subject, $msgToCheck)) {
                Yii::$app->session->setFlash('success', 'Email berhasil dikirim ke ' . $to);
            } else {
                Yii::$app->session->setFlash('error', 'Email gagal dikirim ke ' . $to);
            }
        }
    }
}
