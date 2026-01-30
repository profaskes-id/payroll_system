<?php

namespace backend\controllers;

use backend\models\PendapatanPotonganLainnya;
use backend\models\PendapatanPotonganLainnyaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * PendapatanPotonganLainnyaController implements the CRUD actions for PendapatanPotonganLainnya model.
 */
class PendapatanPotonganLainnyaController extends Controller
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
     * Lists all PendapatanPotonganLainnya models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PendapatanPotonganLainnyaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PendapatanPotonganLainnya model.
     * @param int $id_ppl Id Ppl
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_ppl)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_ppl),
        ]);
    }

    /**
     * Creates a new PendapatanPotonganLainnya model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PendapatanPotonganLainnya();

        if ($model->load(Yii::$app->request->post())) {

            // Ambil data array
            $jumlahArray = Yii::$app->request->post('PendapatanPotonganLainnya')['jumlah'] ?? [];
            $keteranganArray = Yii::$app->request->post('PendapatanPotonganLainnya')['keterangan'] ?? [];

            // Ambil field lain yang sama untuk semua record
            $idKaryawan = $model->id_karyawan;
            $isPendapatan = $model->is_pendapatan;
            $isPotongan = $model->is_potongan;
            $bulan = $model->bulan;
            $tahun = $model->tahun;


            // Looping untuk menyimpan setiap record
            foreach ($jumlahArray as $index => $jumlah) {
                $keterangan = $keteranganArray[$index] ?? null;

                if (!empty($jumlah) && !empty($keterangan)) {
                    $newModel = new PendapatanPotonganLainnya();
                    $newModel->id_karyawan = $idKaryawan;
                    $newModel->jumlah = $jumlah;
                    $newModel->keterangan = $keterangan;
                    $newModel->is_pendapatan = $isPendapatan;
                    $newModel->is_potongan = $isPotongan;
                    $newModel->bulan = $bulan;
                    $newModel->tahun = $tahun;
                    $newModel->created_at = time();
                    $newModel->updated_at = time();
                    // Bisa tambahkan validasi juga
                    if (!$newModel->save()) {
                        // Optional: handle error, misalnya log
                        Yii::error($newModel->errors);
                    }
                }
            }

            return $this->redirect(['/transaksi-gaji/view', 'id_karyawan' => $idKaryawan, 'bulan' => $newModel->bulan, 'tahun' => $newModel->tahun]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing PendapatanPotonganLainnya model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_ppl Id Ppl
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $request = Yii::$app->request;

        $idKaryawan = $request->get('id_karyawan');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');
        $isPendapatan = (int) $request->get('pendapatan', 1);

        $model = new PendapatanPotonganLainnya();

        // ambil data lama (buat ditampilkan di form)
        $existingData = PendapatanPotonganLainnya::find()
            ->where([
                'id_karyawan' => $idKaryawan,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'is_pendapatan' => $isPendapatan
            ])
            ->all();

        if ($model->load($request->post())) {

            $post = $request->post('PendapatanPotonganLainnya');
            $jumlahArray = $post['jumlah'] ?? [];
            $keteranganArray = $post['keterangan'] ?? [];

            // 🔥 HAPUS DATA LAMA (bulk)
            PendapatanPotonganLainnya::deleteAll([
                'id_karyawan' => $idKaryawan,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'is_pendapatan' => $isPendapatan
            ]);

            // 🔥 INSERT ULANG (bulk save)
            foreach ($jumlahArray as $i => $jumlah) {
                if (empty($jumlah) || empty($keteranganArray[$i])) {
                    continue;
                }

                $newModel = new PendapatanPotonganLainnya();
                $newModel->id_karyawan = $idKaryawan;
                $newModel->bulan = $bulan;
                $newModel->tahun = $tahun;
                $newModel->is_pendapatan = $isPendapatan;
                $newModel->jumlah = $jumlah;
                $newModel->keterangan = $keteranganArray[$i];
                $newModel->save(false);
            }

            return $this->redirect('/panel/transaksi-gaji/index');
        }

        return $this->render('create', [
            'model' => $model,
            'existingData' => $existingData,
            'isUpdate' => true
        ]);
    }


    /**
     * Deletes an existing PendapatanPotonganLainnya model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_ppl Id Ppl
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_ppl)
    {
        $model =  $this->findModel($id_ppl);

        if ($model->delete()) {
            // flash berhasil di haspu
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error', 'Data gagal dihapus');
        }
        return $this->redirect(['transaksi-gaji/view', 'id_karyawan' => $model->id_karyawan, 'bulan' => $model->bulan, 'tahun' => $model->tahun]);
    }

    /**
     * Finds the PendapatanPotonganLainnya model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_ppl Id Ppl
     * @return PendapatanPotonganLainnya the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_ppl)
    {
        if (($model = PendapatanPotonganLainnya::findOne(['id_ppl' => $id_ppl])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
