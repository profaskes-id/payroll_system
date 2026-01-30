<?php

namespace backend\controllers;

use backend\models\Absensi;
use backend\models\AtasanKaryawan;
use backend\models\Bagian;
use backend\models\JamKerjaKaryawan;
use backend\models\KaryawanSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AbsensiController implements the CRUD actions for Absensi model.
 */
class AbsensiController extends Controller
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

    /**
     * Lists all Absensi models.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Default date if none is provided
        $tanggalSet = date('Y-m-d');
        $searchModel = new KaryawanSearch();

        // Get the query parameters (this will include data sent via GET)
        $queryParams = Yii::$app->request->get();

        // Get the data provider using the default or GET parameters
        $dataProvider = $searchModel->searchAbsensi($queryParams, $tanggalSet);


        // Create new instances of Absensi and Bagian
        $absensi = new Absensi();
        $bagian = new Bagian();

        // Check if there are GET parameters (this simulates form submission with GET)
        $param_bagian = isset($queryParams['Bagian']['id_bagian']) ? $queryParams['Bagian']['id_bagian'] : null;
        $param_tanggal = isset($queryParams['Absensi']['tanggal']) ? $queryParams['Absensi']['tanggal'] : null;

        if ($param_tanggal) {
            // Update the $tanggalSet if a date is provided
            $tanggalSet = $param_tanggal;
            // Refresh the dataProvider with the updated date
            $dataProvider = $searchModel->searchAbsensi($queryParams, $tanggalSet);
        }

        if ($param_bagian) {
            // Filter the dataProvider models based on the 'id_bagian' value
            $filteredModels = [];
            foreach ($dataProvider->models as $model) {
                if (isset($model['data_pekerjaan']) && $model['data_pekerjaan']['id_bagian'] == intval($param_bagian)) {
                    $filteredModels[] = $model;
                }
            }
            // Set the filtered models to the dataProvider
            $dataProvider->setModels($filteredModels);
        }

        // Render the view with all the necessary data
        return $this->render('index', [
            'absensi' => $absensi,
            'bagian' => $bagian,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tanggalSet' => $tanggalSet
        ]);
    }



    /**
     * Displays a single Absensi model.
     * @param int $id_absensi Id Absensi
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_absensi)
    {
        $model = $this->findModel($id_absensi);
        $atasanKaryawan = AtasanKaryawan::find()->where(['id_karyawan' => $model['id_karyawan']])->one();
        if ($atasanKaryawan == null) {
            Yii::$app->session->setFlash('error', 'Mohon Untuk Menambahkan Data Atasan Karyawan dan Penempatan Terlebih Dahulu');
            return $this->redirect(['index']);
        }
        $alamat = $atasanKaryawan->masterLokasi;

        return $this->render('view', [
            'model' => $model,
            'alamat' => $alamat
        ]);
    }

    /**
     * Creates a new Absensi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Absensi();
        $karyawan = Yii::$app->request->get('id_karyawan');
        $jadwalKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $karyawan])->one();
        $atasanKaryawan = AtasanKaryawan::find()->where(['id_karyawan' => $karyawan])->one();
        if (!$jadwalKerjaKaryawan || !$atasanKaryawan) {

            Yii::$app->session->setFlash('error', 'Mohon Untuk Menambahkan Data Jadwal Kerja Terlebih Dahulu');
            return $this->redirect(['index']);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->keterangan  = $model->keterangan ?? '-';

                $model->created_at = date('Y-m-d H:i:s');
                $model->created_by = Yii::$app->user->identity->id;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Menambahkan Data Absensi');
                    return $this->redirect(['view', 'id_absensi' => $model->id_absensi]);
                } else {
                    //flash error
                    Yii::$app->session->setFlash('error', 'Gagal Menambahkan Data Absensi');
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
     * Updates an existing Absensi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_absensi Id Absensi
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_absensi)
    {
        $model = $this->findModel($id_absensi);

        if ($this->request->isPost && $model->load($this->request->post())) {

            $model->updated_at = date('Y-m-d H:i:s');
            $model->updated_by = Yii::$app->user->identity->id;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Berhasil Memperbaraui Data Absensi');
                return $this->redirect(['view', 'id_absensi' => $model->id_absensi]);
            } else {
                //flash error
                Yii::$app->session->setFlash('error', 'Gagal Memperbaraui Data Absensi');
            }

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Absensi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_absensi Id Absensi
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_absensi)
    {
        $this->findModel($id_absensi)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Absensi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_absensi Id Absensi
     * @return Absensi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_absensi)
    {
        if (($model = Absensi::findOne(['id_absensi' => $id_absensi])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
