<?php

namespace backend\controllers;

use backend\models\HariLibur;
use backend\models\HariLiburSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HariLiburController implements the CRUD actions for HariLibur model.
 */
class HariLiburController extends Controller
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
     * Lists all HariLibur models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new HariLiburSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HariLibur model.
     * @param int $id_hari_libur Id Hari Libur
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_hari_libur)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_hari_libur),
        ]);
    }

    /**
     * Creates a new HariLibur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new HariLibur();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_hari_libur' => $model->id_hari_libur]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HariLibur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_hari_libur Id Hari Libur
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_hari_libur)
    {
        $model = $this->findModel($id_hari_libur);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_hari_libur' => $model->id_hari_libur]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HariLibur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_hari_libur Id Hari Libur
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_hari_libur)
    {
        $this->findModel($id_hari_libur)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HariLibur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_hari_libur Id Hari Libur
     * @return HariLibur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_hari_libur)
    {
        if (($model = HariLibur::findOne(['id_hari_libur' => $id_hari_libur])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
