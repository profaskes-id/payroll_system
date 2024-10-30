<?php

namespace backend\controllers;

use backend\models\PotonganDetail;
use backend\models\PotonganDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PotonganDetailController implements the CRUD actions for PotonganDetail model.
 */
class PotonganDetailController extends Controller
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
     * Lists all PotonganDetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PotonganDetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PotonganDetail model.
     * @param int $id_potongan_detail Id Potongan Detail
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_potongan_detail)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_potongan_detail),
        ]);
    }

    /**
     * Creates a new PotonganDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PotonganDetail();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_potongan_detail' => $model->id_potongan_detail]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PotonganDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_potongan_detail Id Potongan Detail
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_potongan_detail)
    {
        $model = $this->findModel($id_potongan_detail);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_potongan_detail' => $model->id_potongan_detail]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PotonganDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_potongan_detail Id Potongan Detail
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_potongan_detail)
    {
        $this->findModel($id_potongan_detail)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PotonganDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_potongan_detail Id Potongan Detail
     * @return PotonganDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_potongan_detail)
    {
        if (($model = PotonganDetail::findOne(['id_potongan_detail' => $id_potongan_detail])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
