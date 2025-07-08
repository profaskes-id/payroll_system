<?php

namespace backend\controllers;

use backend\models\RiwayatPelatihan;
use backend\models\RiwayatPelatihanSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * RiwayatPelatihanController implements the CRUD actions for RiwayatPelatihan model.
 */
class RiwayatPelatihanController extends Controller
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
     * Lists all RiwayatPelatihan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RiwayatPelatihanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RiwayatPelatihan model.
     * @param int $id_riwayat_pelatihan Id Riwayat Pelatihan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_riwayat_pelatihan)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_riwayat_pelatihan),
        ]);
    }

    /**
     * Creates a new RiwayatPelatihan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new RiwayatPelatihan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $lampiranSertifikat = UploadedFile::getInstance($model, 'sertifikat');
                $lampiranSertifikat != null ? $this->saveImage($model, $lampiranSertifikat, 'sertifikat') : $model->sertifikat = null;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Menambahkan Data Riwayat Pealatihan');
                    return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
                }
                Yii::$app->session->setFlash('error', 'Gagal Menambahkan Data Riwayat Pealatihan');
                return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RiwayatPelatihan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_riwayat_pelatihan Id Riwayat Pelatihan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_riwayat_pelatihan)
    {
        $model = $this->findModel($id_riwayat_pelatihan);
        $oldPost = $model->oldAttributes;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $lampiranFilesuratSertifikat = UploadedFile::getInstance($model, 'sertifikat');

            $data = [
                'sertifikat' => $lampiranFilesuratSertifikat,
            ];

            foreach ($data as $key => $value) {
                if ($value != null) {
                    $this->saveImage($model, $value, $key);
                    if ($oldPost[$key]) {
                        $this->deleteImage($oldPost[$key]);
                    }
                } else {
                    $model->$key = $oldPost[$key];
                }
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Berhasil Melakukan Upadte Data Riwayat Pelatihan');
                return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
            }
            Yii::$app->session->setFlash('error', 'Gagal Melakukan Upadte Data Riwayat Pelatihan');
            return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
            return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RiwayatPelatihan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_riwayat_pelatihan Id Riwayat Pelatihan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_riwayat_pelatihan)
    {
        $model = $this->findModel($id_riwayat_pelatihan);
        if ($this->deleteImage($model->sertifikat)) {
            if ($model->delete()) {
                Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data Riwayat Pelatihan');
                return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
            }
        }
        Yii::$app->session->setFlash('error', 'Gagal Menghapus Data Riwayat Pelatihan');
        return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
    }

    /**
     * Finds the RiwayatPelatihan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_riwayat_pelatihan Id Riwayat Pelatihan
     * @return RiwayatPelatihan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_riwayat_pelatihan)
    {
        if (($model = RiwayatPelatihan::findOne(['id_riwayat_pelatihan' => $id_riwayat_pelatihan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function saveImage($model, $uploadedFile, $type)
    {
        if ($type !== 'sertifikat' && $type !== 0) {
            return false;
        }
        $uploadsDir = Yii::getAlias('@webroot/uploads/sertifikat/');

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
