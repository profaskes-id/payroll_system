<?php

namespace backend\controllers;

use backend\models\PengajuanShift;
use backend\models\PengajuanShiftSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * PengajuanShiftController implements the CRUD actions for PengajuanShift model.
 */
class PengajuanShiftController extends Controller
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
     * Lists all PengajuanShift models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PengajuanShiftSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PengajuanShift model.
     * @param int $id_pengajuan_shift Id Pengajuan Shift
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_pengajuan_shift)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_pengajuan_shift),
        ]);
    }

    /**
     * Creates a new PengajuanShift model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanShift();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_pengajuan_shift' => $model->id_pengajuan_shift]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PengajuanShift model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_pengajuan_shift Id Pengajuan Shift
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_pengajuan_shift)
    {
        $model = $this->findModel($id_pengajuan_shift);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_pengajuan_shift' => $model->id_pengajuan_shift]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PengajuanShift model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pengajuan_shift Id Pengajuan Shift
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_pengajuan_shift)
    {
        $this->findModel($id_pengajuan_shift)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PengajuanShift model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pengajuan_shift Id Pengajuan Shift
     * @return PengajuanShift the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pengajuan_shift)
    {
        if (($model = PengajuanShift::findOne(['id_pengajuan_shift' => $id_pengajuan_shift])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
