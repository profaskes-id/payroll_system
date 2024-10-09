<?php

namespace backend\controllers;

use backend\models\Cetak;
use backend\models\CetakSearch;
use backend\models\DataPekerjaan;
use backend\models\Karyawan;
use backend\models\Perusahaan;
use kartik\mpdf\Pdf;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CetakController implements the CRUD actions for Cetak model.
 */
class CetakController extends Controller
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
     * Lists all Cetak models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CetakSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cetak model.
     * @param int $id_cetak Id Cetak
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_cetak)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_cetak),
        ]);
    }

    /**
     * Creates a new Cetak model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Cetak();

        $get = Yii::$app->request->get();

        $karyawan = Karyawan::find()->select(['id_karyawan', 'tempat_lahir', 'tanggal_lahir', 'agama', 'nomer_identitas', 'jenis_identitas',  'kode_jenis_kelamin', 'nama', 'status_nikah', 'is_current_domisili', 'kode_provinsi_domisili', 'kode_kabupaten_kota_domisili', 'kode_kecamatan_domisili', 'desa_lurah_domisili', 'desa_lurah_domisili', 'alamat_domisili', 'kode_provinsi_identitas', 'kode_kabupaten_kota_identitas', 'kode_kecamatan_identitas', 'desa_lurah_identitas', 'desa_lurah_identitas', 'alamat_identitas'])->where(['id_karyawan' => $get['id_karyawan']])->one();
        $pekerjaan = DataPekerjaan::find()->where(['id_data_pekerjaan' => $get['id_data_pekerjaan']])->one();
        $perusahaan = Perusahaan::find()->where(['id_perusahaan' => $pekerjaan->bagian->id_perusahaan])->one();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_cetak' => $model->id_cetak]);
            }
        } else {
            $model->loadDefaultValues();
        }


        return $this->render('create', [
            'karyawan' => $karyawan,
            'pekerjaan' => $pekerjaan,
            'model' => $model,
            'perusahaan' => $perusahaan
        ]);
    }



    public function actionKontrakDownload()
    {
        $params = Yii::$app->request->get();
        $model = $this->findModel($params['id_cetak']);
        if ($model['id_karyawan'] == null || $model['id_data_pekerjaan'] == null) {
            Yii::$app->session->setFlash('error', 'Data Tidak Valid, pastikan data karyawan dan data pekerjaan valid dan sudah terisi semua');
            return $this->redirect(['karyawan/view', 'id_karyawan' => $model['id_karyawan']]);
        }


        $karyawan = Karyawan::findOne($model['id_karyawan']);
        $dataPekerjaan = DataPekerjaan::find()->where(['id_data_pekerjaan' => $model['id_data_pekerjaan'], 'id_karyawan' => $model['id_karyawan']])->one();
        $bagian = $dataPekerjaan->bagian;
        $perusahaan = $bagian->perusahaan;
        $perusahaan = Perusahaan::find()->where(['id_perusahaan' => $dataPekerjaan->bagian->id_perusahaan])->asArray()->one();

        $content = $this->renderPartial('_cetak', [
            'model' => $model,
            'karyawan' => $karyawan,
            'dataPekerjaan' => $dataPekerjaan,
            'perusahaan' => $perusahaan,
            'bagian' => $bagian
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:15px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Report Rekap Absensi ' . date('F')],
            'methods' => [
                // 'SetHeader' => ['Data Rekap Absensi ' . date('F')],
                // 'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        return $pdf->render();
    }

    /**
     * Updates an existing Cetak model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_cetak Id Cetak
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpload($id_cetak)
    {
        $model = $this->findModel($id_cetak);
        // dd($model);
        $oldPost = $model->oldAttributes;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $lampiranSurat_kontrak = UploadedFile::getInstance($model, 'surat_upload');


            $data = [
                'surat_upload' => $lampiranSurat_kontrak,
            ];

            foreach ($data as $key => $value) {
                if ($value != null) {
                    $this->saveImage($model, $value, $key);
                    // Hapus gambar lama jika ada
                    if ($oldPost[$key]) {
                        $this->deleteImage($oldPost[$key]);
                    }
                } else {
                    $model->$key = $oldPost[$key];
                }
            }
            $model->status = 1;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Berhasil Melakukan Upload Surat Kontrak');
                return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
            }
        }

        return $this->render('upload', [
            'model' => $model,
        ]);
    }
    public function actionUpdate($id_cetak)
    {
        $model = $this->findModel($id_cetak);

        $karyawan = Karyawan::find()->select(['id_karyawan', 'tempat_lahir', 'tanggal_lahir', 'agama', 'nomer_identitas', 'jenis_identitas',  'kode_jenis_kelamin', 'nama', 'status_nikah', 'is_current_domisili', 'kode_provinsi_domisili', 'kode_kabupaten_kota_domisili', 'kode_kecamatan_domisili', 'desa_lurah_domisili', 'desa_lurah_domisili', 'alamat_domisili', 'kode_provinsi_identitas', 'kode_kabupaten_kota_identitas', 'kode_kecamatan_identitas', 'desa_lurah_identitas', 'desa_lurah_identitas', 'alamat_identitas'])->where(['id_karyawan' => $model['id_karyawan']])->one();
        $pekerjaan = DataPekerjaan::find()->where(['id_data_pekerjaan' => $model['id_data_pekerjaan']])->one();
        $perusahaan = Perusahaan::find()->where(['id_perusahaan' => $pekerjaan->bagian->id_perusahaan])->one();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_cetak' => $model->id_cetak]);
        };
        return $this->render('update', [
            'karyawan' => $karyawan,
            'pekerjaan' => $pekerjaan,
            'model' => $model,
            'perusahaan' => $perusahaan,
        ]);
    }

    /**
     * Deletes an existing Cetak model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_cetak Id Cetak
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_cetak)
    {
        $model = $this->findModel($id_cetak);
        $model->delete();
        return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
    }

    /**
     * Finds the Cetak model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_cetak Id Cetak
     * @return Cetak the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_cetak)
    {
        if (($model = Cetak::findOne(['id_cetak' => $id_cetak])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function saveImage($model, $uploadedFile, $type)
    {
        // if ($type !== 'surat_upload' && $type !== 0) {
        //     return false;
        // }
        $uploadsDir = Yii::getAlias('@webroot/uploads/surat_upload/');

        if (!$uploadedFile) {
            return false;
        }

        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $fileName = $uploadsDir . '/' . uniqid() . '.' . $uploadedFile->extension;

        if ($uploadedFile->saveAs($fileName)) {
            $model->{$type} = 'uploads/' . $type . '/' . basename($fileName);
            return true;
        } else {
            Yii::$app->session->setFlash('error', 'Failed to save the uploaded file.');
            return false;
        }
    }

    public function deleteImage($oldThumbnail)
    {
        $filePath = Yii::getAlias('@webroot') . '/' . $oldThumbnail;
        if ($oldThumbnail && file_exists($filePath)) {
            unlink($filePath);
            return true;
        } else {
            return true;
        }
    }
}
