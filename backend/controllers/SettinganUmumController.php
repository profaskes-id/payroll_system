<?php

namespace backend\controllers;

use backend\models\MasterKode;
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
        $model = new MasterKode();

        $tanggal_cut_of = MasterKode::find()->where(['nama_group' => Yii::$app->params["tanggal-cut-of"]])->one();
        $potongan_persenan_wfh = MasterKode::find()->where(['nama_group' => Yii::$app->params['potongan-persen-wfh']])->one();
        $toleransi_keterlambatan = MasterKode::find()->where(['nama_group' => Yii::$app->params['toleransi-keterlambatan']])->one();
        $batas_deviasi_absensi = MasterKode::find()->where(['nama_group' => Yii::$app->params['batas-deviasi-absensi']])->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tanggal_cut_of' => $tanggal_cut_of,
            'potongan_persenan_wfh' => $potongan_persenan_wfh,
            'toleransi_keterlambatan' => $toleransi_keterlambatan,
            'batas_deviasi_absensi' => $batas_deviasi_absensi,
            'model' => $model
        ]);
    }

    public function actionEditCutoff()
    {

        $model = MasterKode::find()->where(['nama_group' => Yii::$app->params["tanggal-cut-of"]])->one();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->save()) {
                return ['success' => true];
            } else {
                return [
                    'success' => false,
                    'form' => $this->renderAjax('_form', ['model' => $model]) // Buat partial view _form.php jika belum ada
                ];
            }
        }

        // Untuk non-AJAX request
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionEditWfhPersen()
    {
        $model = MasterKode::find()->where(['nama_group' => 'potongan-persen-wfh'])->one();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->save()) {
                return ['success' => true];
            } else {
                return [
                    'success' => false,
                    'form' => $this->renderAjax('_form_wfh_persen', ['model' => $model])
                ];
            }
        }

        // Untuk non-AJAX fallback (opsional)
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('edit_wfh_persen', [
            'model' => $model,
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
            if ($model->load($this->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                } else {
                    Yii::$app->session->setFlash('error', 'Data gagal disimpan.');
                }
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

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
            } else {
                Yii::$app->session->setFlash('error', 'Data gagal disimpan.');
            }
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
