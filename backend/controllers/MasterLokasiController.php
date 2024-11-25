<?php

namespace backend\controllers;

use backend\models\MasterLokasi;
use backend\models\MasterLokasiSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MasterLokasiController implements the CRUD actions for MasterLokasi model.
 */
class MasterLokasiController extends Controller
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
                                return $user->can('admin') && $user->can('super_admin');
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all MasterLokasi models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterLokasiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterLokasi model.
     * @param int $id_master_lokasi Id Master Lokasi
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_master_lokasi)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_master_lokasi),
        ]);
    }

    /**
     * Creates a new MasterLokasi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterLokasi();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Data Master Lokasi Berhasil Disimpan');
                } else {
                    Yii::$app->session->setFlash('error', 'Data Master Lokasi Gagal Disimpan');
                }
                return $this->redirect(['view', 'id_master_lokasi' => $model->id_master_lokasi]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MasterLokasi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_master_lokasi Id Master Lokasi
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_master_lokasi)
    {
        $model = $this->findModel($id_master_lokasi);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Update Data Master Lokasi Berhasil');
            } else {
                Yii::$app->session->setFlash('error', 'Update Data Master Lokasi Gagal Disimpan');
            }
            return $this->redirect(['view', 'id_master_lokasi' => $model->id_master_lokasi]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MasterLokasi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_master_lokasi Id Master Lokasi
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_master_lokasi)
    {
        $model =  $this->findModel($id_master_lokasi);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data Master Lokasi ');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal Menghapus Data Master Lokasi Gagal ');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterLokasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_master_lokasi Id Master Lokasi
     * @return MasterLokasi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_master_lokasi)
    {
        if (($model = MasterLokasi::findOne(['id_master_lokasi' => $id_master_lokasi])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
