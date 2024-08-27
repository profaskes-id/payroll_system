<?php

namespace backend\controllers;

use backend\models\JamKerjaKaryawan;
use backend\models\JamKerjaKaryawanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JamKerjaKaryawanController implements the CRUD actions for JamKerjaKaryawan model.
 */
class JamKerjaKaryawanController extends Controller
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
                    'class' => \yii\filters\AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all JamKerjaKaryawan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new JamKerjaKaryawanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JamKerjaKaryawan model.
     * @param int $id_jam_kerja_karyawan Id Jam Kerja Karyawan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_jam_kerja_karyawan)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_jam_kerja_karyawan),
        ]);
    }

    /**
     * Creates a new JamKerjaKaryawan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new JamKerjaKaryawan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_jam_kerja_karyawan' => $model->id_jam_kerja_karyawan]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing JamKerjaKaryawan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_jam_kerja_karyawan Id Jam Kerja Karyawan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_jam_kerja_karyawan)
    {
        $model = $this->findModel($id_jam_kerja_karyawan);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_jam_kerja_karyawan' => $model->id_jam_kerja_karyawan]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing JamKerjaKaryawan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_jam_kerja_karyawan Id Jam Kerja Karyawan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_jam_kerja_karyawan)
    {
        $this->findModel($id_jam_kerja_karyawan)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the JamKerjaKaryawan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_jam_kerja_karyawan Id Jam Kerja Karyawan
     * @return JamKerjaKaryawan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_jam_kerja_karyawan)
    {
        if (($model = JamKerjaKaryawan::findOne(['id_jam_kerja_karyawan' => $id_jam_kerja_karyawan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
