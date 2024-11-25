<?php

namespace backend\controllers;

use backend\models\AtasanKaryawan;
use backend\models\GajiPotongan;
use backend\models\GajiTunjangan;
use backend\models\helpers\KaryawanHelper;
use backend\models\helpers\PeriodeGajiHelper;
use backend\models\JamKerja;
use backend\models\Karyawan;
use backend\models\MasterLokasi;
use backend\models\PeriodeGaji;
use backend\models\TransaksiGaji;
use backend\models\TransaksiGajiSearch;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransaksiGajiController implements the CRUD actions for TransaksiGaji model.
 */
class TransaksiGajiController extends Controller
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
                                return $user->can('admin') && $user->can('super_admin');
                            },
                        ],
                    ],
                ],
            ]
        );
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            // Ini adalah insert baru
            Yii::$app->session->set('lastInsertedId', $this->id_transaksi_gaji);
        }
    }
    /**
     * Lists all TransaksiGaji models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new TransaksiGaji();
        $bulan = date('m');
        $tahun = date('Y');
        $searchModel = new TransaksiGajiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $bulan, $tahun, null, null);
        $karyawan = new Karyawan();
        $periode_gaji = new PeriodeGaji();

        $karyawanID = null;
        $periode_gajiID = PeriodeGajiHelper::getPeriodeGajiBulanIni();
        if ($this->request->isPost) {
            $karyawanID = Yii::$app->request->post('Karyawan')['id_karyawan'];
            $periode_gajiID = intval(Yii::$app->request->post('PeriodeGaji')['id_periode_gaji']);

            if (!$karyawanID) $karyawanID = null;
            if (!$periode_gajiID) $periode_gajiID = null;

            $periode_gaji = PeriodeGaji::findOne($periode_gajiID);
            $dataProvider = $searchModel->search($this->request->queryParams, $periode_gaji['bulan'], $periode_gaji['tahun'], $karyawanID, $periode_gajiID);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'karyawan' => $karyawan,
            'periode_gaji' => $periode_gaji,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'model' => $model,
            'karyawanID' => $karyawanID,
            'periode_gajiID' => $periode_gajiID
        ]);
    }


    public function actionReport()
    {
        $model = new TransaksiGaji();
        $bulan = date('m');
        $tahun = date('Y');
        $searchModel = new TransaksiGajiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $bulan, $tahun, null, null);
        $karyawan = new Karyawan();
        $periode_gaji = new PeriodeGaji();

        $karyawanID = null;
        $periode_gajiID = PeriodeGajiHelper::getPeriodeGajiBulanIni();

        return $this->renderPartial('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'karyawan' => $karyawan,
            'periode_gaji' => $periode_gaji,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'model' => $model,
            'karyawanID' => $karyawanID,
            'periode_gajiID' => $periode_gajiID
        ]);
    }


    /**
     * Displays a single TransaksiGaji model.
     * @param int $id_transaksi_gaji Id Transaksi Gaji
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_periode_gaji, $id_karyawan)
    {

        $karyawan = Karyawan::find()->where(['id_karyawan' => $id_karyawan])->asArray()->one();
        $transaksi_gaji = TransaksiGaji::find()
            ->where(['nomer_identitas' => $karyawan['nomer_identitas']])
            ->asArray()
            ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $transaksi_gaji,
            'pagination' => [
                'pageSize' => 10, // Jumlah item per halaman
            ],
            'sort' => [
                'attributes' => ['id', 'tanggal', 'jumlah'], // Sesuaikan dengan kolom yang Anda miliki
            ],
        ]);
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'karyawan' => $karyawan,
        ]);
    }

    public function actionCetak($id_transaksi_gaji)
    {
        $model = $this->findModel($id_transaksi_gaji);
        // $karyawan = Karyawan::find()->select(['id_karyawan'])->where(['kode_karyawan' => $model['kode_karyawan']])->asArray()->one();
        $jamKerja = JamKerja::find()->where(['id_jam_kerja' => $model['jam_kerja']])->one();
        // $penempatan = AtasanKaryawan::find()->where(['id_karyawan' => $karyawan['id_karyawan']])->one();
        // $masterLokasi = MasterLokasi::find()->where(['id_master_lokasi' => $penempatan['id_master_lokasi']])->one();
        // $perusahaan = Perusahaan::find()->where(['id_perusahaan' => $masterLokasi['id_perusahaan']])->one();
        $content = $this->renderPartial('_report', [
            'model' => $model,
            'jamKerja' => $jamKerja
        ]);


        $pdf = new Pdf([

            // 'mode' => Pdf::MODE_CORE,

            'format' => Pdf::FORMAT_A4,

            'orientation' => Pdf::ORIENT_PORTRAIT,

            'destination' => Pdf::DEST_BROWSER,

            'content' => $content,

            // 'cssFile' => 'https://cdn.tailwindcss.com',
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // 'cssInline' => '*{font-family: arial; font-size: 16px;}',

            'options' => ['title' => 'Slip Gaji Karyawan'],
            // 'methods' => [
            //     'SetFooter' => ['{PAGENO}'],
            // ]
        ]);

        return $pdf->render();
    }


    public function actionDetail($id_transaksi_gaji)
    {
        $tahunPeriode = date('Y');
        $model = $this->findModel($id_transaksi_gaji);
        $id_karyawan = KaryawanHelper::getIdKaryawan($model['kode_karyawan']);
        return $this->render('detail', [
            'model'  => $model,
            'tahunPeriode' => $tahunPeriode,
            'id_karyawan' => $id_karyawan
        ]);
    }
    /**
     * Creates a new TransaksiGaji model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($periode_gaji, $id_karyawan, $params = null)
    {
        $model = new TransaksiGaji();
        $periode_gaji = $model->getPeriodeGajiOne($periode_gaji);

        //! mengambil parameter
        if ($params != null) {
            $bulan = $params['bulan'];
            $tahun = $params['tahun'];
        } else {
            $bulan = $periode_gaji['bulan'];
            $tahun = $periode_gaji['tahun'];
        }

        $firstDayOfMonth = $periode_gaji['tanggal_awal'];
        $lastDayOfMonth = $periode_gaji['tanggal_akhir'];
        $karyawan = $model->getKaryawanData($id_karyawan, $periode_gaji['id_periode_gaji']);
        $dataPekerjaan = $model->getDataPekerjaan($id_karyawan);
        $absensiData = $model->getAbsensiData($id_karyawan, $firstDayOfMonth, $lastDayOfMonth);
        $totalCuti = $model->getTotalCutiKaryawan($karyawan, $firstDayOfMonth, $lastDayOfMonth);
        $gajiPokok = $model->getGajiPokok($id_karyawan);
        $jumlahJamLembur = $model->getJumlahJamLembur($id_karyawan, $firstDayOfMonth, $lastDayOfMonth);
        $getTunjangan = $model->getTunjangan($id_karyawan, true);
        $getPotongan = $model->getPotongan($id_karyawan, true);
        $getTerlambat = $model->getTotalTerlambat($id_karyawan, $firstDayOfMonth, $lastDayOfMonth);

        if (!$dataPekerjaan) {
            Yii::$app->session->setFlash('error', 'Data pekerjaan tidak ditemukan, lengkapi data pekerjaan terlebih dahulu');
            return $this->redirect(['transaksi-gaji/index']);
        }
        if (!$gajiPokok) {
            Yii::$app->session->setFlash('error', 'Data gaji pokok tidak ditemukan, lengkapi data gaji pokok terlebih dahulu');
            return $this->redirect(['transaksi-gaji/index']);
        }

        $rekapandata = [

            'karyawan' => $karyawan,
            'dataPekerjaan' => $dataPekerjaan,  //data pekerjaan
            'absensiData' => $absensiData,  //data absensi
            'totalCuti' => $totalCuti,  //total cuti
            'gajiPokok' => $gajiPokok,  //gaji pokok
            'jumlahJamLembur' => $jumlahJamLembur,
            'getTunjangan' => $getTunjangan,
            'getPotongan' => $getPotongan,
            'periode_gaji' => $periode_gaji,
            'getTerlambat' => $getTerlambat
        ];


        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $existingRecord = TransaksiGaji::find()
                    ->where(['periode_gaji' => $model->periode_gaji, 'kode_karyawan' => $karyawan['kode_karyawan']])
                    ->one();

                if ($existingRecord) {
                    Yii::$app->session->setFlash('error', 'Data dengan periode gaji dan kode karyawan yang sama sudah ada.');
                }


                // Jika belum ada, lanjutkan menyimpan
                if ($model->save()) {
                    $getIdTransaksiGaji = TransaksiGaji::find()->asArray()->select('id_transaksi_gaji')->where(['periode_gaji' => $model->periode_gaji, 'kode_karyawan' => $model->kode_karyawan])->one();

                    $tunjanganDetail =  $model->getTunjangan($id_karyawan, false);
                    $potonganDetail =  $model->getPotongan($id_karyawan, false);

                    if ($tunjanganDetail) {
                        $batchDataTunjangan = [];
                        foreach ($tunjanganDetail as  $detail) {
                            $batchDataTunjangan[] = [
                                'id_transaksi_gaji' => $getIdTransaksiGaji['id_transaksi_gaji'],
                                'id_tunjangan_detail' => $detail['id_tunjangan_detail'],
                                'nama_tunjangan' => $detail['id_tunjangan'],
                                'jumlah' => $detail['jumlah'],
                            ];
                        }

                        $columnsTunjangan = ['id_transaksi_gaji', 'id_tunjangan_detail', 'nama_tunjangan', 'jumlah'];
                        $insertedTunjangan = Yii::$app->db->createCommand()
                            ->batchInsert('gaji_tunjangan', $columnsTunjangan, $batchDataTunjangan)
                            ->execute();
                    }


                    if ($potonganDetail) {

                        $batchDataPotongan = [];
                        foreach ($potonganDetail as  $detail) {
                            $batchDataPotongan[] = [
                                'id_transaksi_gaji' => $getIdTransaksiGaji['id_transaksi_gaji'],
                                'id_potongan_detail' => $detail['id_potongan_detail'],
                                'nama_potongan' => $detail['id_potongan'],
                                'jumlah' => $detail['jumlah'],
                            ];
                        }

                        $columnsPotongan = ['id_transaksi_gaji', 'id_potongan_detail', 'nama_potongan', 'jumlah'];
                        $insertedPotongan = Yii::$app->db->createCommand()
                            ->batchInsert('gaji_potongan', $columnsPotongan, $batchDataPotongan)
                            ->execute();
                    }


                    return $this->redirect(['detail', 'id_transaksi_gaji' => $getIdTransaksiGaji['id_transaksi_gaji']]);
                } else {
                    // Jika gagal menyimpan, kembali ke form dengan pesan error
                    Yii::$app->session->setFlash('error', 'Gagal menyimpan data. Silakan cek kembali input Anda.');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'rekapandata' => $rekapandata,
            'id_karyawan' => $id_karyawan
        ]);
    }

    /**
     * Updates an existing TransaksiGaji model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_transaksi_gaji Id Transaksi Gaji
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_transaksi_gaji, $id_karyawan, $params = null)
    {

        $model = $this->findModel($id_transaksi_gaji);
        $periode_gaji = $model->getPeriodeGajiOne($model['periode_gaji']);
        //! mengambil parameter
        if ($params != null) {
            $bulan = $params['bulan'];
            $tahun = $params['tahun'];
        } else {
            $bulan = $periode_gaji['bulan'];
            $tahun = $periode_gaji['tahun'];
        }

        $firstDayOfMonth = $periode_gaji['tanggal_awal'];
        $lastDayOfMonth = $periode_gaji['tanggal_akhir'];
        $karyawan = $model->getKaryawanData($id_karyawan, $periode_gaji['id_periode_gaji']);
        $dataPekerjaan = $model->getDataPekerjaan($id_karyawan);
        $absensiData = $model->getAbsensiData($id_karyawan, $firstDayOfMonth, $lastDayOfMonth);
        $totalCuti = $model->getTotalCutiKaryawan($karyawan, $firstDayOfMonth, $lastDayOfMonth);
        $gajiPokok = $model->getGajiPokok($id_karyawan);
        $jumlahJamLembur = $model->getJumlahJamLembur($id_karyawan, $bulan, $tahun);
        $getTunjangan = $model->getTunjangan($id_karyawan, true);
        $getPotongan = $model->getPotongan($id_karyawan, true);

        if (!$dataPekerjaan) {
            Yii::$app->session->setFlash('error', 'Data pekerjaan tidak ditemukan, lengkapi data pekerjaan terlebih dahulu');
            return $this->redirect(['transaksi-gaji/index']);
        }
        if (!$gajiPokok) {
            Yii::$app->session->setFlash('error', 'Data gaji pokok tidak ditemukan, lengkapi data gaji pokok terlebih dahulu');
            return $this->redirect(['transaksi-gaji/index']);
        }

        $rekapandata = [

            'karyawan' => $karyawan,
            'dataPekerjaan' => $dataPekerjaan,  //data pekerjaan
            'absensiData' => $absensiData,  //data absensi
            'totalCuti' => $totalCuti,  //total cuti
            'gajiPokok' => $gajiPokok,  //gaji pokok
            'jumlahJamLembur' => $jumlahJamLembur,
            'getTunjangan' => $getTunjangan,
            'getPotongan' => $getPotongan,
            'periode_gaji' => $periode_gaji,
        ];




        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                if ($model->save()) {
                    $getIdTransaksiGaji = TransaksiGaji::find()->asArray()->select('id_transaksi_gaji')->where(['periode_gaji' => $model->periode_gaji, 'kode_karyawan' => $model->kode_karyawan])->one();
                    $tunjanganDetail =  $model->getTunjangan($id_karyawan, false);
                    $potonganDetail =  $model->getPotongan($id_karyawan, false);


                    $gajiTunjangan = GajiTunjangan::find()->where(['id_transaksi_gaji' => $getIdTransaksiGaji['id_transaksi_gaji']])->all();
                    $gajiPotongan = GajiPotongan::find()->where(['id_transaksi_gaji' => $getIdTransaksiGaji['id_transaksi_gaji']])->all();


                    // Hapus semua data yang ditemukan
                    foreach ($gajiTunjangan as $item) {
                        $item->delete();
                    }

                    foreach ($gajiPotongan as $item) {
                        $item->delete();
                    }
                    // die;



                    $batchDataTunjangan = [];
                    foreach ($tunjanganDetail as  $detail) {
                        $batchDataTunjangan[] = [
                            'id_transaksi_gaji' => $getIdTransaksiGaji['id_transaksi_gaji'],
                            'id_tunjangan_detail' => $detail['id_tunjangan_detail'],
                            'nama_tunjangan' => $detail['id_tunjangan'],
                            'jumlah' => $detail['jumlah'],
                        ];
                    }


                    $columnsTunjangan = ['id_transaksi_gaji', 'id_tunjangan_detail', 'nama_tunjangan', 'jumlah'];
                    $insertedTunjangan = Yii::$app->db->createCommand()
                        ->batchInsert('gaji_tunjangan', $columnsTunjangan, $batchDataTunjangan)
                        ->execute();

                    $batchDataPotongan = [];
                    foreach ($potonganDetail as  $detail) {
                        $batchDataPotongan[] = [
                            'id_transaksi_gaji' => $getIdTransaksiGaji['id_transaksi_gaji'],
                            'id_potongan_detail' => $detail['id_potongan_detail'],
                            'nama_potongan' => $detail['id_potongan'],
                            'jumlah' => $detail['jumlah'],
                        ];
                    }

                    $columnsPotongan = ['id_transaksi_gaji', 'id_potongan_detail', 'nama_potongan', 'jumlah'];
                    $insertedPotongan = Yii::$app->db->createCommand()
                        ->batchInsert('gaji_potongan', $columnsPotongan, $batchDataPotongan)
                        ->execute();



                    if ($insertedTunjangan && $insertedPotongan) {
                        Yii::$app->session->setFlash('success', "Data tunjangan berhasil tersimpan.");
                    } else {
                        Yii::$app->session->setFlash('error', "Data tunjangan gagal tersimpan.");
                    }

                    return $this->redirect(['detail', 'id_transaksi_gaji' => $getIdTransaksiGaji['id_transaksi_gaji']]);
                } else {
                    // Jika gagal menyimpan, kembali ke form dengan pesan error
                    Yii::$app->session->setFlash('error', 'Gagal menyimpan data. Silakan cek kembali input Anda.');
                }
            }
        } else {
            $model->loadDefaultValues();
        }




        return $this->render('update', [
            'model' => $model,
            'rekapandata' => $rekapandata,
        ]);
    }

    /**
     * Deletes an existing TransaksiGaji model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_transaksi_gaji Id Transaksi Gaji
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_transaksi_gaji)
    {
        $model = $this->findModel($id_transaksi_gaji);
        // $karyawan = Karyawan::find()->where(['kode_karyawan' => $model->kode_karyawan])->asArray()->one();
        if ($model->delete()) {
            $gajiTunjangan = GajiTunjangan::find()->where(['id_transaksi_gaji' => $id_transaksi_gaji])->all();
            $gajiPotongan = GajiPotongan::find()->where(['id_transaksi_gaji' => $id_transaksi_gaji])->all();


            // Hapus semua data yang ditemukan
            foreach ($gajiTunjangan as $item) {
                $item->delete();
            }

            foreach ($gajiPotongan as $item) {
                $item->delete();
            }

            Yii::$app->session->setFlash('success', 'Data Berhasilsil Dihapus');
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Data Gagal Dihapus');
        }
    }

    /**
     * Finds the TransaksiGaji model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_transaksi_gaji Id Transaksi Gaji
     * @return TransaksiGaji the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_transaksi_gaji)
    {
        if (($model = TransaksiGaji::findOne(['id_transaksi_gaji' => $id_transaksi_gaji])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
