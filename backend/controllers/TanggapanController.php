<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\models\Absensi;
use backend\models\AtasanKaryawan;
use backend\models\DetailCuti;
use backend\models\DetailTugasLuar;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\NotificationHelper;
use backend\models\helpers\UseMessageHelper;
use backend\models\IzinPulangCepat;
use backend\models\JadwalShift;
use backend\models\JamKerjaKaryawan;
use backend\models\MasterKode;
use backend\models\PengajuanAbsensi;
use backend\models\PengajuanCuti;
use backend\models\PengajuanDinas;
use backend\models\PengajuanKasbon;
use backend\models\PengajuanLembur;
use backend\models\PengajuanShift;
use backend\models\PengajuanTugasLuar;
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
                $pengajuanWfh->disetujui_pada = date('Y-m-d H:i:s');
                $pengajuanWfh->disetujui_oleh = Yii::$app->user->identity->id;
                if ($pengajuanWfh->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Mengubah Pengajuan');


                    $adminUsers = User::find()->where(['id_karyawan' => $pengajuanWfh->id_karyawan])->all();
                    $sender = Yii::$app->user->identity->id;

                    $params = [
                        'judul' => 'Pengajuan wfh',
                        'deskripsi' => 'Pengajuan Wfh Anda telah ditanggapi oleh atasan.',
                        'nama_transaksi' => "/panel/pengajuan/wfh-detail?id",
                        'id_transaksi' => $pengajuanWfh['id_pengajuan_wfh'],
                    ];

                    $this->sendNotif($params, $sender, $pengajuanWfh, $adminUsers, "Pengajuan wfh  ");

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



    // tugas luar




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
                    'nama_transaksi' => "/panel/pengajuan/lembur-detail?id",
                    'id_transaksi' => $model['id_pengajuan_lembur'],
                ];

                $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan lembur  ");
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
                $model->ditanggapi_pada = date('Y-m-d H:i:s');
                $model->ditanggapi_oleh = Yii::$app->user->identity->id;

                if ($model->save()) {

                    $existingDetails = DetailCuti::find()
                        ->where(['id_pengajuan_cuti' => $model->id_pengajuan_cuti])
                        ->indexBy('id_detail_cuti')
                        ->all();

                    $postDetails = Yii::$app->request->post('DetailCuti', []);
                    $usedIds = [];
                    $approvedCount = 0;

                    foreach ($postDetails as $key => $data) {
                        if (!empty($data['id_detail_cuti']) && isset($existingDetails[$data['id_detail_cuti']])) {
                            // Update
                            $detail = $existingDetails[$data['id_detail_cuti']];
                            $detail->load($data, '');
                            $detail->save();
                            $usedIds[] = $data['id_detail_cuti'];
                        } else {
                            // New
                            $detail = new DetailCuti();
                            $detail->id_pengajuan_cuti = $model->id_pengajuan_cuti;
                            $detail->load($data, '');
                            $detail->save();
                        }

                        if (isset($data['status']) && $data['status'] == 1) {
                            $approvedCount++;
                        }
                    }

                    // Delete yang dihapus di form
                    foreach ($existingDetails as $id => $detail) {
                        if (!in_array($id, $usedIds)) {
                            $detail->delete();
                        }
                    }




                    if ($model->status == Yii::$app->params['disetujui']) {
                        $rekapan = RekapCuti::find()->where(['id_karyawan' => $model->id_karyawan, 'id_master_cuti' => $model->jenis_cuti, 'tahun' => date('Y')])->one();
                        if ($rekapan) {
                            $rekapan->total_hari_terpakai += $approvedCount;
                            $rekapan->save();
                        } else {

                            $NewrekapAsensi = new RekapCuti();
                            $NewrekapAsensi->id_karyawan = $model->id_karyawan;
                            $NewrekapAsensi->id_master_cuti = $model->jenis_cuti;
                            $NewrekapAsensi->total_hari_terpakai = $approvedCount;
                            $NewrekapAsensi->tahun = date('Y');
                            $NewrekapAsensi->save();
                        }
                    }


                    $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                    $sender = Yii::$app->user->identity->id;

                    $params = [
                        'judul' => 'Pengajuan cuti',
                        'deskripsi' => 'Pengajuan cuti Anda telah ditanggapi oleh atasan.',
                        'nama_transaksi' => "/panel/pengajuan/cuti-detail?id",
                        'id_transaksi' => $model['id_pengajuan_cuti'],
                    ];

                    // $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan cuti Baru Dari " . $model->karyawan->nama);
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


    public function actionDeleteCutiDetail($id, $id_pengajuan_cuti)
    {
        $model = DetailCuti::find()->where(['id_detail_cuti' => $id])->one();
        $model->delete();
        Yii::$app->session->setFlash('success', 'Berhasil Menghapus Pengajuan');
        return $this->redirect('/panel/tanggapan/cuti-view?id_pengajuan_cuti=' . $id_pengajuan_cuti);
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
        $tgl_mulai =  Yii::$app->request->get() == [] ? $firstDayOfMonth :  Yii::$app->request->get()['PengajuanDinasSearch']['tanggal_mulai'];
        $tgl_selesai =  Yii::$app->request->get() == [] ? $lastdate :  Yii::$app->request->get()['PengajuanDinasSearch']['tanggal_selesai'];

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->disetujui_oleh = Yii::$app->user->identity->id;
            $model->disetujui_pada = date('Y-m-d H:i:s');
            $model->save();
            $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
            $sender = Yii::$app->user->identity->id;

            $params = [
                'judul' => 'Pengajuan dinas',
                'deskripsi' => 'Pengajuan Dinas luar Anda Telah Ditanggapi Oleh Atasan.',
                'nama_transaksi' => "/panel/pengajuan/dinas-detail?id",
                'id_transaksi' => $model['id_pengajuan_dinas'],
            ];
            $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan dinas  ");


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

                $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                $sender = Yii::$app->user->identity->id;

                $params = [
                    'judul' => 'Pengajuan dinas',
                    'deskripsi' => 'Pengajuan Dinas luar Anda Telah Ditanggapi Oleh Atasan.',
                    'nama_transaksi' => "/panel/pengajuan/dinas-detail?id",
                    'id_transaksi' => $model['id_pengajuan_dinas'],
                ];
                $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan dinas ");


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









    // kasbon   
    public function actionKasbon()
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;

        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');
        $pengajuanKasbonList = PengajuanKasbon::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->orderBy(['status' => SORT_DESC])
            ->all();

        $this->layout = 'mobile-main';

        $model = new PengajuanKasbon();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->disetujui_oleh = Yii::$app->user->identity->id;
            $model->tanggal_disetujui = date('Y-m-d');
            $model->status = 1; // contoh status disetujui
            $model->save();

            $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
            $sender = Yii::$app->user->identity->id;

            $params = [
                'judul' => 'Pengajuan Kasbon',
                'deskripsi' => 'Pengajuan kasbon Anda telah ditanggapi oleh atasan.',
                'nama_transaksi' => "/panel/pengajuan/kasbon-detail?id",
                'id_transaksi' => $model['id_pengajuan_kasbon'],
            ];
            $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan kasbon");

            return $this->redirect(['kasbon-view', 'id_pengajuan_kasbon' => $model->id_pengajuan_kasbon]);
        }

        return $this->render('/home/tanggapan/kasbon/index', compact('pengajuanKasbonList', 'model'));
    }

    // ========================================
    // CREATE KASBON
    // ========================================
    public function actionKasbonCreate()
    {
        $this->layout = 'mobile-main';
        $id_admin = Yii::$app->user->identity->id_karyawan;

        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $model = new PengajuanKasbon();

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Set default values
            $model->tanggal_pengajuan = date('Y-m-d');
            $model->status = 0; // pending
            $model->created_at = time();
            $model->created_by = Yii::$app->user->identity->id;
            $model->updated_at = time();
            $model->updated_by = Yii::$app->user->identity->id;

            // 🔹 Ambil gaji pokok dari master_gaji
            $gaji = \backend\models\MasterGaji::find()
                ->where(['id_karyawan' => $model->id_karyawan])
                ->orderBy(['id_gaji' => SORT_DESC])
                ->one();
            $model->gaji_pokok = $gaji ? $gaji->nominal_gaji : 0;

            if ($model->save()) {

                // 🔹 Setelah Save → Buat atau update data PembayaranKasbon
                // if ($model->status == 1) {
                //     $idKasbonBaru = $model->id_pengajuan_kasbon;
                //     $dataOld = \backend\models\PembayaranKasbon::find()
                //         ->where([
                //             'id_karyawan' => $model->id_karyawan,
                //             'status_potongan' => 0,
                //             'autodebt' => $model->tipe_potongan
                //         ])
                //         ->orderBy(['created_at' => SORT_DESC])
                //         ->one();

                //     if ($dataOld) {
                //         // Jika ada data lama
                //         $dataOld->id_kasbon = $idKasbonBaru;
                //         $dataOld->jumlah_kasbon += $model->jumlah_kasbon;
                //         $dataOld->jumlah_potong = 0;
                //         $dataOld->tanggal_potong = $model->tanggal_mulai_potong;
                //         $dataOld->angsuran = $model->angsuran_perbulan;
                //         $dataOld->status_potongan = 0;
                //         $dataOld->autodebt = $model->tipe_potongan;
                //         $dataOld->sisa_kasbon += $model->jumlah_kasbon;
                //         $dataOld->created_at = time();
                //         $dataOld->deskripsi = 'Top-up Kasbon';

                //         $dataOld->save(false);
                //     } else {
                //         // Jika belum ada, buat baru
                //         $pembayaran = new \backend\models\PembayaranKasbon();
                //         $pembayaran->id_karyawan = $model->id_karyawan;
                //         $pembayaran->id_kasbon = $idKasbonBaru;
                //         $pembayaran->jumlah_kasbon = $model->jumlah_kasbon;
                //         $pembayaran->jumlah_potong = 0;
                //         $pembayaran->tanggal_potong = $model->tanggal_mulai_potong;
                //         $pembayaran->angsuran = $model->angsuran_perbulan;
                //         $pembayaran->status_potongan = 0;
                //         $pembayaran->autodebt = $model->tipe_potongan;
                //         $pembayaran->sisa_kasbon = $model->jumlah_kasbon;
                //         $pembayaran->created_at = time();
                //         $pembayaran->deskripsi = 'Kasbon Baru';
                //         $pembayaran->save(false);
                //     }
                // }

                Yii::$app->session->setFlash('success', 'Berhasil membuat pengajuan kasbon.');
                return $this->redirect(['/tanggapan/kasbon']);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal membuat pengajuan kasbon.');
                return $this->redirect(['/tanggapan/kasbon']);
            }
        }

        return $this->render('/home/tanggapan/kasbon/create', compact('model', 'karyawanBawahanAdmin'));
    }



    // ========================================
    // UPDATE KASBON
    // ========================================
    public function actionKasbonUpdate($id)
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;

        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $pengajuanKasbon = PengajuanKasbon::findOne($id);

        if ($this->request->isPost && $pengajuanKasbon->load($this->request->post())) {
            $pengajuanKasbon->disetujui_oleh = Yii::$app->user->identity->id;
            $pengajuanKasbon->tanggal_disetujui = date('Y-m-d');
            $pengajuanKasbon->status = 1; // disetujui
            $pengajuanKasbon->updated_at = time();
            $pengajuanKasbon->updated_by = Yii::$app->user->identity->id;

            if ($pengajuanKasbon->save()) {

                // 🔹 Setelah Save → update PembayaranKasbon
                if ($pengajuanKasbon->status == 1) {
                    $idKasbonBaru = $pengajuanKasbon->id_pengajuan_kasbon;
                    $dataOld = \backend\models\PembayaranKasbon::find()
                        ->where([
                            'id_karyawan' => $pengajuanKasbon->id_karyawan,
                            'status_potongan' => 0,
                        ])
                        ->orderBy(['created_at' => SORT_DESC])
                        ->one();

                    if ($dataOld) {
                        // Jika sudah ada kasbon aktif (belum lunas)
                        if ($dataOld->id_kasbon == $idKasbonBaru) {
                            // Update data lama (top-up pada kasbon yang sama)
                            $dataOld->jumlah_kasbon = $pengajuanKasbon->jumlah_kasbon;
                            $dataOld->sisa_kasbon = $pengajuanKasbon->jumlah_kasbon;
                        } else {
                            // Jika kasbon baru tapi masih ada sisa lama → tambahkan
                            $dataOld->id_kasbon = $idKasbonBaru;
                            $dataOld->jumlah_kasbon = $dataOld->jumlah_kasbon + $pengajuanKasbon->jumlah_kasbon;
                            $dataOld->sisa_kasbon = $dataOld->sisa_kasbon + $pengajuanKasbon->jumlah_kasbon;
                        }

                        $dataOld->tanggal_potong = $pengajuanKasbon->tanggal_mulai_potong;
                        $dataOld->angsuran = $pengajuanKasbon->angsuran_perbulan;
                        $dataOld->autodebt = $pengajuanKasbon->tipe_potongan;
                        $dataOld->created_at = time();
                        $dataOld->deskripsi = 'Top-up Kasbon';

                        if ($dataOld->save(false)) {
                            Yii::$app->session->setFlash('success', 'Data kasbon berhasil diperbarui (Top-up Kasbon).');
                        } else {
                            Yii::$app->session->setFlash('error', 'Gagal memperbarui data kasbon lama.');
                        }
                    } else {
                        // Jika belum ada kasbon aktif, buat baru
                        $pembayaran = new \backend\models\PembayaranKasbon();
                        $pembayaran->id_karyawan = $pengajuanKasbon->id_karyawan;
                        $pembayaran->id_kasbon = $idKasbonBaru;
                        $pembayaran->jumlah_kasbon = $pengajuanKasbon->jumlah_kasbon;
                        $pembayaran->jumlah_potong = 0;
                        $pembayaran->tanggal_potong = $pengajuanKasbon->tanggal_mulai_potong;
                        $pembayaran->angsuran = $pengajuanKasbon->angsuran_perbulan;
                        $pembayaran->status_potongan = 0;
                        $pembayaran->autodebt = $pengajuanKasbon->tipe_potongan;
                        $pembayaran->sisa_kasbon = $pengajuanKasbon->jumlah_kasbon;
                        $pembayaran->created_at = time();
                        $pembayaran->deskripsi = 'Kasbon Baru';

                        if ($pembayaran->save(false)) {
                            Yii::$app->session->setFlash('success', 'Kasbon baru berhasil ditambahkan.');
                        } else {
                            Yii::$app->session->setFlash('error', 'Gagal menambahkan kasbon baru.');
                        }
                    }
                }

                // 🔔 Kirim notifikasi
                $adminUsers = User::find()->where(['id_karyawan' => $pengajuanKasbon->id_karyawan])->all();
                $sender = Yii::$app->user->identity->id;
                $params = [
                    'judul' => 'Pengajuan Kasbon',
                    'deskripsi' => 'Pengajuan kasbon Anda telah ditanggapi oleh atasan.',
                    'nama_transaksi' => "/panel/pengajuan/kasbon-detail?id",
                    'id_transaksi' => $pengajuanKasbon['id_pengajuan_kasbon'],
                ];
                $this->sendNotif($params, $sender, $pengajuanKasbon, $adminUsers, "Pengajuan kasbon");

                Yii::$app->session->setFlash('success', 'Pengajuan kasbon berhasil diperbarui.');
                return $this->redirect(['/tanggapan/kasbon-view', 'id_pengajuan_kasbon' => $pengajuanKasbon->id_pengajuan_kasbon]);
            }

            Yii::$app->session->setFlash('error', 'Gagal mengupdate pengajuan kasbon.');
        }

        $this->layout = 'mobile-main';
        return $this->render('/home/tanggapan/kasbon/update', [
            'model' => $pengajuanKasbon,
            'karyawanBawahanAdmin' => $karyawanBawahanAdmin,
        ]);
    }


    // ========================================
    // VIEW KASBON
    // ========================================
    public function actionKasbonView($id_pengajuan_kasbon)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanKasbon::find()->where(['id_pengajuan_kasbon' => $id_pengajuan_kasbon])->one();
        return $this->render('/home/tanggapan/kasbon/view', compact('model'));
    }

    // ========================================
    // DELETE KASBON
    // ========================================
    public function actionKasbonDelete($id_pengajuan_kasbon)
    {
        $model = PengajuanKasbon::findOne($id_pengajuan_kasbon);
        if ($model && $model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil menghapus pengajuan kasbon.');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menghapus pengajuan kasbon.');
        }

        return $this->redirect(['/tanggapan/kasbon']);
    }
    // end kasbon











    // deviasi absensi

    public function actionAbsensi()
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;
        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();
        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');
        $pengajuanAbsensiList = PengajuanAbsensi::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->all();

        $this->layout = 'mobile-main';


        $model = new PengajuanAbsensi();
        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->disetujui_oleh = Yii::$app->user->identity->id;
            $model->disetujui_pada = date('Y-m-d H:i:s');
            $model->save();
            $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
            $sender = Yii::$app->user->identity->id;

            $params = [
                'judul' => 'Pengajuan absensi',
                'deskripsi' => 'Pengajuan Absensi  Anda Telah Ditanggapi Oleh Atasan.',
                'nama_transaksi' => "/panel/pengajuan/absensi-detail?id",
                'id_transaksi' => $model['id_pengajuan_absensi'],
            ];
            $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan absensi  ");


            return $this->redirect(['view', 'id_pengajuan_absensi' => $model->id_pengajuan_absensi]);
        }


        return $this->render('/home/tanggapan/absensi/index', compact('pengajuanAbsensiList', 'model',));
    }

    public function actionAbsensiView($id)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanAbsensi::find()->where(['id' => $id])->one();
        return $this->render('/home/tanggapan/absensi/view', compact('model'));
    }
    public function actionAbsensiUpdate($id)
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;


        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();

        $pengajuanAbsensi = PengajuanAbsensi::find()->where(['id' => $id])->one();


        $model = PengajuanAbsensi::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->id_approver     = Yii::$app->user->identity->id;
            $model->tanggal_disetujui = date('Y-m-d H:i:s');

            if ($model->save()) {

                if ($model->status == 1) {
                    $absensi = new Absensi();
                    $absensi->id_karyawan = $model->id_karyawan;
                    $absensi->jam_masuk = $model->jam_masuk;
                    $absensi->jam_pulang = $model->jam_keluar;
                    $absensi->keterangan = $model->alasan_pengajuan;
                    $absensi->created_at = date('Y-m-d H:i:s');
                    $absensi->created_by = Yii::$app->user->identity->id;
                    $absensi->updated_at = date('Y-m-d H:i:s');
                    $absensi->updated_by = Yii::$app->user->identity->id;
                    $absensi->kode_status_hadir = 'H';
                    $absensi->tanggal = date('Y-m-d', strtotime($model->tanggal_absen));

                    if ($absensi->save()) {
                        $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                        $sender = Yii::$app->user->identity->id;

                        $params = [
                            'judul' => 'Pengajuan Absensi',
                            'deskripsi' => 'Pengajuan Absensi  Anda Telah Ditanggapi Oleh Atasan.',
                            'nama_transaksi' => "/panel/pengajuan/absensi-detail?id",
                            'id_transaksi' => $model['id'],
                        ];
                        $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan Absensi ");
                        return $this->redirect(['/tanggapan/absensi-view', 'id' => $model->id]);
                    } else {
                        Yii::$app->session->setFlash('error', 'gagal menambahkan absensi karyawan pada tanggal ' . $model->tanggal_absen);
                    }
                } else {

                    Yii::$app->session->setFlash('succes', 'berhasil mengupdate pengajuan');
                }
            } else {
                Yii::$app->session->setFlash('error', 'gagal mengupdate pengajuan');
            }
        }


        $this->layout = 'mobile-main';
        return $this->render('/home/tanggapan/absensi/update', [
            'model' => $pengajuanAbsensi,
            'karyawanBawahanAdmin' => $karyawanBawahanAdmin,
        ]);
    }


    public function actionAbsensiDelete($id)
    {
        $model = PengajuanAbsensi::find()->where(['id' => $id])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Pengajuan');
            return $this->redirect(['/tanggapan/absensi']);
        }

        Yii::$app->session->setFlash('error', 'Gagal Menghapus Pengajuan');
        return $this->redirect(['/tanggapan/absensi']);
    }



    // pulang cepat
    public function actionPulangCepat()
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;

        // Ambil karyawan bawahan
        $bawahan = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()->all();
        $idKaryawanList = array_column($bawahan, 'id_karyawan');

        // Ambil daftar pengajuan
        $listPengajuan = IzinPulangCepat::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->orderBy(['tanggal' => SORT_DESC])
            ->all();

        // Layout mobile
        $this->layout = 'mobile-main';

        return $this->render('/home/tanggapan/pulang-cepat/index', [
            'listPengajuan' => $listPengajuan
        ]);
    }

    public function actionPulangCepatView($id)
    {
        $this->layout = 'mobile-main';
        $model = IzinPulangCepat::findOne($id);

        return $this->render('/home/tanggapan/pulang-cepat/view', [
            'model' => $model
        ]);
    }

    public function actionPulangCepatUpdate($id)
    {
        $model = IzinPulangCepat::findOne($id);
        $id_admin = Yii::$app->user->identity->id_karyawan;

        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->disetujui_oleh = Yii::$app->user->identity->id;
            $model->disetujui_pada = date('Y-m-d H:i:s');

            if ($model->save()) {

                $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                $sender = Yii::$app->user->identity->id;

                $params = [
                    'judul' => 'Pengajuan Pulang Cepat',
                    'deskripsi' => 'Pengajuan Pulang Cepat Anda telah ditanggapi',
                    'nama_transaksi' => "/panel/home/pulang-cepat",
                    'id_transaksi' => '',
                ];

                $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan perubahan pulang cepat");


                Yii::$app->session->setFlash('success', 'Berhasil memperbarui pengajuan');
                return $this->redirect(['pulang-cepat-view', 'id' => $model->id_izin_pulang_cepat]);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal memperbarui pengajuan');
            }
        }

        $this->layout = 'mobile-main';
        return $this->render('/home/tanggapan/pulang-cepat/update', [
            'model' => $model,
            'karyawanBawahanAdmin' => $karyawanBawahanAdmin,
        ]);
    }

    public function actionPulangCepatDelete($id)
    {
        $model = IzinPulangCepat::findOne($id);

        if ($model && $model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil menghapus pengajuan');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menghapus pengajuan');
        }

        return $this->redirect(['pulang-cepat']);
    }




    public function actionTugasLuar()
    {
        $id_admin = Yii::$app->user->identity->id_karyawan;
        $karyawanBawahanAdmin = AtasanKaryawan::find()
            ->where(['id_atasan' => $id_admin])
            ->asArray()
            ->all();
        $idKaryawanList = array_column($karyawanBawahanAdmin, 'id_karyawan');
        $pengajuanTugasLuarList = PengajuanTugasLuar::find()
            ->where(['id_karyawan' => $idKaryawanList])
            ->all();

        $this->layout = 'mobile-main';


        $model = new PengajuanTugasLuar();
        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->disetujui_oleh = Yii::$app->user->identity->id;
            $model->disetujui_pada = date('Y-m-d H:i:s');
            $model->save();
            $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
            $sender = Yii::$app->user->identity->id;

            $params = [
                'judul' => 'Pengajuan tugas luar',
                'deskripsi' => 'Pengajuan Tugas Luar  Anda Telah Ditanggapi Oleh Atasan.',
                'nama_transaksi' => "/panel/pengajuan/tugas-luar-detail?id",
                'id_transaksi' => $model['id_tugas_luar'],
            ];
            $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan tugas-luar");


            return $this->redirect(['view', 'id_tugas_luar' => $model->id_tugas_luar]);
        }


        return $this->render('/home/tanggapan/tugas-luar/index', compact('pengajuanTugasLuarList', 'model',));
    }

    public function actionTugasLuarView($id)
    {
        $this->layout = 'mobile-main';
        $model = PengajuanTugasLuar::find()->where(['id_tugas_luar' => $id])->one();
        $detail = DetailTugasLuar::find()->where(['id_tugas_luar' => $id])->all();

        return $this->render('/home/tanggapan/tugas-luar/view', compact('model', "detail"));
    }
    public function actionTugasLuarUpdate($id_tugas_luar)
    {
        $model = PengajuanTugasLuar::find()->where(['id_tugas_luar' => $id_tugas_luar])->one();
        $detailModels = $model->detailTugasLuars;

        if (empty($detailModels)) {
            $detailModels = [new DetailTugasLuar()];
        }

        if ($this->request->isPost) {
            $postData = $this->request->post();
            $transaction = Yii::$app->db->beginTransaction();

            try {
                // Load and save main model
                if ($model->load($postData)) {
                    $model->updated_at = date('Y-m-d H:i:s');
                    $model->updated_by = Yii::$app->user->identity->id;

                    if (!$model->save()) {
                        throw new \Exception('Gagal menyimpan pengajuan: ' . json_encode($model->errors));
                    }

                    // Process detail data
                    $details = $postData['DetailTugasLuar'] ?? [];

                    // Get existing detail IDs
                    $existingDetails = DetailTugasLuar::find()
                        ->where(['id_tugas_luar' => $model->id_tugas_luar])
                        ->indexBy('id_detail')
                        ->all();

                    $savedDetails = [];
                    $urutan = 1;

                    foreach ($details as $detailData) {
                        if (!empty($detailData['id_detail']) && isset($existingDetails[$detailData['id_detail']])) {
                            // Update existing detail
                            $detail = $existingDetails[$detailData['id_detail']];
                            unset($existingDetails[$detailData['id_detail']]); // Remove from delete list
                        } else {
                            // Create new detail
                            $detail = new DetailTugasLuar();
                            $detail->id_tugas_luar = $model->id_tugas_luar;
                            $detail->created_at = date('Y-m-d H:i:s');
                        }

                        // Set default values if not provided
                        $detailData['status_check'] = $detailData['status_check'] ?? 0;
                        $detailData['status_pengajuan_detail'] = $detailData['status_pengajuan_detail'] ?? 1;
                        $detailData['urutan'] = $urutan++;

                        $detail->updated_at = date('Y-m-d H:i:s');

                        if (!$detail->load($detailData, '') || !$detail->save()) {
                            throw new \Exception(
                                'Gagal menyimpan detail: ' . json_encode($detail->errors) .
                                    ' Data: ' . json_encode($detailData)
                            );
                        }

                        $savedDetails[] = $detail;
                    }

                    // Delete details that were removed
                    if (!empty($existingDetails)) {
                        DetailTugasLuar::deleteAll(['id_detail' => array_keys($existingDetails)]);
                    }

                    // Validate at least one detail exists
                    if (empty($savedDetails)) {
                        throw new \Exception('Setidaknya harus ada satu detail tugas');
                    }

                    $transaction->commit();

                    // Send notification
                    $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                    $sender = Yii::$app->user->identity->id;

                    $params = [
                        'judul' => 'Pengajuan Tugas Luar',
                        'deskripsi' => 'Pengajuan Tugas Luar Anda Telah Ditanggapi Oleh Atasan.',
                        'nama_transaksi' => "/panel/pengajuan/tugas-luar-detail?id=",
                        'id_transaksi' => $model->id_tugas_luar,
                    ];
                    $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan Tugas luar ");

                    Yii::$app->session->setFlash('success', 'Data berhasil diperbarui');
                    return $this->redirect(['tugas-luar-view', 'id' => $model->id_tugas_luar]);
                } else {
                    throw new \Exception('Gagal memproses data pengajuan');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', 'Gagal menyimpan: ' . $e->getMessage());

                // Reload detail models for form
                $detailModels = [];
                foreach ($postData['DetailTugasLuar'] as $detailData) {
                    $detail = new DetailTugasLuar();
                    $detail->attributes = $detailData;
                    $detailModels[] = $detail;
                }
            }
        }

        $this->layout = 'mobile-main';
        return $this->render('/home/tanggapan/tugas-luar/update', [
            'model' => $model,
            'detailModels' => $detailModels,
        ]);
    }


    public function actionTugasLuarDelete($id_tugas_luar)
    {
        $model = PengajuanTugasLuar::find()->where(['id_tugas_luar' => $id_tugas_luar])->one();

        if ($model->detailTugasLuars) {
            foreach ($model->detailTugasLuars as $detail) {
                if (!empty($detail->bukti_foto)) {
                    $filePath = Yii::getAlias('@webroot/uploads/bukti_tugas_luar/') . $detail->bukti_foto;
                    if (file_exists($filePath) && is_file($filePath) && !unlink($filePath)) {
                        throw new \Exception("Gagal menghapus file: " . $detail->bukti_foto);
                    }
                }
            }
        }
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Pengajuan');
            return $this->redirect(['/tanggapan/tugas-luar']);
        }

        Yii::$app->session->setFlash('error', 'Gagal Menghapus Pengajuan');
        return $this->redirect(['/tanggapan/tugas-luar']);
    }


    public function actionTugasLuarDeleteDetail($id_tugas_luar, $id_detail)
    {
        $model = DetailTugasLuar::find()->where(['id_detail' => $id_detail])->one();

        if (!empty($model->bukti_foto)) {
            $filePath = Yii::getAlias('@webroot/uploads/bukti_tugas_luar/') . $model->bukti_foto;
            if (file_exists($filePath) && is_file($filePath) && !unlink($filePath)) {
                throw new \Exception("Gagal menghapus file: " . $model->bukti_foto);
            }
        }
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Detail Pengajuan');
            return $this->redirect(['/tanggapan/tugas-luar-view', 'id' => $id_tugas_luar]);
        }
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
            if ($model->status == '1') {

                $tanggalMulai = $model['tanggal_awal']; // dari form
                $tanggalSelesai = $model['tanggal_akhir']; // dari form
                $start = new \DateTime($tanggalMulai);
                $end = new \DateTime($tanggalSelesai);
                $end = $end->modify('+1 day'); // supaya termasuk tanggal selesai

                $successCount = 0;
                $failedCount = 0;

                $interval = new \DateInterval('P1D');
                $dateRange = new \DatePeriod($start, $interval, $end);

                foreach ($dateRange as $date) {
                    $tanggal = $date->format('Y-m-d');

                    // Cek apakah data untuk tanggal & karyawan ini sudah ada
                    $jadwal = JadwalShift::findOne([
                        'id_karyawan' => $model->id_karyawan,
                        'tanggal' => $tanggal
                    ]);




                    if (!$jadwal) {
                        $jadwal = new JadwalShift();
                        $jadwal->id_karyawan = $model->id_karyawan;
                        $jadwal->tanggal = $tanggal;
                        $jadwal->id_shift_kerja = $model->id_shift_kerja;
                    }
                    $jadwal->id_shift_kerja = $model->id_shift_kerja;

                    if ($jadwal->save()) {
                        $successCount++;
                    } else {
                        $failedCount++;
                    }
                }
                if ($successCount > 0) {
                    Yii::$app->session->setFlash('success', "$successCount shift berhasil diubah.");
                }
                if ($failedCount > 0) {
                    Yii::$app->session->setFlash('error', "$failedCount shift gagal diubah.");
                }
            }
            if ($model->save()) {
                $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                $sender = Yii::$app->user->identity->id;

                $params = [
                    'judul' => 'Pengajuan Perubahan shift',
                    'deskripsi' => 'Pengajuan Perubahan Shift luar Anda Telah Ditanggapi Oleh Atasan.',
                    'nama_transaksi' => "/panel/home/lihat-shift?id_karyawan",
                    'id_transaksi' => $model['id_karyawan'],
                ];
                $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan perubahan shift  ");

                Yii::$app->session->setFlash('success', 'Berhasil Mengupdate Pengajuan');

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
    public function actionShiftDelete($id)
    {
        $model = PengajuanShift::findOne($id);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Pengajuan');
            return $this->redirect(['/tanggapan/shift']);
        } else {
            Yii::$app->session->setFlash('error', 'Gagal Menghapus Pengajuan');
        }
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

        $msgToCheck =  $this->renderPartial('@backend/views/home/pengajuan/email', compact('model', 'params'));


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
