<?php

namespace backend\controllers;

use backend\models\MasterCuti;
use backend\models\MasterCutiSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MasterCutiController implements the CRUD actions for MasterCuti model.
 */
class MasterCutiController extends Controller
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
                            'roles' => ['super_admin'], // Pastikan peran ini ada dalam RBAC Anda
                            'matchCallback' => function ($rule, $action) {
                                return Yii::$app->user->can('super_admin'); // Pastikan Anda sudah mengonfigurasi permission ini di RBAC
                            },
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
     * Lists all MasterCuti models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterCutiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterCuti model.
     * @param int $id_master_cuti Id Master Cuti
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_master_cuti)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_master_cuti),
        ]);
    }

    /**
     * Creates a new MasterCuti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterCuti();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_master_cuti' => $model->id_master_cuti]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MasterCuti model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_master_cuti Id Master Cuti
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_master_cuti)
    {
        $model = $this->findModel($id_master_cuti);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_master_cuti' => $model->id_master_cuti]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MasterCuti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_master_cuti Id Master Cuti
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_master_cuti)
    {
        $this->findModel($id_master_cuti)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterCuti model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_master_cuti Id Master Cuti
     * @return MasterCuti the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_master_cuti)
    {
        if (($model = MasterCuti::findOne(['id_master_cuti' => $id_master_cuti])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
