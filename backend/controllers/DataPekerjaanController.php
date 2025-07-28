<?php

namespace backend\controllers;

use backend\models\Cetak;
use backend\models\DataPekerjaan;
use backend\models\DataPekerjaanSearch;
use backend\models\Karyawan;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DataPekerjaanController implements the CRUD actions for DataPekerjaan model.
 */
class DataPekerjaanController extends Controller
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
     * Lists all DataPekerjaan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DataPekerjaanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DataPekerjaan model.
     * @param int $id_data_pekerjaan Id Data Pekerjaan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_data_pekerjaan)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_data_pekerjaan),
        ]);
    }

    /**
     * Creates a new DataPekerjaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id_karyawan)
    {
        $model = new DataPekerjaan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $lampiranFilesuratLamaranPekerjaan = UploadedFile::getInstance($model, 'surat_lamaran_pekerjaan');
                $lampiranFilesuratLamaranPekerjaan != null ? $this->saveImage($model, $lampiranFilesuratLamaranPekerjaan, 'surat_lamaran_pekerjaan') : $model->surat_lamaran_pekerjaan = null;

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Menambahkan Data Pekerjaan');
                    return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
                }
                Yii::$app->session->setFlash('error', 'Gagal Menambahkan Data Pekerjaan');
                return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $karyawan_nama = Karyawan::find()->select('nama')->asArray()->where(['id_karyawan' => $id_karyawan])->one();

        return $this->render('create', [
            'model' => $model,
            'karyawan_nama' => $karyawan_nama
        ]);
    }

    /**
     * Updates an existing DataPekerjaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_data_pekerjaan Id Data Pekerjaan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_data_pekerjaan)
    {
        $model = $this->findModel($id_data_pekerjaan);

        $oldPost = $model->oldAttributes;
        if ($this->request->isPost && $model->load($this->request->post())) {
            $lampiranFilesuratLamaranPekerjaan = UploadedFile::getInstance($model, 'surat_lamaran_pekerjaan');


            $data = [
                'surat_lamaran_pekerjaan' => $lampiranFilesuratLamaranPekerjaan,
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


            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Berhasil Melakukan Upadte Data Pekerjaan');
                return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
            }
            Yii::$app->session->setFlash('error', 'Gagal Melakukan Upadte Data Pekerjaan');
            return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DataPekerjaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_data_pekerjaan Id Data Pekerjaan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_data_pekerjaan)
    {
        $model = $this->findModel($id_data_pekerjaan);
        $cetakData = Cetak::find()->where(['id_data_pekerjaan' => $model->id_data_pekerjaan])->one();

        if ($cetakData && $cetakData->surat_upload) {
            $this->deleteImage($cetakData->surat_upload);
        }

        if ($this->deleteImage($model->surat_lamaran_pekerjaan)) {
            if ($model->delete()) {
                Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data Pekerjaan');
                return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
            }
        }
        Yii::$app->session->setFlash('error', 'Gagal Menghapus Data Pekerjaan');
        return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
    }

    /**
     * Finds the DataPekerjaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_data_pekerjaan Id Data Pekerjaan
     * @return DataPekerjaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_data_pekerjaan)
    {
        if (($model = DataPekerjaan::findOne(['id_data_pekerjaan' => $id_data_pekerjaan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function saveImage($model, $uploadedFile, $type)
    {
        if ($type !== 'surat_lamaran_pekerjaan' && $type !== 0) {
            return false;
        }
        $uploadsDir = Yii::getAlias('@webroot/uploads/surat_lamaran_pekerjaan/');

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
