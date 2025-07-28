<?php

namespace backend\controllers;

use backend\models\MasterKode;
use backend\models\MasterKodeSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JabatanController implements the CRUD actions for MasterKode model.
 */
class JabatanController extends Controller
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

    /**
     * Lists all MasterKode models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterKodeSearch();
        $searchModel->nama_group = 'jabatan';
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterKode model.
     * @param string $nama_group Nama Group
     * @param int $kode Kode
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
    public function actionCreate($nama_group)
    {
        $model = new MasterKode();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'nama_group' => $nama_group
        ]);
    }

    /**
     * Updates an existing MasterKode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $nama_group Nama Group
     * @param int $kode Kode
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($nama_group, $kode)
    {
        $model = $this->findModel($nama_group, $kode);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MasterKode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $nama_group Nama Group
     * @param int $kode Kode
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
     * @param int $kode Kode
     * @return MasterKode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($nama_group, $kode)
    {
        if (($model = MasterKode::findOne(['nama_group' => $nama_group, 'kode' => $kode])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
