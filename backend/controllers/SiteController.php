<?php

namespace backend\controllers;

use backend\models\Absensi;
use backend\models\IzinPulangCepatSearch;
use backend\models\Karyawan;
use backend\models\MasterKode;
use backend\models\PengajuanCuti;
use backend\models\PengajuanCutiSearch;
use backend\models\PengajuanDinas;
use backend\models\PengajuanDinasSearch;
use backend\models\PengajuanLembur;
use backend\models\PengajuanLemburSearch;
use backend\models\Pengumuman;
use common\models\LoginForm;
use common\models\User;
use Symfony\Component\CssSelector\Parser\Shortcut\ElementParser;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['user/login']);
        } elseif (Yii::$app->user->can('admin')) {

            //1.total karyawan
            $TotalKaryawan = User::find()->where(['role_id' => '2'])->count();
            // 2. total data yang isi absen hari ini
            $TotalData = Absensi::find()->where(['tanggal' => date('Y-m-d')])->count();
            // 3. total yang belum isi absen
            $TotalDataBelum = $TotalKaryawan - $TotalData;
            // 4. total izin
            $izin = MasterKode::find()->where(['nama_group' => 'status-hadir', 'nama_kode' => 'Izin'])->one();
            $TotalIzin = Absensi::find()->where(['kode_status_hadir' => $izin->kode, 'tanggal' => date('Y-m-d')])->count();
            // 3 total pegumuman
            $totalPengumuman = Pengumuman::find()->count();

            $pengajuanLembur = PengajuanLembur::find()->where(['status' => '0'])->count();
            $pengajuanCuti = PengajuanCuti::find()->where(['status' => '0'])->count();
            $pengajuanDinas = PengajuanDinas::find()->where(['status' => '0'])->count();


            // pengajuan Cuti
            $PengajuanCuti = new PengajuanCutiSearch();
            $PengajuanCuti->status = 0;
            $PengajuanCuti_dataProvider = $PengajuanCuti->search($this->request->queryParams);

            //pengajuan lembur
            $PengajuanLembur = new PengajuanLemburSearch();
            $PengajuanLembur->status = 0;
            $PengajuanLembu_dataProvider = $PengajuanLembur->search($this->request->queryParams);

            //pengajuan dinas
            $PengajuanDinas = new PengajuanDinasSearch();
            $PengajuanDinas->status = 0;
            $PengajuanDinas_dataProvider = $PengajuanDinas->search($this->request->queryParams);


            //izin pulang cepat
            $izinPulcep = new IzinPulangCepatSearch();
            $izinPulcep->status = 0;
            $PulangCepat_dataProvider = $izinPulcep->search($this->request->queryParams);
            // dd($PulangCepat_dataProvider->models);



            return $this->render('index', compact('TotalKaryawan', 'TotalData', 'TotalDataBelum', 'TotalIzin', 'totalPengumuman', 'pengajuanLembur', 'pengajuanCuti', 'pengajuanDinas', 'PengajuanCuti_dataProvider', 'PengajuanLembu_dataProvider', 'PengajuanDinas_dataProvider', 'PulangCepat_dataProvider'));
        } elseif (!Yii::$app->user->can('admin')) {

            return $this->redirect(['home/index']);
        } else {
            return $this->redirect(['user/login']);
        }
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
