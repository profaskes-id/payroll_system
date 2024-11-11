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
                'access' => [
                    'class' => \yii\filters\AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
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
        $tanggalSet = date('Y-m-d');
        $searchModel = new KaryawanSearch();
        $dataProvider = $searchModel->searchAbsensi(Yii::$app->request->queryParams, $tanggalSet);
        $absensi = new Absensi();
        $bagian = new Bagian();

        if (\Yii::$app->request->isPost) {
            // echo "kode jika request adalah POST";
            $param_bagian = Yii::$app->request->post('Bagian')['id_bagian'];
            $param_tanggal = Yii::$app->request->post('Absensi')['tanggal'];
            // dd($param_tanggal);

            if ($param_tanggal) {
                $tanggalSet = $param_tanggal;
                $dataProvider = $searchModel->searchAbsensi(Yii::$app->request->queryParams, $tanggalSet);
            }
            if ($param_bagian) {
                $filteredModels = [];
                foreach ($dataProvider->models as $model) {
                    if (isset($model['data_pekerjaan']) && $model['data_pekerjaan']['id_bagian'] == intval($param_bagian)) {
                        $filteredModels[] = $model;
                    }
                }
                $dataProvider->setModels($filteredModels);
            }
        }

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
                $model->save();
                return $this->redirect(['view', 'id_absensi' => $model->id_absensi]);
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

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
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
