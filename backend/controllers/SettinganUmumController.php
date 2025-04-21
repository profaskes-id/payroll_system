<?php

namespace backend\controllers;

use backend\models\SettinganUmum;
use backend\models\SettinganUmumSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * SettinganUmumController implements the CRUD actions for SettinganUmum model.
 */
class SettinganUmumController extends Controller
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
                                // Check if the user does  have the 'admin' or 'super admin' role
                                return $user->can('admin') || $user->can('super_admin');
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all SettinganUmum models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SettinganUmumSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SettinganUmum model.
     * @param int $id_settingan_umum Id Settingan Umum
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_settingan_umum)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_settingan_umum),
        ]);
    }

    /**
     * Creates a new SettinganUmum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SettinganUmum();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_settingan_umum' => $model->id_settingan_umum]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SettinganUmum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_settingan_umum Id Settingan Umum
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_settingan_umum)
    {
        $model = $this->findModel($id_settingan_umum);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_settingan_umum' => $model->id_settingan_umum]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SettinganUmum model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_settingan_umum Id Settingan Umum
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_settingan_umum)
    {
        $this->findModel($id_settingan_umum)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SettinganUmum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_settingan_umum Id Settingan Umum
     * @return SettinganUmum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_settingan_umum)
    {
        if (($model = SettinganUmum::findOne(['id_settingan_umum' => $id_settingan_umum])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
