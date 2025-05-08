<?php

namespace backend\controllers;

use amnah\yii2\user\models\User;
use backend\models\helpers\EmailHelper;
use backend\models\helpers\NotificationHelper;
use backend\models\MasterKode;
use backend\models\PengajuanLembur;
use backend\models\PengajuanLemburSearch;
use backend\models\SettinganUmum;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PengajuanLemburController implements the CRUD actions for PengajuanLembur model.
 */
class PengajuanLemburController extends Controller
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
     * Lists all PengajuanLembur models.
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
        $tgl_mulai =  Yii::$app->request->get() == [] ? $firstDayOfMonth :  Yii::$app->request->get()['PengajuanLemburSearch']['tanggal_mulai'];
        $tgl_selesai =  Yii::$app->request->get() == [] ? $lastdate :  Yii::$app->request->get()['PengajuanLemburSearch']['tanggal_selesai'];
        $searchModel = new PengajuanLemburSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $tgl_mulai, $tgl_selesai);
        // mematikan pagination
        $dataProvider->pagination = false;
        $total = 0;
        $models = $dataProvider->getModels(); // Ambil semua model dari dataProvider

        foreach ($models as $model) {
            $total += $model->hitungan_jam; // Asumsi field hitungan_jam ada di model PengajuanLembur
        }


        return $this->render('index', [
            'total' => $total,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai
        ]);
    }

    /**
     * Displays a single PengajuanLembur model.
     * @param int $id_pengajuan_lembur Id Pengajuan Lembur
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_pengajuan_lembur)
    {
        $model = $this->findModel($id_pengajuan_lembur);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new PengajuanLembur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanLembur();
        $poinArray = [];
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->pekerjaan = json_encode(Yii::$app->request->post('pekerjaan'));
                // $model->disetujui_oleh = Yii::$app->user->identity->id;
                if ($model->status == Yii::$app->params['disetujui']) {
                    $model->disetujui_oleh = Yii::$app->user->identity->id;
                    $model->disetujui_pada = date('Y-m-d H:i:s');
                }

                $jamMulai = strtotime($model->jam_mulai);
                $jamSelesai = strtotime($model->jam_selesai);

                // Jika jam selesai lebih kecil dari jam mulai, berarti sudah berbeda hari
                if ($jamSelesai < $jamMulai) {
                    $jamSelesai += 24 * 60 * 60; // Tambahkan 24 jam dalam detik
                }

                // Pembulatan jam_selesai jika menitnya adalah 59
                if (date('i', $jamSelesai) == 59) {
                    $jamSelesai = strtotime('+1 hour', $jamSelesai);
                    $jamSelesai = strtotime(date('Y-m-d H:00', $jamSelesai)); // Set menit ke 0
                }

                // Menghitung selisih waktu dalam detik
                $selisihDetik = $jamSelesai - $jamMulai;

                // Mengkonversi selisih waktu ke dalam format jam:menit
                $model->durasi = gmdate('H:i', $selisihDetik);

                // Menghitung durasi dalam menit
                $durasiMenit = $selisihDetik / 60; // Durasi dalam menit
                $durasiJam = floor($durasiMenit / 60); // Durasi dalam jam (dibulatkan ke bawah)
                $durasiMenitSisa = $durasiMenit % 60; // Sisa menit setelah dibagi jam

                // Menghitung jumlah jam lembur sesuai dengan aturan yang diberikan
                $hitunganLembur = 0;

                // Ambil settingan umum kalkulasi_jam_lembur
                $settingKalkulasi = SettinganUmum::find()
                    ->where(['kode_setting' => 'kalkulasi_jam_lembur'])
                    ->one();


                $hitunganLembur = 0;

                if ($settingKalkulasi && $settingKalkulasi->nilai_setting == 0) {
                    // Perhitungan versi 1 (seperti sebelumnya)

                    // Hitung jam pertama (selalu 1.5 jam untuk 1 jam pertama)
                    if ($durasiJam >= 1) {
                        $hitunganLembur += 1.5; // 1 jam pertama dihitung 1.5 jam
                        $durasiJam -= 1; // Kurangi jam pertama
                    }

                    // Hitung jam berikutnya (jam kedua dan seterusnya dihitung 2 jam per jam)
                    if ($durasiJam > 0) {
                        $hitunganLembur += $durasiJam * 2; // Setiap jam setelah jam pertama dihitung 2 jam
                    }

                    // Menambahkan waktu sisa menit
                    if ($durasiMenitSisa > 0) {
                        if ($durasiMenitSisa >= 30) {
                            $hitunganLembur += 1; // Tambah 1 jam
                        } else {
                            $hitunganLembur += 0.5; // Tambah 0.5 jam
                        }
                    }
                } else {
                    // Perhitungan versi 2 (1:1, tanpa pengali)
                    $hitunganLembur = round($durasiMenit / 60, 2); // Hitung jam lembur dengan 2 digit desimal
                }

                // Menyimpan hasil hitungan lembur
                $model->hitungan_jam = $hitunganLembur;

                // Simpan data pengajuan lembur
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Pengajuan Lembur Berhasil Ditambahkan');
                } else {
                    Yii::$app->session->setFlash('error', 'Pengajuan Lembur Gagal Ditambahkan');
                }

                return $this->redirect(['view', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'poinArray' => $poinArray,
        ]);
    }



    /**
     * Updates an existing PengajuanLembur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_pengajuan_lembur Id Pengajuan Lembur
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_pengajuan_lembur)
    {
        $model = $this->findModel($id_pengajuan_lembur);
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

            // Simpan pengajuan lembur yang telah diupdate
            if ($model->save()) {

                // Kirim notifikasi kepada admin atau pihak terkait
                $adminUsers = User::find()->where(['id_karyawan' => $model->id_karyawan])->all();
                $sender = Yii::$app->user->identity->id;

                $params = [
                    'judul' => 'Pengajuan lembur',
                    'deskripsi' => 'Pengajuan Lembur Anda telah ditanggapi oleh atasan.',
                    'nama_transaksi' => "lembur",
                    'id_transaksi' => $model['id_pengajuan_lembur'],
                ];

                $this->sendNotif($params, $sender, $model, $adminUsers, "Pengajuan lembur Baru Dari " . $model->karyawan->nama);

                // Redirect ke halaman view setelah berhasil diupdate
                return $this->redirect(['view', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'poinArray' => $poinArray
        ]);
    }


    /**
     * Deletes an existing PengajuanLembur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pengajuan_lembur Id Pengajuan Lembur
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_pengajuan_lembur)
    {
        $this->findModel($id_pengajuan_lembur)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PengajuanLembur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pengajuan_lembur Id Pengajuan Lembur
     * @return PengajuanLembur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pengajuan_lembur)
    {
        if (($model = PengajuanLembur::findOne(['id_pengajuan_lembur' => $id_pengajuan_lembur])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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
