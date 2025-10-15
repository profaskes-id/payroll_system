<?php

namespace backend\controllers;

use backend\models\JatahCutiKaryawan;
use backend\models\JatahCutiKaryawanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * JatahCutiKaryawanController implements the CRUD actions for JatahCutiKaryawan model.
 */
class JatahCutiKaryawanController extends Controller
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
     * Lists all JatahCutiKaryawan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new JatahCutiKaryawanSearch();
        $id_karyawan = Yii::$app->request->get('JatahCutiKaryawanSearch')['id_karyawan'] ?? null;
        $tahun = Yii::$app->request->get('JatahCutiKaryawanSearch')['tahun'] ?? date('Y');
        $masterCuti = Yii::$app->request->get('JatahCutiKaryawanSearch')['id_master_cuti'] ?? null;

        $dataProvider = $searchModel->search($this->request->queryParams, null,  $tahun, $masterCuti, $id_karyawan);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tahun' => $tahun,
        ]);
    }

    /**
     * Displays a single JatahCutiKaryawan model.
     * @param int $id_jatah_cuti Id Jatah Cuti
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_jatah_cuti)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_jatah_cuti),
        ]);
    }

    /**
     * Creates a new JatahCutiKaryawan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new JatahCutiKaryawan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->created_at = date('Y-m-d H:i:s');
                $model->created_by = Yii::$app->user->identity->id;
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
     * Updates an existing JatahCutiKaryawan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_jatah_cuti Id Jatah Cuti
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_jatah_cuti)
    {
        $model = $this->findModel($id_jatah_cuti);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->updated_at = date('Y-m-d H:i:s');
            $model->updated_by = Yii::$app->user->identity->id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil diubah');
                return $this->redirect(['view', 'id_jatah_cuti' => $model->id_jatah_cuti]);
            }
            Yii::$app->session->setFlash('error', 'Data gagal diubah');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing JatahCutiKaryawan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_jatah_cuti Id Jatah Cuti
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_jatah_cuti)
    {
        $this->findModel($id_jatah_cuti)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the JatahCutiKaryawan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_jatah_cuti Id Jatah Cuti
     * @return JatahCutiKaryawan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_jatah_cuti)
    {
        if (($model = JatahCutiKaryawan::findOne(['id_jatah_cuti' => $id_jatah_cuti])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
