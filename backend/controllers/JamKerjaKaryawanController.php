<?php

namespace backend\controllers;

use backend\models\JamKerjaKaryawan;
use backend\models\JamKerjaKaryawanSearch;
use backend\models\Karyawan;
use backend\models\KaryawanSearch;
use Yii;
use yii\data\ArrayDataProvider;
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
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [

                        [
                            'allow' => true,
                            'roles' => ['@'], // Allow authenticated users
                            'matchCallback' => function ($rule, $action) {
                                $user = Yii::$app->user;
                                return $user->can('admin') || $user->can('super_admin');
                            },
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
        $searchModel = new KaryawanSearch();
        $dataProvider = $searchModel->searchJadwalKerja($this->request->queryParams);


        if (\Yii::$app->request->isPost) {
            $id_karyawan = Yii::$app->request->post('KaryawanSearch')['id_karyawan'];
            $dataProvider = $searchModel->searchJadwalKerjaID($id_karyawan);
        }

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
    public function actionView($id_karyawan)
    {
        $model = JamKerjaKaryawan::find()->where(['id_karyawan' => $id_karyawan])->one();

        return $this->render('view', [
            'model' => $model,
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
            if ($model->load($this->request->post())) {

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Jam kerja karyawan ditambahkan');
                    return $this->redirect(['view', 'id_karyawan' => $model->id_karyawan]);
                }
                return $this->redirect(['index']);
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

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Jam kerja karyawan diubah');
            } else {
                Yii::$app->session->setFlash('error', 'Jam kerja karyawan gagal diubah');
            }
            return $this->redirect(['view', 'id_karyawan' => $model->id_karyawan]);
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


    public function actionSearch()
    {
        $id_karyawan = Yii::$app->request->get('KaryawanSearch')['id_karyawan'];
        $model = JamKerjaKaryawan::find()->where(['id_karyawan' => $id_karyawan])->one();

        $dataProvider = new ArrayDataProvider([
            'models' => $model,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
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
