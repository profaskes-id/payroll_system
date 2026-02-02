<?php

namespace backend\controllers;

use backend\models\Karyawan;
use backend\models\MasterKode;
use backend\models\PengajuanLembur;
use backend\models\PengajuanLemburSearch;
use Yii;
use yii\base\DynamicModel;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RekapLemburDiajukanController implements the CRUD actions for RekapLemburDiajukan model.
 */
class RekapLemburDiajukanController extends Controller
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

    public function actionIndex()
    {
        $request = Yii::$app->request;

        // ===============================
        // Hitung default periode tanggal
        // ===============================
        $tanggalAwal = MasterKode::find()
            ->where(['nama_group' => 'tanggal-cut-of'])
            ->one();

        $tanggalAwalInt   = (int) $tanggalAwal->nama_kode;
        $tanggalSekarang  = (int) date('d');
        $bulanSekarang    = (int) date('m');
        $tahunSekarang    = (int) date('Y');

        if ($tanggalSekarang < $tanggalAwalInt) {
            $tgl_mulai = date(
                'Y-m-d',
                mktime(0, 0, 0, $bulanSekarang - 1, $tanggalAwalInt, $tahunSekarang)
            );
            $tgl_selesai = date(
                'Y-m-d',
                mktime(0, 0, 0, $bulanSekarang, $tanggalAwalInt - 1, $tahunSekarang)
            );
        } else {
            $tgl_mulai = date(
                'Y-m-d',
                mktime(0, 0, 0, $bulanSekarang, $tanggalAwalInt, $tahunSekarang)
            );
            $tgl_selesai = date(
                'Y-m-d',
                mktime(0, 0, 0, $bulanSekarang + 1, $tanggalAwalInt - 1, $tahunSekarang)
            );
        }

        // =================================
        // Override tanggal dari request user
        // =================================
        $params = $request->get('DynamicModel', []);

        if (!empty($params['tanggal_mulai'])) {
            $tgl_mulai = $params['tanggal_mulai'];
        }

        if (!empty($params['tanggal_selesai'])) {
            $tgl_selesai = $params['tanggal_selesai'];
        }


        // ===============================
        // Model filter (ID KARYAWAN)
        // ===============================
        $model = new DynamicModel([
            'id_karyawan',
            'tgl_mulai',
            'tgl_selesai',
        ]);

        $model->addRule(['id_karyawan'], 'integer')
            ->addRule(['tgl_mulai', 'tgl_selesai'], 'safe');

        $model->load($request->get());

        if (!$model->tgl_mulai || !$model->tgl_selesai) {
            // hitung default periode
            $model->tgl_mulai   = $tgl_mulai;
            $model->tgl_selesai = $tgl_selesai;
        }


        // ===============================
        // Query utama (DATA TIDAK DIUBAH)
        // ===============================
        $query = (new \yii\db\Query())
            ->select([
                'k.id_karyawan',
                'k.nama',
                'k.kode_karyawan',
                'dp.id_data_pekerjaan',
                'b.nama_bagian AS bagian',
                'dp.status',
                'mkj.nama_kode AS jabatan',
                'COUNT(p.id_pengajuan_lembur) AS total_pengajuan',
                'SUM(CASE WHEN p.status = 0 THEN 1 ELSE 0 END) AS total_belum_disetujui',
                'COALESCE(SUM(p.hitungan_jam), 0) AS total_jam_lembur',
            ])
            ->from(['k' => 'karyawan'])
            ->leftJoin(
                'pengajuan_lembur p',
                'k.id_karyawan = p.id_karyawan
             AND p.tanggal BETWEEN :tgl_mulai AND :tgl_selesai'
            )
            ->leftJoin(
                'data_pekerjaan dp',
                'dp.id_karyawan = k.id_karyawan AND dp.is_aktif = 1'
            )
            ->leftJoin(
                'master_kode mkj',
                'mkj.nama_group = "jabatan" AND mkj.kode = dp.jabatan'
            )
            ->leftJoin(
                'bagian b',
                'b.id_bagian = dp.id_bagian'
            )
            ->where(['k.is_aktif' => 1])
            ->andWhere(['<>', 'dp.status', 5]);

        // ===============================
        // Filter ID Karyawan
        // ===============================
        if (!empty($model->id_karyawan)) {
            $query->andWhere(['k.id_karyawan' => $model->id_karyawan]);
        }

        $query->groupBy([
            'k.id_karyawan',
            'k.nama',
            'k.kode_karyawan',
            'dp.id_data_pekerjaan',
            // 'dp.bagian',
            'dp.jabatan',
            'dp.status',
        ])
            ->orderBy(['k.nama' => SORT_ASC])
            ->addParams([
                ':tgl_mulai'   => $model->tgl_mulai,
                ':tgl_selesai' => $model->tgl_selesai,

            ]);



        $data = $query->all();

        // ===============================
        // Data Provider
        // ===============================
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => [
                    'nama',
                    'kode_karyawan',
                    'bagian',
                    'jabatan',
                    'total_pengajuan',
                    'total_belum_disetujui',
                    'total_jam_lembur',
                ],
            ],
        ]);



        return $this->render('index', [
            'model'        => $model,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single RekapLemburDiajukan model.
     * @param int $id_rekap_lembur Id Rekap Lembur
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_rekap_lembur)
    {
        // return $this->render('view', [
        //     'model' => $this->findModel($id_rekap_lembur),
        // ]);
    }


    // public function actionDelete($id_rekap_lembur)
    // {
    //     $this->findModel($id_rekap_lembur)->delete();

    //     return $this->redirect(['index']);
    // }

}
