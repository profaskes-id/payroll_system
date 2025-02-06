<?php

namespace backend\controllers;

use amnah\yii2\user\models\Profile;
use yii\web\Controller;
use amnah\yii2\user\models\User;
use backend\models\Karyawan;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Yii;




class AutoLoginController extends Controller
{


    public function beforeAction($action)
    {

        if ($action->id == 'login') {
            // Menonaktifkan CSRF verification untuk aksi 'view'
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    public function getViewPath()
    {
        return Yii::getAlias('@backend/views/auth');
    }
    public function actionLogin()
    {
        $users = new User();
        $params = Yii::$app->request->get();
        $factory = new PasswordHasherFactory([
            'common' => ['algorithm' => 'bcrypt'],
            'memory-hard' => ['algorithm' => 'sodium'],
        ]);

        $passwordHasher = $factory->getPasswordHasher('common');



        if (Yii::$app->request->isPost) {

            $kode_karyawan = Yii::$app->request->post('kode_karyawan');
            $password = Yii::$app->request->post('password');

            $model = Karyawan::find()->select(['id_karyawan', 'email', 'nama', 'nomer_identitas'])->where(['kode_karyawan' => $kode_karyawan])->one();
            $users->id_karyawan = $model->id_karyawan;
            $users->email =  $model->email;
            $users->newPassword = $password;
            $users->setRegisterAttributes(2, 1);
            if ($passwordHasher->verify($params['token'], $kode_karyawan)) {

                if ($users->save(false)) {
                    $profil = new Profile();
                    $profil->user_id = $users->id;
                    $profil->full_name = $model->nama;
                    if ($profil->save()) {
                        return $this->redirect(['/home']);
                    } else {
                        Yii::$app->session->setFlash(
                            'error',
                            "gagal save profile"
                        );
                        return $this->redirect(['/']);
                    }
                } else {
                    // tampilkan error
                    return  Yii::error("Error saving user: " . json_encode($users->errors), __METHOD__);
                }
            } else {
                return 'gagal kode karyawan beda';
            }
        }

        return $this->render('register', [
            'kode_karyawan' => $params['id'] ?? null,
        ]);
    }
}
