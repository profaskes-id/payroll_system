<?php

namespace backend\controllers;

use backend\models\Tunjangan;
use backend\models\TunjanganDetail;
use backend\models\TunjanganDetailSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TunjanganDetailController implements the CRUD actions for TunjanganDetail model.
 */
class TunjanganDetailController extends Controller
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
     * Lists all TunjanganDetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TunjanganDetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $tunjangan = new Tunjangan();

        $id_karyawan = Yii::$app->request->get('id_karyawan');

        // Jika id_karyawan tidak ada di parameter GET, cek di model pencarian
        if (!$id_karyawan && isset($searchModel->id_karyawan)) {
            $id_karyawan = $searchModel->id_karyawan;
        }


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id_karyawan' => $id_karyawan,
            'tunjangan' => $tunjangan
        ]);
    }

    /**
     * Displays a single TunjanganDetail model.
     * @param int $id_tunjangan_detail Id Tunjangan Detail
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_tunjangan_detail)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_tunjangan_detail),
        ]);
    }

    /**
     * Creates a new TunjanganDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TunjanganDetail();

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
     * Updates an existing TunjanganDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_tunjangan_detail Id Tunjangan Detail
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_tunjangan_detail)
    {
        $model = $this->findModel($id_tunjangan_detail);

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
     * Deletes an existing TunjanganDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_tunjangan_detail Id Tunjangan Detail
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_tunjangan_detail)
    {
        $model = $this->findModel($id_tunjangan_detail);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Data berhasil diperbarui');
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Data gagal diperbarui');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the TunjanganDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_tunjangan_detail Id Tunjangan Detail
     * @return TunjanganDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_tunjangan_detail)
    {
        if (($model = TunjanganDetail::findOne(['id_tunjangan_detail' => $id_tunjangan_detail])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
