<?php

namespace backend\controllers;

use backend\models\PeriodeGaji;
use backend\models\PeriodeGajiSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PeriodeGajiController implements the CRUD actions for PeriodeGaji model.
 */
class PeriodeGajiController extends Controller
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
     * Lists all PeriodeGaji models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PeriodeGajiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $periodeGaji = new PeriodeGaji();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'periodeGaji' => $periodeGaji
        ]);
    }

    /**
     * Displays a single PeriodeGaji model.
     * @param int $bulan Bulan
     * @param int $tahun Tahun
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($bulan, $tahun)
    {
        return $this->render('view', [
            'model' => $this->findModel($bulan, $tahun),
        ]);
    }

    /**
     * Creates a new PeriodeGaji model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PeriodeGaji();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Contoh penggunaan
                $tahun = $model->tahun;
                $tanggal_set = $model->tanggal_set;


                $dateRanges = $model->generateDateRanges($tahun, $tanggal_set);
                // Batch Insert
                $rows = [];
                foreach ($dateRanges as $range) {
                    $rows[] = [
                        'tahun' => $tahun,
                        'bulan' => $range['bulan'],
                        'tanggal_awal' => $range['tanggal_awal'],
                        'tanggal_akhir' => $range['tanggal_akhir'],
                        'terima' => $range['tanggal_terima'],
                        // Tambahkan field lain yang diperlukan
                    ];
                }
                // Gunakan batch insert
                $result = Yii::$app->db->createCommand()
                    ->batchInsert(
                        'periode_gaji',
                        ['tahun', 'bulan', 'tanggal_awal', 'tanggal_akhir', 'terima'],
                        $rows
                    )
                    ->execute();

                if ($result) {
                    Yii::$app->session->setFlash('success', 'Data berhasil disimpan');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Data gagal disimpan');
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
     * Updates an existing PeriodeGaji model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $bulan Bulan
     * @param int $tahun Tahun
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($bulan, $tahun)
    {
        $model = $this->findModel($bulan, $tahun);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil diperbarui');
                return $this->redirect(['view', 'bulan' => $model->bulan, 'tahun' => $model->tahun]);
            } else {
                Yii::$app->session->setFlash('error', 'Data gagal diperbarui');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PeriodeGaji model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $bulan Bulan
     * @param int $tahun Tahun
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($bulan, $tahun)
    {
        $model =   $this->findModel($bulan, $tahun);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus');
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Data gagal dihapus');
        }
    }

    /**
     * Finds the PeriodeGaji model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $bulan Bulan
     * @param int $tahun Tahun
     * @return PeriodeGaji the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($bulan, $tahun)
    {
        if (($model = PeriodeGaji::findOne(['bulan' => $bulan, 'tahun' => $tahun])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
