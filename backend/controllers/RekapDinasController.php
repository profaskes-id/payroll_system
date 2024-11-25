<?php

namespace backend\controllers;

use backend\models\PengajuanDinas;
use backend\models\RekapDinasSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RekapDinasController implements the CRUD actions for PengajuanDinas model.
 */
class RekapDinasController extends Controller
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
     * Lists all PengajuanDinas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RekapDinasSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PengajuanDinas model.
     * @param int $id_pengajuan_dinas Id Pengajuan Dinas
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_pengajuan_dinas)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_pengajuan_dinas),
        ]);
    }

    /**
     * Creates a new PengajuanDinas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanDinas();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PengajuanDinas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_pengajuan_dinas Id Pengajuan Dinas
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_pengajuan_dinas)
    {
        $model = $this->findModel($id_pengajuan_dinas);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PengajuanDinas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_pengajuan_dinas Id Pengajuan Dinas
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_pengajuan_dinas)
    {
        $model = $this->findModel($id_pengajuan_dinas);
        $files = json_decode($model->files, true);

        if ($files) {
            foreach ($files as $file) {
                if (file_exists(Yii::getAlias('@webroot') . '/' . $file)) {
                    unlink(Yii::getAlias('@webroot') . '/' . $file);
                }
            }
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PengajuanDinas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_pengajuan_dinas Id Pengajuan Dinas
     * @return PengajuanDinas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_pengajuan_dinas)
    {
        if (($model = PengajuanDinas::findOne(['id_pengajuan_dinas' => $id_pengajuan_dinas])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
