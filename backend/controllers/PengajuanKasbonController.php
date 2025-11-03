<?php

namespace backend\controllers;

use backend\models\PembayaranKasbon;
use backend\models\PengajuanKasbon;
use backend\models\PengajuanKasbonSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PengajuanKasbonController implements the CRUD actions for PengajuanKasbon model.
 */
class PengajuanKasbonController extends Controller
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
            ]
        );
    }

    /**
     * Lists all PengajuanKasbon models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PengajuanKasbonSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PengajuanKasbon model.
     * @param int $id_pengajuan_kasbon Id Pengajuan Kasbon
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_pengajuan_kasbon)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_pengajuan_kasbon),
        ]);
    }

    /**
     * Creates a new PengajuanKasbon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanKasbon();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                // 🔹 Ambil gaji pokok dari master_gaji
                $gaji = \backend\models\MasterGaji::find()
                    ->where(['id_karyawan' => $model->id_karyawan])
                    ->orderBy(['id_gaji' => SORT_DESC])
                    ->one();
                $model->gaji_pokok = $gaji ? $gaji->nominal_gaji : 0;

                // 🔹 Jika status == 1, isi tanggal_disetujui dan disetujui_oleh
                if ($model->status == 1) {
                    $model->tanggal_disetujui = date('Y-m-d');
                    $model->disetujui_oleh = Yii::$app->user->id; // id user yang menyetujui
                } else {
                    $model->tanggal_disetujui = null;
                    $model->disetujui_oleh = 0;
                }

                // 🔹 Set audit fields
                $model->tanggal_pengajuan = date('Y-m-d');
                $model->created_at = time();
                $model->created_by = Yii::$app->user->id;
                $model->updated_at = time();
                $model->updated_by = Yii::$app->user->id;


                if ($model->save()) {

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

                    //     // Jika data lama ditemukan
                    //     if ($dataOld) {
                    //         $dataOld->id_kasbon = $idKasbonBaru;
                    //         $dataOld->jumlah_kasbon =  $model->jumlah_kasbon;
                    //         $dataOld->jumlah_potong = 0;
                    //         $dataOld->tanggal_potong = $model->tanggal_mulai_potong;
                    //         $dataOld->angsuran = $model->angsuran_perbulan;
                    //         $dataOld->status_potongan = 0;
                    //         $dataOld->autodebt = $model->tipe_potongan;
                    //         $dataOld->sisa_kasbon += $model->jumlah_kasbon; // Tambahkan ke sisa lama
                    //         $dataOld->created_at = time();

                    //         if ($dataOld->save(false)) {
                    //             Yii::$app->session->setFlash('success', 'Data kasbon lama berhasil diperbarui (sisa kasbon ditambahkan).');
                    //         } else {
                    //             Yii::$app->session->setFlash('error', 'Gagal memperbarui data kasbon lama.');
                    //         }
                    //     }
                    //     // Jika tidak ada data lama → buat baru
                    //     else {
                    //         $pembayaran = new \backend\models\PembayaranKasbon();
                    //         $pembayaran->id_karyawan = $model->id_karyawan;
                    //         $pembayaran->id_kasbon = $idKasbonBaru;
                    //         $pembayaran->jumlah_kasbon =  $model->jumlah_kasbon;

                    //         $pembayaran->jumlah_potong = 0;
                    //         $pembayaran->tanggal_potong = $model->tanggal_mulai_potong;
                    //         $pembayaran->angsuran = $model->angsuran_perbulan;
                    //         $pembayaran->status_potongan = 0;
                    //         $pembayaran->autodebt = $model->tipe_potongan;
                    //         $pembayaran->sisa_kasbon = $model->jumlah_kasbon;
                    //         $pembayaran->created_at = time();

                    //         if ($pembayaran->save(false)) {
                    //             Yii::$app->session->setFlash('success', 'Data kasbon baru berhasil ditambahkan.');
                    //         } else {
                    //             Yii::$app->session->setFlash('error', 'Gagal menambahkan data kasbon baru.');
                    //         }
                    //     }



                    //     return $this->redirect(['view', 'id_pengajuan_kasbon' => $model->id_pengajuan_kasbon]);
                    // }
                    Yii::$app->session->setFlash('success', 'Pengajuan Kasbon berhasil disimpan.');
                    return $this->redirect(['view', 'id_pengajuan_kasbon' => $model->id_pengajuan_kasbon]);
                } else {

                    $errors = $model->getErrors();

                    // Buat string informatif
                    $errorMessages = [];
                    foreach ($errors as $attribute => $messages) {
                        $label = $model->getAttributeLabel($attribute);
                        $errorMessages[] = "<b>$label:</b> " . implode(', ', $messages);
                    }
                    $errorText = implode('<br>', $errorMessages);

                    Yii::$app->session->setFlash('error', "Pengajuan Kasbon gagal disimpan:<br>$errorText");
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
     * Updates an existing PengajuanKasbon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_pengajuan_kasbon Id Pengajuan Kasbon
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_pengajuan_kasbon)
    {
        $model = $this->findModel($id_pengajuan_kasbon);

        if ($this->request->isPost && $model->load($this->request->post())) {

            if ($model->save()) {

                if ($model->status == 1) {
                    $idKasbonBaru = $model->id_pengajuan_kasbon;
                    $pembayaran = new \backend\models\PembayaranKasbon();
                    $dataOld = \backend\models\PembayaranKasbon::find()
                        ->where([
                            'id_karyawan' => $model->id_karyawan,
                            'status_potongan' => 0,
                            'autodebt' => $model->tipe_potongan
                        ])
                        ->orderBy(['created_at' => SORT_DESC])
                        ->one();




                    if ($dataOld) {

                        if ($dataOld['id_kasbon'] == $model['id_pengajuan_kasbon']) {
                            $dataOld->bulan = date('m');
                            $dataOld->tahun = date('Y');
                            $dataOld->jumlah_potong = 0;
                            $dataOld->jumlah_kasbon =  $model->jumlah_kasbon;
                            $dataOld->tanggal_potong = $model->tanggal_mulai_potong;
                            $dataOld->angsuran = $model->angsuran_perbulan;
                            $dataOld->status_potongan = 0;
                            $dataOld->autodebt = $model->tipe_potongan;
                            $dataOld->sisa_kasbon = $model->jumlah_kasbon; // Tambahkan ke sisa lama

                            $dataOld->created_at = time();
                            $dataOld->deskripsi = 'Top-up Kasbon';

                            if ($dataOld->save(false)) {
                                Yii::$app->session->setFlash('success', 'Data kasbon lama berhasil diperbarui .');
                            } else {
                                Yii::$app->session->setFlash('error', 'Gagal memperbarui data kasbon lama.');
                            }
                        } else {

                            $pembayaran->id_kasbon = $idKasbonBaru;
                            $pembayaran->id_karyawan = $model->id_karyawan;
                            $pembayaran->bulan = date('m');
                            $pembayaran->tahun = date('Y');
                            $pembayaran->jumlah_potong = 0;
                            $pembayaran->jumlah_kasbon = $dataOld->jumlah_kasbon +  $model->jumlah_kasbon;
                            $pembayaran->tanggal_potong = $model->tanggal_mulai_potong;
                            $pembayaran->angsuran = $model->angsuran_perbulan;
                            $pembayaran->status_potongan = 0;
                            $pembayaran->autodebt = $model->tipe_potongan;
                            $pembayaran->sisa_kasbon = $dataOld->sisa_kasbon + $model->jumlah_kasbon; // Tambahkan ke sisa lama

                            $pembayaran->created_at = time();
                            $pembayaran->deskripsi = 'Top-up Kasbon';
                            if ($pembayaran->save(false)) {
                                Yii::$app->session->setFlash('success', 'Data kasbon lama berhasil diperbarui (sisa kasbon ditambahkan).');
                            } else {
                                Yii::$app->session->setFlash('error', 'Gagal memperbarui data kasbon lama.');
                            }
                        }
                    }
                    // Jika tidak ada data lama → buat baru
                    else {

                        $pembayaran = new PembayaranKasbon();
                        $pembayaran->id_karyawan = (int) $model['id_karyawan'];
                        $pembayaran->id_kasbon = $idKasbonBaru;
                        $pembayaran->jumlah_potong = 0;
                        $pembayaran->jumlah_kasbon =  $model->jumlah_kasbon;
                        $pembayaran->bulan = date('m');
                        $pembayaran->tahun = date('Y');
                        $pembayaran->tanggal_potong = $model->tanggal_mulai_potong;
                        $pembayaran->angsuran = $model->angsuran_perbulan;
                        $pembayaran->status_potongan = 0;
                        $pembayaran->autodebt = $model->tipe_potongan;
                        $pembayaran->sisa_kasbon = $model->jumlah_kasbon;
                        $pembayaran->created_at = time();
                        $pembayaran->deskripsi = 'Top-up Kasbon';



                        if ($pembayaran->save(false)) {
                            Yii::$app->session->setFlash('success', 'Data kasbon baru berhasil ditambahkan.');
                        } else {
                            Yii::$app->session->setFlash('error', 'Gagal menambahkan data kasbon baru.');
                        }
                    }





                    return $this->redirect(['view', 'id_pengajuan_kasbon' => $model->id_pengajuan_kasbon]);
                }
                Yii::$app->session->setFlash('success', 'Pengajuan Kasbon berhasil disimpan.');
                return $this->redirect(['view', 'id_pengajuan_kasbon' => $model->id_pengajuan_kasbon]);
            } else {

                $errors = $model->getErrors();

                // Buat string informatif
                $errorMessages = [];
                foreach ($errors as $attribute => $messages) {
                    $label = $model->getAttributeLabel($attribute);
                    $errorMessages[] = "<b>$label:</b> " . implode(', ', $messages);
                }
                $errorText = implode('<br>', $errorMessages);

                Yii::$app->session->setFlash('error', "Pengajuan Kasbon gagal disimpan:<br>$errorText");
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PengajuanKasbon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pengajuan_kasbon Id Pengajuan Kasbon
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_pengajuan_kasbon)
    {
        $this->findModel($id_pengajuan_kasbon)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PengajuanKasbon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pengajuan_kasbon Id Pengajuan Kasbon
     * @return PengajuanKasbon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pengajuan_kasbon)
    {
        if (($model = PengajuanKasbon::findOne(['id_pengajuan_kasbon' => $id_pengajuan_kasbon])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
