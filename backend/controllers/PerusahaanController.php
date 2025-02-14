<?php

namespace backend\controllers;

use backend\models\BagianSearch;
use backend\models\MasterKab;
use backend\models\MasterProp;
use backend\models\Perusahaan;
use backend\models\PerusahaanSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

use function PHPSTORM_META\type;

/**
 * PerusahaanController implements the CRUD actions for Perusahaan model.
 */
class PerusahaanController extends Controller
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
     * Lists all Perusahaan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PerusahaanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Perusahaan model.
     * @param int $id_perusahaan Id Perusahaan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_perusahaan)
    {

        $perusahaanSearch = new BagianSearch();
        $perusahaanSearch->id_perusahaan = $id_perusahaan;
        $perusahaanProvider = $perusahaanSearch->search($this->request->queryParams);



        return $this->render('view', [
            'perusahaanSearch' => $perusahaanSearch,
            'perusahaanProvider' => $perusahaanProvider,
            'model' => $this->findModel($id_perusahaan),
        ]);
    }

    /**
     * Creates a new Perusahaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Perusahaan();

        if ($this->request->isPost) {
            $lampiranFile = UploadedFile::getInstance($model, 'logo');
            if ($model->load($this->request->post())) {
                $this->saveImage($model, $lampiranFile, 'logo');

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Menambahkan Data Perusahaan');
                    return $this->redirect(['view', 'id_perusahaan' => $model->id_perusahaan]);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal Menambahkan Data Perusahaan');
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
     * Updates an existing Perusahaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_perusahaan Id Perusahaan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_perusahaan)
    {
        $model = $this->findModel($id_perusahaan);

        $oldPost = $model->attributes;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $lampiranFileLogo = UploadedFile::getInstance($model, 'logo');
            $data = [
                'logo' => $lampiranFileLogo,
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
                Yii::$app->session->setFlash('success', 'Berhasil Mengupdate Data Perusahaan');
                return $this->redirect(['view', 'id_perusahaan' => $model->id_perusahaan]);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal Mengupdate Data Perusahaan');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Perusahaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_perusahaan Id Perusahaan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_perusahaan)
    {
        $model = $this->findModel($id_perusahaan);

        $this->deleteImage($model->logo);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data Perusahaan');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal Menghapus Data Perusahaan');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Perusahaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_perusahaan Id Perusahaan
     * @return Perusahaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_perusahaan)
    {
        if (($model = Perusahaan::findOne(['id_perusahaan' => $id_perusahaan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionKabupaten($id_prop)
    {
        $kabupaten = MasterKab::find()
            ->where(['kode_prop' => $id_prop])
            ->all();
        $dataKabupaten = \yii\helpers\ArrayHelper::map($kabupaten, 'kode_kab', 'nama_kab');
        return $this->asJson($dataKabupaten);
    }

    public function saveImage($model, $uploadedFile, $type)
    {
        // if ($type !== 'surat_upload' && $type !== 0) {
        //     return false;
        // }
        $uploadsDir = Yii::getAlias('@webroot/uploads/logo/');

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
