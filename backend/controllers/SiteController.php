<?php

namespace backend\controllers;

use backend\models\Absensi;
use backend\models\IzinPulangCepat;
use backend\models\Karyawan;
use backend\models\MasterKode;
use backend\models\MessageReceiver;
use backend\models\PengajuanAbsensi;
use backend\models\PengajuanCuti;
use backend\models\PengajuanDinas;
use backend\models\PengajuanLembur;
use backend\models\PengajuanTugasLuar;
use backend\models\PengajuanWfh;
use backend\models\Pengumuman;
use common\models\LoginForm;

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


    public function actionIndex()
    {
        $assignments = Yii::$app->authManager->getAssignments(Yii::$app->user->id);
        $roleNames = array_column($assignments, 'roleName');

        $today = date('Y-m-d');

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['user/login']);
        } elseif ($roleNames[0] == 'Karyawan' || $roleNames[0] == 'Magang') {
            return $this->redirect(['home/index']);
        } else {

            $TotalKaryawan = Karyawan::find()->where(['is_aktif' => 1])->count();
            $TotalData = Absensi::find()->where(['tanggal' => date('Y-m-d'), 'kode_status_hadir' => 'H'])->count();
            $TotalDataBelum = $TotalKaryawan - $TotalData;



            $count = PengajuanWfh::find()
                ->select(['id_karyawan']) // Pilih kolom yang di-group
                ->where([
                    'AND',
                    'JSON_CONTAINS(tanggal_array, :today) = 1',
                    ['!=', 'status', 2]
                ])
                ->groupBy(['id_karyawan']) // Group by id_karyawan
                ->addParams([':today' => json_encode($today)])
                ->count();

            $wfhCountTouday = $count;

            // total pegumuman
            $totalPengumuman = Pengumuman::find()->count();
            $pengajuanLembur = PengajuanLembur::find()->where(['status' => '0'])->count();
            $pengajuanCuti = PengajuanCuti::find()->where(['status' => '0'])->count();
            $pengajuanDinas = PengajuanDinas::find()->where(['status' => '0'])->count();
            $pengajuanPulangCepat = IzinPulangCepat::find()->where(['status' => '0'])->count();
            $pengajuanWFH = PengajuanWfh::find()->where(['status' => '0'])->count();
            $pengajuanAbsensi = PengajuanAbsensi::find()->where(['status' => '0'])->count();
            $pengajuanTugasLuar = PengajuanTugasLuar::find()->where(['status_pengajuan' => '0'])->count();

            $dates = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = date('d-m-Y', strtotime("-$i days"));
                $dates[$date] = null;
            }


            $absensi = Absensi::find()
                ->asArray()
                ->select(['tanggal'])
                ->where(['kode_status_hadir' => 'H'])
                ->andWhere(['>=', 'tanggal', date('Y-m-d', strtotime('-7 days'))])
                ->orderBy('tanggal ASC')
                ->all();

            foreach ($absensi as $absen) {
                $tanggal_var = date('d-m-Y', strtotime($absen['tanggal']));
                if (!isset($dates[$tanggal_var])) {
                    $dates[$tanggal_var] = [];
                }
                $dates[$tanggal_var][] = $absen;
            }

            uksort($dates, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });


            $datesAsJson = Json::encode($dates);



            $is_ada_notif =  MessageReceiver::find()
                ->where(['receiver_id' => $this->user->id, 'is_open' => 0])
                ->count();


            return $this->render('index', compact('is_ada_notif', 'datesAsJson', 'TotalKaryawan', 'TotalData', 'TotalDataBelum', 'wfhCountTouday', 'totalPengumuman', 'pengajuanLembur', 'pengajuanCuti', 'pengajuanDinas', 'pengajuanPulangCepat', 'pengajuanWFH', 'pengajuanAbsensi', 'pengajuanTugasLuar'));
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
