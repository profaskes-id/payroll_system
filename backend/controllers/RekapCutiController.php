<?php

namespace backend\controllers;

use backend\models\RekapCuti;
use backend\models\RekapCutiSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RekapCutiController implements the CRUD actions for RekapCuti model.
 */
class RekapCutiController extends Controller
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
                                return $user->can('admin') && $user->can('super_admin');
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all RekapCuti models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RekapCutiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RekapCuti model.
     * @param int $id_rekap_cuti Id Rekap Cuti
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_rekap_cuti)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_rekap_cuti),
        ]);
    }

    /**
     * Creates a new RekapCuti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new RekapCuti();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_rekap_cuti' => $model->id_rekap_cuti]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RekapCuti model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_rekap_cuti Id Rekap Cuti
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_rekap_cuti)
    {
        $model = $this->findModel($id_rekap_cuti);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_rekap_cuti' => $model->id_rekap_cuti]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RekapCuti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_rekap_cuti Id Rekap Cuti
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_rekap_cuti)
    {
        $this->findModel($id_rekap_cuti)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RekapCuti model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_rekap_cuti Id Rekap Cuti
     * @return RekapCuti the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_rekap_cuti)
    {
        if (($model = RekapCuti::findOne(['id_rekap_cuti' => $id_rekap_cuti])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
