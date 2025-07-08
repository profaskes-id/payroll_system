<?php

namespace backend\controllers;

use backend\models\Pengumuman;
use backend\models\PengumumanSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PengumumanController implements the CRUD actions for Pengumuman model.
 */
class PengumumanController extends Controller
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
     * Lists all Pengumuman models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PengumumanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pengumuman model.
     * @param int $id_pengumuman Id Pengumuman
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_pengumuman)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_pengumuman),
        ]);
    }

    /**
     * Creates a new Pengumuman model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Pengumuman();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->dibuat_pada = date('Y-m-d H:i:s');
                $model->update_pada = null;
                $model->dibuat_oleh = Yii::$app->user->identity->id;
                $model->save();
                return $this->redirect(['view', 'id_pengumuman' => $model->id_pengumuman]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Pengumuman model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_pengumuman Id Pengumuman
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_pengumuman)
    {
        $model = $this->findModel($id_pengumuman);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_pengumuman' => $model->id_pengumuman]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Pengumuman model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pengumuman Id Pengumuman
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_pengumuman)
    {
        $this->findModel($id_pengumuman)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pengumuman model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pengumuman Id Pengumuman
     * @return Pengumuman the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pengumuman)
    {
        if (($model = Pengumuman::findOne(['id_pengumuman' => $id_pengumuman])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
