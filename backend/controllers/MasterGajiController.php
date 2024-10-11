<?php

namespace backend\controllers;

use backend\models\MasterGaji;
use backend\models\MasterGajiSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MasterGajiController implements the CRUD actions for MasterGaji model.
 */
class MasterGajiController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['super_admin'], // Pastikan peran ini ada dalam RBAC Anda
                            'matchCallback' => function ($rule, $action) {
                                return Yii::$app->user->can('super_admin'); // Pastikan Anda sudah mengonfigurasi permission ini di RBAC
                            },
                        ]
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all MasterGaji models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterGajiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterGaji model.
     * @param int $id_gaji Id Gaji
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_gaji)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_gaji),
        ]);
    }

    /**
     * Creates a new MasterGaji model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterGaji();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Data Gaji Berhasil Ditambahkan');
                } else {
                    Yii::$app->session->setFlash('error', 'Data Gaji Gagal Ditambahkan, Periksa anda telah menginputkan data yang benar');
                }
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
     * Updates an existing MasterGaji model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_gaji Id Gaji
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_gaji)
    {
        $model = $this->findModel($id_gaji);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Gaji Berhasil Ditambahkan');
            } else {
                Yii::$app->session->setFlash('error', 'Data Gaji Gagal Ditambahkan, Periksa anda telah menginputkan data yang benar');
            }
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MasterGaji model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_gaji Id Gaji
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_gaji)
    {
        $model = $this->findModel($id_gaji);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Data Gaji Berhasil Dihapus');
        } else {
            Yii::$app->session->setFlash('error', 'Data Gaji Gagal Dihapus');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterGaji model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_gaji Id Gaji
     * @return MasterGaji the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_gaji)
    {
        if (($model = MasterGaji::findOne(['id_gaji' => $id_gaji])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
