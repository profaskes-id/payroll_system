<?php

namespace backend\controllers;

use backend\models\Absensi;
use backend\models\IzinPulangCepat;
use backend\models\IzinPulangCepatSearch;
use PDO;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IzinPulangCepatController implements the CRUD actions for IzinPulangCepat model.
 */
class IzinPulangCepatController extends Controller
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
     * Lists all IzinPulangCepat models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new IzinPulangCepatSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single IzinPulangCepat model.
     * @param int $id_izin_pulang_cepat Id Izin Pulang Cepat
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_izin_pulang_cepat)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_izin_pulang_cepat),
        ]);
    }

    /**
     * Creates a new IzinPulangCepat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new IzinPulangCepat();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->tanggal = date('Y-m-d H:i:s');

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data Izin Pulang cepat');
                    return $this->redirect(['view', 'id_izin_pulang_cepat' => $model->id_izin_pulang_cepat]);
                } else {

                    Yii::$app->session->setFlash('error', 'Gagal Menambah  Data Izin Pulang cepat');
                    return $this->redirect(['view', 'id_izin_pulang_cepat' => $model->id_izin_pulang_cepat]);
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
     * Updates an existing IzinPulangCepat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_izin_pulang_cepat Id Izin Pulang Cepat
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_izin_pulang_cepat)
    {
        $model = $this->findModel($id_izin_pulang_cepat);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->disetujui_pada = date('Y-m-d H:i:s');
            $model->disetujui_oleh = Yii::$app->user->identity->id;
            if ($model->save()) {
                if ($model->status == Yii::$app->params['disetujui']) {
                    $absensi = Absensi::find()->where(['id_karyawan' => $model->id_karyawan, 'tanggal' => date('Y-m-d')])->one();
                    $absensi->jam_pulang = date('H:i:s');
                    if ($absensi->save()) {
                        Yii::$app->session->setFlash('success', 'Berhasil Melakuan Update Data Izin Pulang cepat');
                        return $this->redirect(['view', 'id_izin_pulang_cepat' => $model->id_izin_pulang_cepat]);
                    }
                }
            } else {

                Yii::$app->session->setFlash('error', 'Gagal Melakukan Upadte  Data Izin Pulang cepat');
                return $this->redirect(['view', 'id_izin_pulang_cepat' => $model->id_izin_pulang_cepat]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing IzinPulangCepat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_izin_pulang_cepat Id Izin Pulang Cepat
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_izin_pulang_cepat)
    {
        $model = $this->findModel($id_izin_pulang_cepat);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Berhasil Menghapus Data Izin Pulang cepat');
            return $this->redirect(['index']);
        } else {

            Yii::$app->session->setFlash('error', 'Gagal Menghapus Data Izin Pulang cepat');
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the IzinPulangCepat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_izin_pulang_cepat Id Izin Pulang Cepat
     * @return IzinPulangCepat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_izin_pulang_cepat)
    {
        if (($model = IzinPulangCepat::findOne(['id_izin_pulang_cepat' => $id_izin_pulang_cepat])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
