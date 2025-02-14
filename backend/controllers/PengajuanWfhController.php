<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\KaryawanHelper;
use backend\models\helpers\NotificationHelper;
use backend\models\JamKerjaKaryawan;
use backend\models\MasterKode;
use backend\models\PengajuanWfh;
use backend\models\PengajuanWfhSearch;
use DateTime;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PengajuanWfhController implements the CRUD actions for PengajuanWfh model.
 */
class PengajuanWfhController extends Controller
{
    /**
     * @inheritDoc
     */
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
                                return $user->can('admin') || $user->can('super_admin');
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all PengajuanWfh models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
        $lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
        $tgl_mulai = $firstDayOfMonth;
        $tgl_selesai = $lastdate;
        $searchModel = new PengajuanWfhSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $tgl_mulai, $tgl_selesai);

        if ($this->request->isPost) {
            $tgl_mulai = $this->request->post('PengajuanWfhSearch')['tanggal_mulai'];
            $tgl_selesai = $this->request->post('PengajuanWfhSearch')['tanggal_selesai'];
            $dataProvider = $searchModel->search($searchModel, $tgl_mulai, $tgl_selesai);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai
        ]);
    }

    /**
     * Displays a single PengajuanWfh model.
     * @param int $id_pengajuan_wfh Id Pengajuan Wfh
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_pengajuan_wfh)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_pengajuan_wfh),
        ]);
    }

    /**
     * Creates a new PengajuanWfh model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanWfh();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $mulai = strtotime($model->tanggal_mulai);
                $selesai = strtotime($model->tanggal_selesai);

                // Validasi tanggal
                if ($mulai > $selesai) {
                    Yii::$app->session->setFlash('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
                    return $this->redirect(['create']); // Menghentikan eksekusi dan kembali ke form
                }

                // Inisialisasi array untuk menyimpan tanggal
                $tanggalArray = [];
                $startDate = new DateTime($model->tanggal_mulai);
                $endDate = new DateTime($model->tanggal_selesai);

                // Loop untuk menambahkan tanggal ke dalam array
                while ($startDate <= $endDate) {
                    // Tambahkan tanggal ke dalam array
                    $tanggalArray[] = $startDate->format('d-m-Y'); // Format sesuai yang diinginkan
                    // Tambah satu hari
                    $startDate->modify('+1 day');
                }

                // Menyimpan tanggal_array dalam format JSON
                $model->tanggal_array = json_encode($tanggalArray);

                // Menyimpan tanggal_mulai dan tanggal_selesai dalam format yang diinginkan
                $model->tanggal_mulai = date('Y-m-d', $mulai);
                $model->tanggal_selesai = date('Y-m-d', $selesai);

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil menyimpan data pengajuan WFH.');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal menyimpan data pengajuan WFH, pastikan data yang anda masukkan benar.');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing PengajuanWfh model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_pengajuan_wfh Id Pengajuan Wfh
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_pengajuan_wfh)
    {
        $model = $this->findModel($id_pengajuan_wfh);

        $tgl = json_decode($model['tanggal_array']);
        $tglmulai = $tgl[0] ?? date('Y') . "-01-01";
        $tanggalSelesai = end($tgl);
        $model->disetujui_oleh = Yii::$app->user->identity->id;


        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $mulai = strtotime($model->tanggal_mulai);

            $selesai = strtotime($model->tanggal_selesai);


            if ($mulai > $selesai) {
                Yii::$app->session->setFlash('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
                return $this->redirect(['index']);
            }

            $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $model->id_karyawan])->one();
            $namaJamKerja = strtolower($jamKerjaKaryawan->jamKerja['nama_jam_kerja']);
            // Inisialisasi jumlahHari berdasarkan nama jam kerja
            if (strpos($namaJamKerja, '6 hari kerja') !== false) {
                $jumlahHari = 6;
            } elseif (strpos($namaJamKerja, '5 hari kerja') !== false) {
                $jumlahHari = 5;
            } elseif (strpos($namaJamKerja, '4 hari kerja') !== false) {
                $jumlahHari = 4;
            } else {
                $jumlahHari = 0; // Default jika tidak ada yang cocok
            }

            $tanggalArray = [];
            $startDate = new DateTime($model->tanggal_mulai);
            $endDate = new DateTime($model->tanggal_selesai);

            while ($startDate <= $endDate && count($tanggalArray) < $jumlahHari) {
                // Cek apakah hari ini adalah Sabtu (6) atau Minggu (0)
                if ($jumlahHari === 5) {
                    // Untuk 5 hari, simpan hanya Senin hingga Jumat
                    if ($startDate->format('N') < 6) { // 1 (Senin) sampai 5 (Jumat)
                        $tanggalArray[] = $startDate->format('Y-m-d');
                    }
                } else {
                    // Untuk 6 hari, simpan Senin hingga Sabtu
                    if ($startDate->format('N') < 7) { // 1 (Senin) sampai 6 (Sabtu)
                        $tanggalArray[] = $startDate->format('Y-m-d');
                    }
                }
                // Tambah satu hari
                $startDate->modify('+1 day');
            }

            // Encode tanggal_array menjadi JSON
            $model->tanggal_array = json_encode($tanggalArray);

            // Menyimpan tanggal_mulai dan tanggal_selesai dalam format yang diinginkan
            $model->tanggal_mulai = date('Y-m-d', $mulai);
            $model->tanggal_selesai = date('Y-m-d', $selesai);

            if ($model->save()) {

                $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                $sender = Yii::$app->user->identity->id;

                $params = [
                    'judul' => 'Pengajuan wfh',
                    'deskripsi' => 'Pengajuan WFH anda telah ditanggapi oleh atasan .',
                    'nama_transaksi' => "wfh",
                    'id_transaksi' => $model['id_pengajuan_wfh'],
                ];
                $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan wfh Baru Dari " . $model->karyawan->nama);



                Yii::$app->session->setFlash('success', 'Berhasil menyimpan data pengajuan WFH.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal menyimpan data pengajuan WFH ,  pastikan data yang anda masukkan benar.');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'tglmulai' => $tglmulai,
            'tglselesai' => $tanggalSelesai
        ]);
    }

    /**
     * Deletes an existing PengajuanWfh model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pengajuan_wfh Id Pengajuan Wfh
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_pengajuan_wfh)
    {
        $this->findModel($id_pengajuan_wfh)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PengajuanWfh model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pengajuan_wfh Id Pengajuan Wfh
     * @return PengajuanWfh the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pengajuan_wfh)
    {
        if (($model = PengajuanWfh::findOne(['id_pengajuan_wfh' => $id_pengajuan_wfh])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }



    // send notif
    public function sendNotif($params, $sender, $model, $adminUsers, $subject = "Pengajuan Di Tanggapi")
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
        $msgToCheck = $this->renderPartial('@backend/views/home/pengajuan/email', compact('model', 'params'));

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
