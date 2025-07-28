<?php

namespace backend\controllers;

use backend\models\JadwalShift;
use backend\models\JadwalShiftSearch;
use backend\models\Karyawan;

use DateTime;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * JadwalShiftController implements the CRUD actions for JadwalShift model.
 */
class JadwalShiftController extends Controller
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
     * Lists all JadwalShift models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new JadwalShiftSearch();
        $model = new JadwalShift();

        // Ambil awal dan akhir bulan saat ini
        $startDate = date('Y-m-01'); // tanggal 1 bulan ini
        $endDate = date('Y-m-t');    // tanggal terakhir bulan ini

        // Query hanya data yang tanggalnya di bulan sekarang
        $query = JadwalShift::find()
            ->joinWith('karyawan')
            ->where(['between', 'tanggal', $startDate, $endDate])
            ->orderBy(['karyawan.nama' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $data = $dataProvider->getModels();

        $karyawanList = [];
        $shifts = [];

        foreach ($data as $model) {
            $id_karyawan = $model->id_karyawan;
            $nama = $model->karyawan->nama;
            $tanggal = date('d', strtotime($model->tanggal));

            if (!isset($karyawanList[$id_karyawan])) {
                $karyawanList[$id_karyawan] = [
                    'nama' => $nama,
                    'id_karyawan' => $id_karyawan,
                    'shift' => []
                ];
            }

            $karyawanList[$id_karyawan]['shift'][$tanggal] = [
                'nama_shift' => $model->shiftKerja->nama_shift,
                'id_jadwal_shift' => $model->id_jadwal_shift
            ];

            $shifts[$id_karyawan][$tanggal] = $model->id_shift_kerja;
        }

        // Buat array tanggal untuk bulan ini
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $dates = [];

        while ($start <= $end) {
            $dates[] = $start->format('d');
            $start->modify('+1 day');
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'karyawanList' => $karyawanList,
            'shifts' => $shifts,
            'dates' => $dates,
            'start' => new DateTime($startDate),
            'end' => new DateTime($endDate),
            'model' => $model
        ]);
    }


    public function actionUpdateShift($shiftId, $namaKaryawan, $tanggal)
    {
        // Cari data berdasarkan shiftId, namaKaryawan, dan tanggal
        $model = JadwalShift::findOne([
            'id_shift_kerja' => $shiftId,
            'id_karyawan' => Karyawan::find()->select('id_karyawan')->where(['nama' => $namaKaryawan])->scalar(), // Fungsi untuk mendapatkan ID karyawan berdasarkan nama
            'tanggal' => $tanggal
        ]);

        if (!$model) {
            throw new NotFoundHttpException('Data tidak ditemukan');
        }

        // Menampilkan form untuk update shift
        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }


    /**
     * Displays a single JadwalShift model.
     * @param int $id_jadwal_shift Id Jadwal Shift
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_jadwal_shift)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_jadwal_shift),
        ]);
    }

    /**
     * Creates a new JadwalShift model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new JadwalShift();

        if ($this->request->isPost) {
            $postData = $this->request->post('JadwalShift');

            $idKaryawanList = $postData['id_karyawan'];
            $tanggalAwal = $postData['tanggal_awal'];
            $tanggalAkhir = $postData['tanggal_akhir'];
            $idShiftKerja = $postData['id_shift_kerja'];

            // convert to DateTime
            $start = new \DateTime($tanggalAwal);
            $end = new \DateTime($tanggalAkhir);
            $end = $end->modify('+1 day'); // supaya termasuk tanggal akhir

            foreach ($idKaryawanList as $idKaryawan) {
                $periode = new \DatePeriod($start, new \DateInterval('P1D'), $end);

                foreach ($periode as $tanggal) {
                    $newModel = new JadwalShift();
                    $newModel->id_karyawan = $idKaryawan;
                    $newModel->tanggal = $tanggal->format('Y-m-d');
                    $newModel->id_shift_kerja = $idShiftKerja;

                    if (!$newModel->save()) {
                        Yii::$app->session->setFlash('error', 'Gagal simpan jadwal untuk karyawan ID: ' . $idKaryawan);
                        // Debug error:
                        Yii::error($newModel->getErrors());
                    }
                }
            }

            Yii::$app->session->setFlash('success', 'Jadwal berhasil dibuat untuk semua karyawan!');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing JadwalShift model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_jadwal_shift Id Jadwal Shift
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $post = $this->request->post()['JadwalShift'] ?? null;

        if ($post === null) {
            Yii::$app->session->setFlash('error', 'Data tidak valid.');
            return $this->redirect(['index']);
        }

        // Cek apakah ini update atau insert
        $model = JadwalShift::findOne($post['id']);

        $isNew = false;
        if (!$model) {
            $model = new JadwalShift();
            $isNew = true;
        }

        // Ambil inputan yang mau disimpan
        $idKaryawan = $post['id_karyawan'];
        $tanggal = $post['tanggal'];

        // Cek apakah ada data shift lain yang bentrok (kecuali jika ini update dan id-nya sama)
        $existing = JadwalShift::find()
            ->where(['id_karyawan' => $idKaryawan, 'tanggal' => $tanggal])
            ->andFilterWhere(['<>', 'id_jadwal_shift', $model->id_jadwal_shift]) // abaikan data yang sedang diupdate
            ->one();

        if ($existing) {
            Yii::$app->session->setFlash('error', 'Data shift untuk karyawan ini di tanggal tersebut sudah ada.');
            return $this->redirect(['index']);
        }

        // Set data ke model
        $model->id_karyawan = $idKaryawan;
        $model->tanggal = $tanggal;
        $model->id_shift_kerja = $post['id_shift_kerja'];

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Data shift berhasil disimpan.');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menyimpan data shift.');
        }

        return $this->redirect(['index']);
    }



    /**
     * Deletes an existing JadwalShift model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_jadwal_shift Id Jadwal Shift
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $post = $this->request->post('JadwalShift');
        $model = $this->findModel($post['id']);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Data shift berhasil dihapus.');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menghapus data shift.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the JadwalShift model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_jadwal_shift Id Jadwal Shift
     * @return JadwalShift the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_jadwal_shift)
    {
        if (($model = JadwalShift::findOne(['id_jadwal_shift' => $id_jadwal_shift])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
