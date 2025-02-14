<?php

namespace backend\controllers;

use backend\models\AtasanKaryawan;
use backend\models\AtasanKaryawanSearch;
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
        $searchModel = new KaryawanSearch();
        $dataProvider = $searchModel->searchAtasanKaryawan($this->request->queryParams);


        if (Yii::$app->request->isPost) {
            $id_karyawan = Yii::$app->request->post('KaryawanSearch')['id_karyawan'];
            $dataProvider = $searchModel->searchAtasanKaryawanID($id_karyawan);
        }



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
    public function actionView($id_karyawan)
    {
        $model = AtasanKaryawan::find()->where(['id_karyawan' => $id_karyawan])->one();

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

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->di_setting_oleh = Yii::$app->user->identity->id;
                $model->di_setting_pada = date('Y-m-d H:i:s');
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Menambahkan Data ');
                    return $this->redirect(['index']);
                }
                Yii::$app->session->setFlash('error', 'Gagal Menambahkan Data ');
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
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


    protected function findModel($id_atasan_karyawan)
    {
        if (($model = AtasanKaryawan::findOne(['id_atasan_karyawan' => $id_atasan_karyawan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
