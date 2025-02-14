<?php

namespace backend\controllers;

use backend\models\ExpensesDetail;
use backend\models\ExpensesDetailSearch;
use backend\models\ExpensesHeader;
use backend\models\KategoriExpenses;
use backend\models\MasterKode;
use backend\models\SubkategoriExpenses;
use kartik\mpdf\Pdf;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExpensesDetailController implements the CRUD actions for ExpensesDetail model.
 */
class ExpensesDetailController extends Controller
{

    public function beforeAction($action)
    {
        // Cek apakah aksi yang sedang dijalankan adalah 'delete'
        if ($action->id == 'delete' || $action->id == 'index') {
            // Nonaktifkan CSRF hanya untuk aksi delete
            $this->enableCsrfValidation = false;
        } else {
            // Aktifkan CSRF untuk aksi lainnya
            $this->enableCsrfValidation = true;
        }

        // Panggil parent::beforeAction untuk melanjutkan proses
        return parent::beforeAction($action);
    }
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
     * Lists all ExpensesDetail models.
     *
     * @return string
     */

    public function actionIndex()
    {

        $bulan = date('m');
        $tahun = date('Y');
        $firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan,  +1, $tahun));
        $lastdate = date('Y-m-d');
        $tgl_mulai = $firstDayOfMonth;
        $tgl_selesai = $lastdate;
        $searchModel = new ExpensesDetailSearch();
        $total = '';
        $dataProvider = $searchModel->search($this->request->queryParams, $tgl_mulai, $tgl_selesai);
        if ($this->request->isPost) {
            $tgl_mulai =   $this->request->post('ExpensesDetailSearch')['tanggal_mulai'] ?? date('Y-m-d', strtotime($this->request->post()['tanggal_awal']));
            $tgl_selesai = $this->request->post('ExpensesDetailSearch')['tanggal_selesai'] ?? date('Y-m-d',  strtotime($this->request->post()['tanggal_akhir']));
            $searchModel->id_kategori_expenses = $this->request->post('ExpensesDetailSearch')['id_kategori_expenses'] ?? $this->request->post()['id_kategori_expenses'];
            $searchModel->id_subkategori_expenses = $this->request->post('ExpensesDetailSearch')['id_subkategori_expenses'] ?? $this->request->post()['id_subkategori_expenses'];
            $dataProvider = $searchModel->search($searchModel, $tgl_mulai, $tgl_selesai);
            $total =  $total = $this->sumJumlahPengeluaran($dataProvider->models);
        }



        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
            'total' => $total
        ]);
    }

    /**
     * Displays a single ExpensesDetail model.
     * @param int $id_expense_detail Id Expenses Detail
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_expense_detail)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_expense_detail),
        ]);
    }

    /**
     * Creates a new ExpensesDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ExpensesDetail();
        $headerEx = new  ExpensesHeader();
        $nextKode = $headerEx->generateAutoCode();

        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {

                $headerExPost = $this->request->post('ExpensesHeader');
                $headerEx->id_expense_header = $headerExPost['id_expense_header'];
                $headerEx->tanggal = $headerExPost['tanggal'];

                if ($headerEx->save()) {
                    $headerEx->refresh();
                    $model->id_expense_header = $headerEx['id_expense_header'];
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                        return $this->redirect(['update', 'id_expense_header' => $model->id_expense_header, 'tanggal' => $headerEx->tanggal]);
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'headerEx' => $headerEx,
            'nextKode' => $nextKode
        ]);
    }

    /**
     * Updates an existing ExpensesDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_expense_detail Id Expenses Detail
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_expense_header)
    {
        $model = new ExpensesDetail();
        $headerEx = new ExpensesHeader();

        $dataHEader = $model->getHeaderAndDetailDataWhereHeaderKode($id_expense_header);
        if ($this->request->isPost && $model->load($this->request->post())) {
            $headerExPost = $this->request->post('ExpensesHeader');
            $headerEx->id_expense_header = $headerExPost['id_expense_header'];
            $headerEx->tanggal = $headerExPost['tanggal'];
            $model->id_expense_header = $headerEx['id_expense_header'];
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                return $this->redirect(['update', 'id_expense_header' => $model->id_expense_header]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'headerEx' => $headerEx,
            'dataHEader' => $dataHEader

        ]);
    }

    public function actionPerbarui($id_expense_detail)
    {
        $model = $this->findModel($id_expense_detail);
        $headerEx = new ExpensesHeader();
        $headerEx->id_expense_header = $model['id_expense_header'];
        $headerEx->tanggal = $model->expenseHeader->tanggal;


        if ($this->request->isPost && $model->load($this->request->post())) {
            $headerExPost = $this->request->post('ExpensesHeader');
            $headerEx->id_expense_header = $headerExPost['id_expense_header'];
            $headerEx->tanggal = $headerExPost['tanggal'];
            $model->id_expense_detail = $id_expense_detail;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['index']);
            }
        }

        return $this->render('perbarui', [
            'model' => $model,
            'headerEx' => $headerEx
        ]);
    }

    public function actionReport($tahun)
    {
        $data = $this->getExpensesData($tahun);
        return $this->render('report', ['data' => $data, 'tahun' => $tahun]);
    }
    public function actionPdf($tahun)
    {
        $data = $this->getExpensesData($tahun);
        $content = $this->renderPartial('pdf', [
            'data' => $data,
            'tahun' => $tahun
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => 'pengeluaran tahun ' . $tahun],
            'methods' => [

                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        return $pdf->render();


        return $this->render('pdf', ['data' => $data, 'tahun' => $tahun]);
    }

    public function actionExel($tahun)
    {
        $data = $this->getExpensesData($tahun);

        // Header untuk Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Rekapan-Pengeluaran-{$tahun}.xls");

        // Render view khusus Excel
        return $this->renderPartial('exel', ['data' => $data, 'tahun' => $tahun]);
    }

    public function getExpensesData($tahun)
    {
        // Mengambil semua data kategori
        $allKategori = KategoriExpenses::find()->asArray()->all();

        // Mengambil semua data subkategori
        $allSubkategori = SubkategoriExpenses::find()->asArray()->all();

        // Mengambil semua data header expenses berdasarkan tahun
        $allExpensesHeader = ExpensesHeader::find()
            ->where(['YEAR(tanggal)' => $tahun]) // Filter berdasarkan tahun
            ->asArray()
            ->all();

        // Inisialisasi array untuk menyimpan data
        $data = [];

        // Loop melalui setiap kategori
        foreach ($allKategori as $kategori) {
            // Menyimpan kategori
            $kategoriData = [
                'id_kategori_expenses' => $kategori['id_kategori_expenses'],
                'nama_kategori' => $kategori['nama_kategori'],
                'deskripsi' => $kategori['deskripsi'],
                'subkategori' => [] // Inisialisasi array subkategori
            ];

            // Loop melalui setiap subkategori untuk mengelompokkan berdasarkan id_kategori_expenses
            foreach ($allSubkategori as $sub) {
                if ($sub['id_kategori_expenses'] === $kategori['id_kategori_expenses']) {
                    // Inisialisasi bulan dan total
                    $bulanData = [
                        'januari' => 0,
                        'februari' => 0,
                        'maret' => 0,
                        'april' => 0,
                        'mei' => 0,
                        'juni' => 0,
                        'juli' => 0,
                        'agustus' => 0,
                        'september' => 0,
                        'oktober' => 0,
                        'november' => 0,
                        'desember' => 0,
                    ];

                    // Loop melalui setiap header expenses
                    foreach ($allExpensesHeader as $header) {
                        // Ambil data detail berdasarkan id_expense_header dan id_subkategori_expenses
                        $expensesDetail = ExpensesDetail::find()
                            ->where(['id_subkategori_expenses' => $sub['id_subkategori_expenses']])
                            ->andWhere(['id_expense_header' => $header['id_expense_header']])
                            ->asArray()
                            ->all();

                        // Jumlahkan jumlah untuk setiap bulan
                        foreach ($expensesDetail as $detail) {
                            $month = date('n', strtotime($header['tanggal'])); // Ambil bulan dari tanggal
                            switch ($month) {
                                case 1:
                                    $bulanData['januari'] += $detail['jumlah'];
                                    break;
                                case 2:
                                    $bulanData['februari'] += $detail['jumlah'];
                                    break;
                                case 3:
                                    $bulanData['maret'] += $detail['jumlah'];
                                    break;
                                case 4:
                                    $bulanData['april'] += $detail['jumlah'];
                                    break;
                                case 5:
                                    $bulanData['mei'] += $detail['jumlah'];
                                    break;
                                case 6:
                                    $bulanData['juni'] += $detail['jumlah'];
                                    break;
                                case 7:
                                    $bulanData['juli'] += $detail['jumlah'];
                                    break;
                                case 8:
                                    $bulanData['agustus'] += $detail['jumlah'];
                                    break;
                                case 9:
                                    $bulanData['september'] += $detail['jumlah'];
                                    break;
                                case 10:
                                    $bulanData['oktober'] += $detail['jumlah'];
                                    break;
                                case 11:
                                    $bulanData['november'] += $detail['jumlah'];
                                    break;
                                case 12:
                                    $bulanData['desember'] += $detail['jumlah'];
                                    break;
                            }
                        }
                    }

                    // Menambahkan subkategori ke dalam kategoriData
                    $kategoriData['subkategori'][] = [
                        'id_subkategori_expenses' => $sub['id_subkategori_expenses'],
                        'nama_subkategori' => $sub['nama_subkategori'],
                        'deskripsi' => $sub['deskripsi'],
                        'bulan' => $bulanData, // Menambahkan data bulan yang telah dijumlahkan
                        'total' => array_sum($bulanData) // Menghitung total dari semua bulan
                    ];
                }
            }

            // Menambahkan kategoriData ke dalam data
            $data[] = $kategoriData;
        }

        // Kembalikan data dalam format JSON
        return $data;
    }




    /**
     * Deletes an existing ExpensesDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_expense_detail Id Expenses Detail
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_expense_detail)
    {

        $this->findModel($id_expense_detail)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ExpensesDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_expense_detail Id Expenses Detail
     * @return ExpensesDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_expense_detail)
    {
        if (($model = ExpensesDetail::findOne(['id_expense_detail' => $id_expense_detail])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSubGet($id)
    {
        $subkategori = SubkategoriExpenses::find()->where(['id_kategori_expenses' => $id])->all();
        $datasub = \yii\helpers\ArrayHelper::map($subkategori, 'id_subkategori_expenses', 'nama_subkategori');
        return $this->asJson($datasub);
    }
    public function sumJumlahPengeluaran($models)
    {
        $total = 0;
        foreach ($models as $model) {
            $total += floatval($model['jumlah']);
        }
        return number_format($total, 2, '.', '');
    }
}
