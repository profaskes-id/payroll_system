<?php

namespace backend\controllers;

use backend\models\PembayaranKasbon;
use backend\models\PembayaranKasbonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * PembayaranKasbonController implements the CRUD actions for PembayaranKasbon model.
 */
class PembayaranKasbonController extends Controller
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
     * Lists all PembayaranKasbon models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PembayaranKasbonSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PembayaranKasbon model.
     * @param int $id_pembayaran_kasbon Id Pembayaran Kasbon
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_karyawan)
    {

        $model = PembayaranKasbon::find()
            ->where(['id_karyawan' => $id_karyawan, 'autodebt' => 0])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
        if (!$model) {
            return $this->redirect(['index']);
        }
        $data = PembayaranKasbon::find()
            ->where(['id_karyawan' => $id_karyawan])
            ->andWhere(['autodebt' => 0])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();



        return $this->render('view', [
            'data' => $data,
            'model' => $model
        ]);
    }




    public function actionCreateNewPayment($id_pembayaran_kasbon)
    {
        // Ambil data lama sebagai referensi
        $oldModel = $this->findModel($id_pembayaran_kasbon);

        // Buat model baru
        $model = new PembayaranKasbon();


        // Copy atribut dari data lama ke model baru
        $model->id_karyawan = $oldModel->id_karyawan;
        $model->id_kasbon = $oldModel->id_kasbon;
        $model->bulan = $oldModel->bulan;
        $model->tahun = $oldModel->tahun;
        $model->angsuran = $oldModel->angsuran;
        $model->sisa_kasbon = $oldModel->sisa_kasbon;
        $model->autodebt = $oldModel->autodebt;
        $model->tanggal_potong = date('Y-m-d'); // default tanggal hari ini
        $model->status_potongan = 0;
        $model->created_at = time();
        $model->jumlah_kasbon = $oldModel->jumlah_kasbon;
        $model->deskripsi = 'Pembayaran Kasbon';
        if ($this->request->isPost && $model->load($this->request->post())) {


            // Bersihkan format currency sebelum disimpan
            if (!empty($model->sisa_kasbon) && is_string($model->sisa_kasbon)) {
                $model->sisa_kasbon = $this->cleanCurrencyFormat($model->sisa_kasbon);
            }

            if (!empty($model->angsuran) && is_string($model->angsuran)) {
                $model->angsuran = $this->cleanCurrencyFormat($model->jumlah_potong); // PERBAIKAN: ganti jumlah_potong jadi angsuran
            }

            if (!empty($model->jumlah_potong) && is_string($model->jumlah_potong)) {
                $model->jumlah_potong = $this->cleanCurrencyFormat($model->jumlah_potong);
            }




            try {
                if ($model->save()) {
                    return $this->redirect(['view', 'id_karyawan' => $model->id_karyawan]);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal menyimpan data.');
                    print_r($model->getErrors());
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                echo 'Error: ' . $e->getMessage();
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Helper function untuk membersihkan format currency
     */
    private function cleanCurrencyFormat($value)
    {
        if (empty($value)) return $value;

        // Jika sudah numeric, langsung return
        if (is_numeric($value)) return $value;

        // Hapus semua karakter non-digit
        $cleaned = preg_replace('/[^\d]/', '', $value);

        return $cleaned;
    }

    /**
     * Deletes an existing PembayaranKasbon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pembayaran_kasbon Id Pembayaran Kasbon
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionDeleteLatest($id_karyawan)
    {
        // Ambil data terakhir berdasarkan created_at paling baru
        $model = PembayaranKasbon::find()
            ->where(['id_karyawan' => $id_karyawan, 'autodebt' => 0])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();


        if ($model === null) {
            Yii::$app->session->setFlash('error', 'Tidak ada data pembayaran kasbon yang dapat dihapus.');
            return $this->redirect(['index']); // ubah ke halaman yang sesuai
        }

        // Simpan ID sebelum dihapus (optional)
        $id = $model->id_pembayaran_kasbon;

        if (strtolower($model->deskripsi) === 'top-up kasbon') {
            // Pastikan relasi 'kasbon' ada dan valid
            if ($model->kasbon) {
                $pengajuanKasbon = $model->kasbon; // relasi ke tabel pengajuan_kasbon
                $pengajuanKasbon->status = 0; // set status ke 0

                if ($pengajuanKasbon->save(false)) {
                    Yii::$app->session->setFlash('success', 'Status pengajuan kasbon berhasil direset karena top-up kasbon.');
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal memperbarui status pengajuan kasbon.');
                }
            } else {
                Yii::$app->session->setFlash('warning', 'Relasi kasbon tidak ditemukan.');
            }
        }

        // Lakukan penghapusan
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', "Pembayaran kasbon terbaru , berhasil dihapus.");
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menghapus pembayaran kasbon.');
        }



        return $this->redirect(['view', 'id_karyawan' => $id_karyawan]); // arahkan ke halaman daftar kasbon
    }




    /**
     * Finds the PembayaranKasbon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pembayaran_kasbon Id Pembayaran Kasbon
     * @return PembayaranKasbon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pembayaran_kasbon)
    {
        if (($model = PembayaranKasbon::findOne(['id_pembayaran_kasbon' => $id_pembayaran_kasbon])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
