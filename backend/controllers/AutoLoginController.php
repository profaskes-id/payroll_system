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
        // dd($action->id);
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
        $params = Yii::$app->request->get();
        $factory = new PasswordHasherFactory([
            'common' => ['algorithm' => 'bcrypt'],
            'memory-hard' => ['algorithm' => 'sodium'],
        ]);

        $passwordHasher = $factory->getPasswordHasher('common');
        if (Yii::$app->request->isPost) {
            $kode_karyawan = Yii::$app->request->post('kode_karyawan');
            $password = Yii::$app->request->post('password');
            // Configure different password hashers via the factory



            if ($passwordHasher->verify($params['token'], $kode_karyawan)) {

                $model = Karyawan::find()->where(['kode_karyawan' => $kode_karyawan])->select(['email', 'nama', 'nomer_identitas'])->one();
                $is_user_exist = User::find()->where(['email' => $model->email])->one();
                if ($is_user_exist) {
                    return $this->redirect(['/home']);
                }
                $user = new User();
                $user->email = $model->email;
                $user->newPassword = $password;
                $user->setRegisterAttributes(2, 1);
                // dd($user);
                if ($user->save()) {
                    // Yii::$app->user->login($user);
                    $profil = new Profile();
                    $profil->user_id = $user->id;
                    $profil->full_name = $model->nama;
                    if ($profil->save()) {
                        return $this->redirect(['/home']);
                    } else {
                        return 'gagal save profil ';
                        return $this->redirect(['/']);
                    }
                } else {
                    $olderUser = User::find()->where(['email' => $model->email])->one();
                    if ($olderUser) {
                        if ($olderUser->delete()) {
                            $user->email = $model->email;
                            $user->newPassword = $password;
                            if ($user->save()) {
                                // Yii::$app->user->login($user);
                                $profil = new Profile();
                                $profil->user_id = $user->id;
                                $profil->full_name = $model->nama;
                                if ($profil->save()) {
                                    return $this->redirect(['/home']);
                                } else {
                                    return 'gagal save profil ';
                                    return $this->redirect(['/']);
                                }
                            }
                        } else {
                            return 'gagal delete user';
                        }
                    }
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
