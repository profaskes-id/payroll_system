<?php

namespace backend\controllers;

use backend\models\PengajuanLembur;
use backend\models\PengajuanLemburSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PengajuanLemburController implements the CRUD actions for PengajuanLembur model.
 */
class PengajuanLemburController extends Controller
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
     * Lists all PengajuanLembur models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, 1, $tahun));
        $lastDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, date('t', mktime(0, 0, 0, $bulan, 1, $tahun)), $tahun));

        $tgl_mulai = $firstDayOfMonth;
        $tgl_selesai = date('Y-m-d');
        $searchModel = new PengajuanLemburSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $tgl_mulai, $tgl_selesai);

        if ($this->request->isPost) {
            // dd($this->request->post());
            $tgl_mulai = $this->request->post('PengajuanLemburSearch')['tanggal_mulai'];
            $tgl_selesai = $this->request->post('PengajuanLemburSearch')['tanggal_selesai'];
            $dataProvider = $searchModel->search($searchModel, $tgl_mulai, $tgl_selesai);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai
        ]);
    }

    /**
     * Displays a single PengajuanLembur model.
     * @param int $id_pengajuan_lembur Id Pengajuan Lembur
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_pengajuan_lembur)
    {
        $model = $this->findModel($id_pengajuan_lembur);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new PengajuanLembur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanLembur();

        $poinArray = [];
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->pekerjaan = json_encode(Yii::$app->request->post('pekerjaan'));
                // $model->disetujui_oleh = Yii::$app->user->identity->id;
                if ($model->status == Yii::$app->params['disetujui']) {
                    $model->disetujui_oleh = Yii::$app->user->identity->id;
                    $model->disetujui_pada = date('Y-m-d H:i:s');
                }

                $jamMulai = strtotime($model->jam_mulai);
                $jamSelesai = strtotime($model->jam_selesai);

                // Menghitung selisih waktu dalam detik
                $selisihDetik = $jamSelesai - $jamMulai;

                // Mengkonversi selisih waktu ke dalam format jam:menit
                $durasi = gmdate('H:i', $selisihDetik);

                // Menyimpan durasi ke dalam model
                $model->durasi = $durasi;


                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Pengajuan Lembur Berhasil Ditambahkan');
                } else {
                    Yii::$app->session->setFlash('error', 'Pengajuan Lembur Gagal Ditambahkan');
                }
                return $this->redirect(['view', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'poinArray' => $poinArray,
        ]);
    }

    /**
     * Updates an existing PengajuanLembur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_pengajuan_lembur Id Pengajuan Lembur
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_pengajuan_lembur)
    {
        $model = $this->findModel($id_pengajuan_lembur);
        $poinArray = json_decode($model->pekerjaan);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->pekerjaan = json_encode(Yii::$app->request->post('pekerjaan'));
            $model->disetujui_oleh = Yii::$app->user->identity->id;
            $model->disetujui_pada = date('Y-m-d H:i:s');

            $jamMulai = strtotime($model->jam_mulai);
            $jamSelesai = strtotime($model->jam_selesai);
            $selisihDetik = $jamSelesai - $jamMulai;
            $durasi = gmdate('H:i', $selisihDetik);
            $model->durasi = $durasi;

            if ($model->save()) {
                return $this->redirect(['view', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'poinArray' => $poinArray
        ]);
    }

    /**
     * Deletes an existing PengajuanLembur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pengajuan_lembur Id Pengajuan Lembur
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_pengajuan_lembur)
    {
        $this->findModel($id_pengajuan_lembur)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PengajuanLembur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pengajuan_lembur Id Pengajuan Lembur
     * @return PengajuanLembur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pengajuan_lembur)
    {
        if (($model = PengajuanLembur::findOne(['id_pengajuan_lembur' => $id_pengajuan_lembur])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
