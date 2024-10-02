<?php

namespace backend\controllers;

use backend\models\Absensi;
use backend\models\IzinPulangCepat;
use backend\models\MasterKode;
use backend\models\PengajuanCuti;
use backend\models\PengajuanDinas;
use backend\models\PengajuanLembur;
use backend\models\Pengumuman;
use common\models\LoginForm;
use common\models\User;
use Symfony\Component\CssSelector\Parser\Shortcut\ElementParser;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
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

            $TotalKaryawan = User::find()->where(['role_id' => '2'])->count();
            $TotalData = Absensi::find()->where(['tanggal' => date('Y-m-d')])->count();
            $TotalDataBelum = $TotalKaryawan - $TotalData;
            $izin = MasterKode::find()->where(['nama_group' => 'status-hadir', 'nama_kode' => 'Izin'])->one();
            $TotalIzin = Absensi::find()->where(['kode_status_hadir' => $izin->kode, 'tanggal' => date('Y-m-d')])->count();

            // total pegumuman
            $totalPengumuman = Pengumuman::find()->count();
            $pengajuanLembur = PengajuanLembur::find()->where(['status' => '0'])->count();
            $pengajuanCuti = PengajuanCuti::find()->where(['status' => '0'])->count();
            $pengajuanDinas = PengajuanDinas::find()->where(['status' => '0'])->count();
            $pengajuanPulangCepat = IzinPulangCepat::find()->where(['status' => '0'])->count();



            $dates = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = date('d-m-Y', strtotime("-$i days"));
                $dates[$date] = null;
            }

            $absensi = Absensi::find()
                ->select(['id_karyawan', 'tanggal'])
                ->where(['kode_status_hadir' => 'H'])
                ->andWhere(['>=', 'tanggal', date('Y-m-d', strtotime('-7 days'))])
                ->orderBy('tanggal')
                ->all();

            foreach ($absensi as $absen) {
                $tanggal = date('d-m-Y', strtotime($absen->tanggal));
                if (!isset($dates[$tanggal])) {
                    $dates[$tanggal] = [];
                }
                $dates[$tanggal][] = $absen;
            }

            $datesAsJson = Json::encode($dates);


            return $this->render('index', compact('datesAsJson', 'TotalKaryawan', 'TotalData', 'TotalDataBelum', 'TotalIzin', 'totalPengumuman', 'pengajuanLembur', 'pengajuanCuti', 'pengajuanDinas', 'pengajuanPulangCepat'));
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
