<?php

namespace backend\controllers;

use backend\models\Potongan;
use backend\models\PotonganDetail;
use backend\models\PotonganDetailSearch;
use Yii;
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
        $potongan  = new Potongan();



        $id_karyawan = Yii::$app->request->get('id_karyawan');

        // Jika id_karyawan tidak ada di parameter GET, cek di model pencarian
        if (!$id_karyawan && isset($searchModel->id_karyawan)) {
            $id_karyawan = $searchModel->id_karyawan;
        }


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id_karyawan' => $id_karyawan,
            'potongan' => $potongan

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
            if ($model->load($this->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Data berhasil ditambahkan');
                    return $this->redirect(['/tunjangan-potongan/index#custom-tabs-one-potongan']);
                } else {
                    Yii::$app->session->setFlash('error', 'Data gagal ditambahkan');
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
     * Updates an existing PotonganDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_potongan_detail Id Potongan Detail
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_potongan_detail)
    {
        $model = $this->findModel($id_potongan_detail);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil diperbarui');
                return $this->redirect(['/tunjangan-potongan/index#custom-tabs-one-potongan']);
            } else {
                Yii::$app->session->setFlash('error', 'Data gagal diperbarui');
            }
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
        $model = $this->findModel($id_potongan_detail);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus');
            return $this->redirect(['/tunjangan-potongan/index']);
        } else {
            Yii::$app->session->setFlash('error', 'Data gagal dihapus');
        }
        return $this->redirect(['/tunjangan-potongan/index']);
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
