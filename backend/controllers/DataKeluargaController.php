<?php

namespace backend\controllers;

use backend\models\DataKeluarga;
use backend\models\DataKeluargaSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DataKeluargaController implements the CRUD actions for DataKeluarga model.
 */
class DataKeluargaController extends Controller
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
                                return $user->can('admin') && $user->can('super_admin');
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all DataKeluarga models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DataKeluargaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DataKeluarga model.
     * @param int $id_data_keluarga Id Data Keluarga
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_data_keluarga)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_data_keluarga),
        ]);
    }

    /**
     * Creates a new DataKeluarga model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new DataKeluarga();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Menambahkan Data Keluarga');
                    return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
                }
                Yii::$app->session->setFlash('error', 'Gagal Menambahkan Data Keluarga');
                return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DataKeluarga model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_data_keluarga Id Data Keluarga
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_data_keluarga)
    {
        $model = $this->findModel($id_data_keluarga);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Berhasil Melakukan Update Data Keluarga');
                return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
            }
            Yii::$app->session->setFlash('error', 'Gagal Melakukan Update Data Keluarga');
            return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DataKeluarga model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_data_keluarga Id Data Keluarga
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_data_keluarga)
    {
        $model = $this->findModel($id_data_keluarga);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menambahkan Data Keluarga');
            return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
        }
        Yii::$app->session->setFlash('error', 'Gagal Menambahkan Data Keluarga');
        return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);

        return $this->redirect(['/karyawan/view', 'id_karyawan' => $model->id_karyawan]);
    }

    /**
     * Finds the DataKeluarga model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_data_keluarga Id Data Keluarga
     * @return DataKeluarga the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_data_keluarga)
    {
        if (($model = DataKeluarga::findOne(['id_data_keluarga' => $id_data_keluarga])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
