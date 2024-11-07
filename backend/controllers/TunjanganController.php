<?php

namespace backend\controllers;

use backend\models\Tunjangan;
use backend\models\TunjanganSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TunjanganController implements the CRUD actions for Tunjangan model.
 */
class TunjanganController extends Controller
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
                    'class' => \yii\filters\AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Tunjangan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TunjanganSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tunjangan model.
     * @param int $id_tunjangan Id Tunjangan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_tunjangan)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_tunjangan),
        ]);
    }

    /**
     * Creates a new Tunjangan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Tunjangan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Data berhasil disimpan');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Data gagal disimpan');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tunjangan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_tunjangan Id Tunjangan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_tunjangan)
    {
        $model = $this->findModel($id_tunjangan);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil diperbarui');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Data gagal diperbarui');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tunjangan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_tunjangan Id Tunjangan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_tunjangan)
    {
        $model = $this->findModel($id_tunjangan);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus');
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Data gagal dihapus');
        }
    }

    /**
     * Finds the Tunjangan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_tunjangan Id Tunjangan
     * @return Tunjangan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_tunjangan)
    {
        if (($model = Tunjangan::findOne(['id_tunjangan' => $id_tunjangan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
