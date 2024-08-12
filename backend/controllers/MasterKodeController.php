<?php

namespace backend\controllers;

use backend\models\MasterKode;
use backend\models\MasterKodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MasterKodeController implements the CRUD actions for MasterKode model.
 */
class MasterKodeController extends Controller
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
                            'roles' => ['@'],
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
     * Lists all MasterKode models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterKodeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        // $dataProvider->pagination->pageSize = 100;


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterKode model.
     * @param string $nama_group Nama Group
     * @param string $kode Kode
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($nama_group, $kode)
    {
        return $this->render('view', [
            'model' => $this->findModel($nama_group, $kode),
        ]);
    }

    /**
     * Creates a new MasterKode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterKode();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'nama_group' => $model->nama_group, 'kode' => $model->kode]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MasterKode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $nama_group Nama Group
     * @param string $kode Kode
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($nama_group, $kode)
    {
        $model = $this->findModel($nama_group, $kode);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'nama_group' => $model->nama_group, 'kode' => $model->kode]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MasterKode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $nama_group Nama Group
     * @param string $kode Kode
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($nama_group, $kode)
    {
        $this->findModel($nama_group, $kode)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterKode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $nama_group Nama Group
     * @param string $kode Kode
     * @return MasterKode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($nama_group, $kode)
    {
        if (($model = MasterKode::findOne(['nama_group' => $nama_group, 'kode' => $kode])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
