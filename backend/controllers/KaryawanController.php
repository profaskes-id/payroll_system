<?php

namespace backend\controllers;

use amnah\yii2\user\models\Profile;
use amnah\yii2\user\models\User;
use backend\models\DataKeluargaSearch;
use backend\models\DataPekerjaanSearch;
use backend\models\Karyawan;
use backend\models\KaryawanSearch;
use backend\models\PengalamanKerjaSearch;
use backend\models\RiwayatPendidikanSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KaryawanController implements the CRUD actions for Karyawan model.
 */
class KaryawanController extends Controller
{
    /**
     * @inheritDoc
     */ public function behaviors()
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
     * Lists all Karyawan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new KaryawanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Karyawan model.
     * @param int $id_karyawan Id Karyawan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_karyawan)
    {

        $PengalamanKerjasearchModel = new PengalamanKerjaSearch();
        $PengalamanKerjasearchModel->id_karyawan = $id_karyawan;
        $pengalamankerjaProvider = $PengalamanKerjasearchModel->search($this->request->queryParams);

        $riwayatSearch = new RiwayatPendidikanSearch();
        $riwayatSearch->id_karyawan = $id_karyawan;
        $riwayarProvider = $riwayatSearch->search($this->request->queryParams);


        $keluargasearchModel = new DataKeluargaSearch();
        $keluargasearchModel->id_karyawan = $id_karyawan;
        $dataKeluargaProvider = $keluargasearchModel->search($this->request->queryParams);


        $PekerjaansearchModel = new DataPekerjaanSearch();
        $PekerjaansearchModel->id_karyawan = $id_karyawan;
        $pekrjaandataProvider = $PekerjaansearchModel->search($this->request->queryParams);



        return $this->render('view', [
            'PekerjaansearchModel' => $PekerjaansearchModel,
            'pekrjaandataProvider' => $pekrjaandataProvider,
            'keluargasearchModel' => $keluargasearchModel,
            'dataKeluargaProvider' => $dataKeluargaProvider,
            'riwayatSearch' => $riwayatSearch,
            'riwayarProvider' => $riwayarProvider,
            'PengalamanKerjasearchModel' => $PengalamanKerjasearchModel,
            'pengalamankerjaProvider' => $pengalamankerjaProvider,
            'model' => $this->findModel($id_karyawan),
        ]);
    }

    /**
     * Creates a new Karyawan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */



    public function actionInvite($id_karyawan)
    {

        $model = $this->findModel($id_karyawan);
        $user = new User();
        $user->email = $model->email;

        $user->newPassword = $model->nomer_identitas;
        $user->setRegisterAttributes(2, 1);

        // dd($user);
        if ($user->save()) {
            Yii::$app->session->setFlash('success', 'Berhasil Membuat Data');
            $profil = new Profile();
            $profil->user_id = $user->id;
            $profil->full_name = $model->nama;
            if ($profil->save()) {

                $msgToCheck = $this->renderPartial('@backend/views/karyawan/email_verif', compact('model'));

                $sendMsgToCheck = Yii::$app->mailer->compose()
                    ->setTo($user->email)
                    ->setSubject('Akses Akun Trial profaskes')
                    ->setHtmlBody($msgToCheck);
                if ($sendMsgToCheck->send()) {
                    Yii::$app->session->setFlash(
                        'success',
                        'Email Telah Berhasil Terkirim kepada ' . $user->email
                    );
                }

                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Terjadi kesalahan saat menyimpan data.');
                return $this->redirect(['index']);
            }
        } else {
            Yii::$app->session->setFlash('error', 'Terjadi kesalahan saat menyimpan data.');
            return $this->redirect(['index']);
        }
    }



    public function actionCreate()
    {
        $model = new Karyawan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_karyawan' => $model->id_karyawan]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Karyawan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_karyawan Id Karyawan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_karyawan)
    {
        $model = $this->findModel($id_karyawan);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_karyawan' => $model->id_karyawan]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Karyawan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_karyawan Id Karyawan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_karyawan)
    {
        $this->findModel($id_karyawan)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Karyawan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_karyawan Id Karyawan
     * @return Karyawan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_karyawan)
    {
        if (($model = Karyawan::findOne(['id_karyawan' => $id_karyawan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
