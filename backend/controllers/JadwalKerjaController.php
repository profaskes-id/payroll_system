<?php

namespace backend\controllers;

use backend\models\JadwalKerja;
use backend\models\JadwalKerjaSearch;
use Mpdf\Http\Exception\NetworkException;
use PHPUnit\Framework\Error\Error;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JadwalKerjaController implements the CRUD actions for JadwalKerja model.
 */
class JadwalKerjaController extends Controller
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
     * Lists all JadwalKerja models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new JadwalKerjaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JadwalKerja model.
     * @param int $id_jadwal_kerja Id Jadwal Kerja
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_jadwal_kerja)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_jadwal_kerja),
        ]);
    }

    /**
     * Creates a new JadwalKerja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id_jam_kerja = null)
    {
        $model = new JadwalKerja();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['/jam-kerja/view', 'id_jam_kerja' => $model->id_jam_kerja]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'id_jam_kerja' => $id_jam_kerja,
            'model' => $model,
        ]);
    }


    public function actionShift($id_jam_kerja = null)
    {
        $model = new JadwalKerja();
        if ($id_jam_kerja == null) {
            throw new \yii\web\NotFoundHttpException('membutuhkan data jam kerja yang valid');
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                // Hapus data shift lama untuk jam kerja ini (opsional)
                JadwalKerja::deleteAll(['nama_hari' => $model->nama_hari, 'id_jam_kerja' => $id_jam_kerja]);
                // Siapkan data untuk batch insert
                $data = [];
                if (is_array($model->shift_sehari)) {
                    foreach ($model->shift_sehari as $shift) {
                        $data[] = [
                            'id_jam_kerja' => $id_jam_kerja,
                            'id_shift_kerja' => $shift,
                            'nama_hari' => $model->nama_hari
                        ];
                    }
                }
                // dd(!empty($data));

                // Lakukan batch insert
                if (!empty($data)) {
                    $result = Yii::$app->db->createCommand()
                        ->batchInsert(JadwalKerja::tableName(), [
                            'id_jam_kerja',
                            'id_shift_kerja',
                            'nama_hari'
                        ], $data)
                        ->execute();

                    if ($result) {
                        Yii::$app->session->setFlash('success', 'Data berhasil disimpan');
                    } else {
                        Yii::$app->session->setFlash('error', 'Gagal menyimpan data');
                    }
                }

                return $this->redirect(['/jam-kerja/view', 'id_jam_kerja' => $id_jam_kerja]);
            }
        } else {
            $model->loadDefaultValues();
        }
    }

    /**
     * Updates an existing JadwalKerja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_jadwal_kerja Id Jadwal Kerja
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_jadwal_kerja)
    {
        $model = $this->findModel($id_jadwal_kerja);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['/jam-kerja/view', 'id_jam_kerja' => $model->id_jam_kerja]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing JadwalKerja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_jadwal_kerja Id Jadwal Kerja
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_jadwal_kerja)
    {
        $model = $this->findModel($id_jadwal_kerja);
        $model->delete();

        return $this->redirect(['/jam-kerja/view', 'id_jam_kerja' => $model->id_jam_kerja]);
    }

    /**
     * Finds the JadwalKerja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_jadwal_kerja Id Jadwal Kerja
     * @return JadwalKerja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_jadwal_kerja)
    {
        if (($model = JadwalKerja::findOne(['id_jadwal_kerja' => $id_jadwal_kerja])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
