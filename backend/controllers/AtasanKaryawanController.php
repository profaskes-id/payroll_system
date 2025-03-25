<?php

namespace backend\controllers;

use backend\models\AtasanKaryawan;
use backend\models\AtasanKaryawanSearch;
use backend\models\Karyawan;
use backend\models\KaryawanSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AtasanKaryawanController implements the CRUD actions for AtasanKaryawan model.
 */
class AtasanKaryawanController extends Controller
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
     * Lists all AtasanKaryawan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AtasanKaryawanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AtasanKaryawan model.
     * @param int $id_atasan_karyawan Id Atasan Karyawan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_atasan_karyawan)
    {
        $model = $this->findModel($id_atasan_karyawan);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new AtasanKaryawan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AtasanKaryawan();
        $dataKaryawan = Karyawan::find()->select(['id_karyawan', 'nama', 'is_atasan'])->where(['is_aktif' => 1])->orderBy(['nama' => SORT_ASC])->asArray()->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->di_setting_oleh = Yii::$app->user->identity->id;
                $model->di_setting_pada = date('Y-m-d H:i:s');
                // Find the selected atasan

                if (($this->request->get('id_atasan'))) {
                    $model->id_atasan = intval($this->request->get('id_atasan'));
                }



                $atasan = Karyawan::findOne(['id_karyawan' => $model->atasan]);
                if ($atasan === null) {
                    Yii::$app->session->setFlash('error', 'Atasan tidak ditemukan.');
                    return $this->redirect(['index']);
                }

                // Mark the selected atasan as an official atasan
                $atasan->is_atasan = 1;

                // Wrap in transaction for atomic save
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // Save both models in a transaction
                    if ($model->save() && $atasan->save()) {
                        // Commit the transaction if both save operations are successful
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Berhasil Menambahkan Data');
                        // Redirect with id_atasan
                        return $this->redirect(['create', 'id_atasan' => $model->id_atasan]);
                    } else {
                        // Rollback if either save operation fails
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Gagal Menambahkan Data');
                        return $this->redirect(['index']);
                    }
                } catch (\Exception $e) {
                    // Rollback if an exception occurs
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }



        if (empty(Yii::$app->request->get()['id_atasan'])) {
            $atasanData = [];
        } else {
            $atasanData = AtasanKaryawan::find()
                ->select([
                    'atasan_karyawan.*',
                    'karyawan_atasan.nama AS nama_atasan', // Alias untuk nama atasan
                    'karyawan.nama AS nama_karyawan', // Alias untuk nama karyawan
                    'master_lokasi.label AS id_master_lokasi',
                ])
                ->where(['id_atasan' => Yii::$app->request->get()['id_atasan']])
                ->leftJoin('karyawan AS karyawan_atasan', 'atasan_karyawan.id_atasan = karyawan_atasan.id_karyawan') // Join untuk atasan
                ->leftJoin('karyawan', 'atasan_karyawan.id_karyawan = karyawan.id_karyawan') // Join untuk karyawan
                ->leftJoin('master_lokasi', 'atasan_karyawan.id_master_lokasi = master_lokasi.id_master_lokasi')
                ->asArray()
                ->orderBy(['atasan_karyawan.id_karyawan' => SORT_ASC])
                ->all();
        }




        return $this->render('create', [
            'model' => $model,
            'dataKaryawan' => $dataKaryawan,
            'atasanData' => $atasanData
        ]);
    }

    /**
     * Updates an existing AtasanKaryawan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_atasan_karyawan Id Atasan Karyawan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_atasan_karyawan)
    {
        $model = $this->findModel($id_atasan_karyawan);
        $dataKaryawan = Karyawan::find()->select(['id_karyawan', 'nama', 'is_atasan'])->asArray()->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Berhasil Melakukan Update Data ');
                return $this->redirect(['index']);
            }
            Yii::$app->session->setFlash('error', 'Gagal Melakukan Update Data ');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'dataKaryawan' => $dataKaryawan
        ]);
    }

    /**
     * Deletes an existing AtasanKaryawan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_atasan_karyawan Id Atasan Karyawan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_atasan_karyawan)
    {
        $model = $this->findModel($id_atasan_karyawan);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data ');
            return $this->redirect(['index']);
        }

        Yii::$app->session->setFlash('error', 'Gagal Menghapus Data ');
        return $this->redirect(['index']);
    }
    public function actionDeleteCustom($id_atasan_karyawan)
    {
        $model = $this->findModel($id_atasan_karyawan);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data ');
            return $this->redirect(['create', 'id_atasan' => $model->id_atasan]);
        }

        Yii::$app->session->setFlash('error', 'Gagal Menghapus Data ');
    }


    protected function findModel($id_atasan_karyawan)
    {
        if (($model = AtasanKaryawan::findOne(['id_atasan_karyawan' => $id_atasan_karyawan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
