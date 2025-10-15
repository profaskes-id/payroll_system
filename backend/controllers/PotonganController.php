<?php

namespace backend\controllers;

use backend\models\Potongan;
use backend\models\PotonganSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PotonganController implements the CRUD actions for Potongan model.
 */
class PotonganController extends Controller
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
     * Lists all Potongan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PotonganSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSelectedPotongan($id_potongan)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = Potongan::findOne($id_potongan);

        if ($model) {
            return [
                'jumlah' => $model->jumlah,
                'satuan' => $model->satuan, // asumsi ini "rupiah" atau "persen"
            ];
        }

        return ['error' => 'Data tidak ditemukan'];
    }


    public function actionCreateAjax()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new Potongan();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return [
                    'success' => true,
                    'message' => 'Potongan berhasil disimpan.',
                    'id' => $model->id_potongan,
                    'text' => $model->nama_potongan
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal menyimpan potongan.',
                    'errors' => $model->getErrors(),
                ];
            }
        }

        // 🛑 Tambahkan ini agar selalu return
        return [
            'success' => false,
            'message' => 'Data tidak terkirim.',
            'errors' => ['request' => ['Data POST tidak diterima atau tidak valid']],
        ];
    }


    /**
     * Displays a single Potongan model.
     * @param int $id_potongan Id Potongan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_potongan)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_potongan),
        ]);
    }

    /**
     * Creates a new Potongan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Potongan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Data berhasil disimpan');
                    return $this->redirect(['potongan-detail/index']);
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
     * Updates an existing Potongan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_potongan Id Potongan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_potongan)
    {
        $model = $this->findModel($id_potongan);

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
     * Deletes an existing Potongan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_potongan Id Potongan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_potongan)
    {
        $model = $this->findModel($id_potongan);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus');
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Data gagal dihapus');
        }
    }

    /**
     * Finds the Potongan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_potongan Id Potongan
     * @return Potongan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_potongan)
    {
        if (($model = Potongan::findOne(['id_potongan' => $id_potongan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
