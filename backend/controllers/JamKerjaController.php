<?php

namespace backend\controllers;

use backend\models\JamKerja;
use backend\models\JamKerjaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JamKerjaController implements the CRUD actions for JamKerja model.
 */
class JamKerjaController extends Controller
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
     * Lists all JamKerja models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new JamKerjaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JamKerja model.
     * @param int $id_jam_kerja Id Jam Kerja
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_jam_kerja)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_jam_kerja),
        ]);
    }

    /**
     * Creates a new JamKerja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new JamKerja();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_jam_kerja' => $model->id_jam_kerja]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing JamKerja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_jam_kerja Id Jam Kerja
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_jam_kerja)
    {
        $model = $this->findModel($id_jam_kerja);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_jam_kerja' => $model->id_jam_kerja]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing JamKerja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_jam_kerja Id Jam Kerja
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_jam_kerja)
    {
        $this->findModel($id_jam_kerja)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the JamKerja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_jam_kerja Id Jam Kerja
     * @return JamKerja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_jam_kerja)
    {
        if (($model = JamKerja::findOne(['id_jam_kerja' => $id_jam_kerja])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
