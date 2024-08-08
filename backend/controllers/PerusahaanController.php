<?php

namespace backend\controllers;

use backend\models\BagianSearch;
use backend\models\Perusahaan;
use backend\models\PerusahaanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PerusahaanController implements the CRUD actions for Perusahaan model.
 */
class PerusahaanController extends Controller
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
     * Lists all Perusahaan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PerusahaanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Perusahaan model.
     * @param int $id_perusahaan Id Perusahaan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_perusahaan)
    {

        $perusahaanSearch = new BagianSearch();
        $perusahaanSearch->id_perusahaan = $id_perusahaan;
        $perusahaanProvider = $perusahaanSearch->search($this->request->queryParams);



        return $this->render('view', [
            'perusahaanSearch' => $perusahaanSearch,
            'perusahaanProvider' => $perusahaanProvider,
            'model' => $this->findModel($id_perusahaan),
        ]);
    }

    /**
     * Creates a new Perusahaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Perusahaan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_perusahaan' => $model->id_perusahaan]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Perusahaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_perusahaan Id Perusahaan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_perusahaan)
    {
        $model = $this->findModel($id_perusahaan);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_perusahaan' => $model->id_perusahaan]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Perusahaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_perusahaan Id Perusahaan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_perusahaan)
    {
        $this->findModel($id_perusahaan)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Perusahaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_perusahaan Id Perusahaan
     * @return Perusahaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_perusahaan)
    {
        if (($model = Perusahaan::findOne(['id_perusahaan' => $id_perusahaan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
