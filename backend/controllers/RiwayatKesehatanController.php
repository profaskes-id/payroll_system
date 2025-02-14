<?php

namespace backend\controllers;

use backend\models\RiwayatKesehatan;
use backend\models\RiwayatKesehatanSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * RiwayatKesehatanController implements the CRUD actions for RiwayatKesehatan model.
 */
class RiwayatKesehatanController extends Controller
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
     * Lists all RiwayatKesehatan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RiwayatKesehatanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RiwayatKesehatan model.
     * @param int $id_riwayat_kesehatan Id Riwayat Kesehatan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_riwayat_kesehatan)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_riwayat_kesehatan),
        ]);
    }

    /**
     * Creates a new RiwayatKesehatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new RiwayatKesehatan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $lampiranFilesuratDokter = UploadedFile::getInstance($model, 'surat_dokter');
                $lampiranFilesuratDokter != null ? $this->saveImage($model, $lampiranFilesuratDokter, 'surat_dokter') : $model->surat_dokter = null;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Menambahkan Data Riwayat Kesehatan');
                    return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
                }
                Yii::$app->session->setFlash('error', 'Gagal Menambahkan Data Riwayat Kesehatan');
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
     * Updates an existing RiwayatKesehatan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_riwayat_kesehatan Id Riwayat Kesehatan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_riwayat_kesehatan)
    {
        $model = $this->findModel($id_riwayat_kesehatan);

        $oldPost = $model->oldAttributes;
        if ($this->request->isPost && $model->load($this->request->post())) {
            $lampiranFilesuratDokter = UploadedFile::getInstance($model, 'surat_dokter');


            $data = [
                'surat_dokter' => $lampiranFilesuratDokter,
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
                Yii::$app->session->setFlash('success', 'Berhasil Melakukan Upadte Data Riwayat Kesehatan');
                return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
            }
            Yii::$app->session->setFlash('error', 'Gagal Melakukan Upadte Data Riwayat Kesehatan');
            return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RiwayatKesehatan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_riwayat_kesehatan Id Riwayat Kesehatan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_riwayat_kesehatan)
    {
        $model = $this->findModel($id_riwayat_kesehatan);
        if ($this->deleteImage($model->surat_dokter)) {
            if ($model->delete()) {
                Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data Pekerjaan');
                return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
            }
        }
        Yii::$app->session->setFlash('error', 'Gagal Menghapus Data Pekerjaan');
        return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
    }

    /**
     * Finds the RiwayatKesehatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_riwayat_kesehatan Id Riwayat Kesehatan
     * @return RiwayatKesehatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_riwayat_kesehatan)
    {
        if (($model = RiwayatKesehatan::findOne(['id_riwayat_kesehatan' => $id_riwayat_kesehatan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    public function saveImage($model, $uploadedFile, $type)
    {
        if ($type !== 'surat_dokter' && $type !== 0) {
            return false;
        }
        $uploadsDir = Yii::getAlias('@webroot/uploads/surat_dokter/');

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
