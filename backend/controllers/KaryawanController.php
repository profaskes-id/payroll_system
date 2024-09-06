<?php

namespace backend\controllers;

use amnah\yii2\user\models\Profile;
use amnah\yii2\user\models\User;
use backend\models\DataKeluargaSearch;
use backend\models\DataPekerjaanSearch;
use backend\models\Karyawan;
use backend\models\KaryawanSearch;
use backend\models\MasterKab;
use backend\models\MasterKec;
use backend\models\PengalamanKerjaSearch;
use backend\models\RiwayatPendidikanSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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


    public function actionInvite($id_karyawan)
    {

        $model = $this->findModel($id_karyawan);
        $user = new User();
        $user->email = $model->email;

        $user->newPassword =  $id_karyawan . $model->kode_karyawan  . $model->jenis_identitas . $model->kode_jenis_kelamin;
        $user->setRegisterAttributes(2, 1);
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
            $lampiranFileKtp = UploadedFile::getInstance($model, 'ktp');
            $lampiranFileCv = UploadedFile::getInstance($model, 'cv');
            $foto = UploadedFile::getInstance($model, 'foto');
            $lampiranFileIjazah = UploadedFile::getInstance($model, 'ijazah_terakhir');
            if ($model->load($this->request->post())) {
                $lampiranFileKtp != null ? $this->saveImage($model, $lampiranFileKtp, 'ktp') : $model->ktp = null;
                $lampiranFileCv != null ? $this->saveImage($model, $lampiranFileCv, 'cv') : $model->cv = null;
                $foto != null ? $this->saveImage($model, $foto, 'foto') : $model->foto = null;
                $lampiranFileIjazah != null ? $this->saveImage($model, $lampiranFileIjazah, 'ijazah_terakhir') : $model->ijazah_terakhir = null;
                $model->kode_negara = 'indonesia';
                $model->kode_karyawan = Yii::$app->request->post('Karyawan')['kode_karyawan'];


                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Berhasil Membuat Data');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Terjadi Kesalahan saat menyimpan data');
                }
            }
        } else {
            $model->loadDefaultValues();
        }


        $nextKode = $model->generateAutoCode();
        return $this->render('create', [
            'model' => $model,
            'nextKode' => $nextKode
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
        $oldPost = $model->oldAttributes;

        if ($this->request->isPost && $model->load($this->request->post())) {

            $lampiranFileKtp = UploadedFile::getInstance($model, 'ktp');
            $lampiranFileCv = UploadedFile::getInstance($model, 'cv');
            $foto = UploadedFile::getInstance($model, 'foto');
            $lampiranFileIjazah = UploadedFile::getInstance($model, 'ijazah_terakhir');

            $data = [
                'ktp' => $lampiranFileKtp,
                'cv' => $lampiranFileCv,
                'foto' => $foto,
                'ijazah_terakhir' => $lampiranFileIjazah
            ];

            foreach ($data as $key => $value) {
                if ($value != null) {
                    $this->saveImage($model, $value, $key);
                    // Hapus gambar lama jika ada
                    if ($oldPost[$key]) {
                        $this->deleteImage($oldPost[$key]);
                    }
                } else {
                    $model->$key = $oldPost[$key];
                }
            }


            $model->save();


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
        $model =  $this->findModel($id_karyawan);
        $oldPost = $model->oldAttributes;
        $oldThumbnailKtp = $oldPost['ktp'];
        $oldThumbnailCv = $oldPost['cv'];
        $foto = $oldPost['foto'];
        $oldThumbnailIjazahTerakhir = $oldPost['ijazah_terakhir'];

        if ($oldThumbnailKtp) {
            $this->deleteImage($oldThumbnailKtp);
        }

        if ($oldThumbnailCv) {
            $this->deleteImage($oldThumbnailCv);
        }

        if ($foto) {
            $this->deleteImage($foto);
        }

        if ($oldThumbnailIjazahTerakhir) {
            $this->deleteImage($oldThumbnailIjazahTerakhir);
        }



        $model->delete();

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



    public function saveImage($model, $uploadedFile, $type)
    {
        $uploadsDir =  "";
        if ($type == 'ktp' || $type == 0) {
            $uploadsDir =  Yii::getAlias('@webroot/uploads/ktp/');
        } elseif ($type == 'cv' || $type == 1) {
            $uploadsDir =  Yii::getAlias('@webroot/uploads/cv/');
        } elseif ($type == 'foto' || $type == 2) {
            $uploadsDir =  Yii::getAlias('@webroot/uploads/foto/');
        } elseif ($type == 'ijazah_terakhir' || $type == 3) {
            $uploadsDir =  Yii::getAlias('@webroot/uploads/ijazah_terakhir/');
        } else {
            return false;
        }

        if ($uploadedFile) {

            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);
            }
            $fileName = $uploadsDir . '/' . uniqid() . '.' . $uploadedFile->extension;

            if ($uploadedFile->saveAs($fileName)) {
                // Simpan path gambar baru ke model
                $model->{$type} = 'uploads/' . $type . '/' . basename($fileName);
                return true;
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save the uploaded file.');
                return false;
            }
        }
    }

    public function deleteImage($oldThumbnail)
    {
        if ($oldThumbnail && file_exists(Yii::getAlias('@webroot') . '/' . $oldThumbnail)) {
            unlink(Yii::getAlias('@webroot') . '/' . $oldThumbnail);
        } else {
            return false;
        }
    }

    public function actionKabupaten($id_prop)
    {
        $kabupaten = MasterKab::find()
            ->where(['kode_prop' => $id_prop])
            ->all();
        $dataKabupaten = \yii\helpers\ArrayHelper::map($kabupaten, 'kode_kab', 'nama_kab');
        return $this->asJson($dataKabupaten);
    }

    public function actionKecamatan($id_kabupaten)
    {
        $kecamatan = MasterKec::find()
            ->where(['kode_kab' => $id_kabupaten])
            ->all();

        $dataKecamatan = \yii\helpers\ArrayHelper::map($kecamatan, 'kode_kec', 'nama_kec');
        return $this->asJson($dataKecamatan);
    }
}
