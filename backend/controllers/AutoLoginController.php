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
            $nomer_identitas = Yii::$app->request->post('nomer_identitas');
            $password = Yii::$app->request->post('password');
            // Configure different password hashers via the factory

            if ($passwordHasher->verify($params['token'], $nomer_identitas)) {

                $model = Karyawan::find()->where(['nomer_identitas' => $nomer_identitas])->select(['email', 'nama', 'nomer_identitas'])->one();
                $user = new User();
                $user->email = $model->email;
                $user->newPassword = $password;
                $user->setRegisterAttributes(2, 1);
                if ($user->save()) {
                    Yii::$app->user->login($user);
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
                                Yii::$app->user->login($user);
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
                return 'gagal nomer identitas beda';
            }
        }

        return $this->render('register', [
            'nomer_identitas' => $params['id'] ?? null,
        ]);
    }


    // public function actionNewPassword($token)
    // {

    //     // Configure different password hashers via the factory
    //     $factory = new PasswordHasherFactory([
    //         'common' => ['algorithm' => 'bcrypt'],
    //     ]);

    //     // Retrieve the right password hasher by its name
    //     $passwordHasher = $factory->getPasswordHasher('common');

    //     // Hash a plain password
    //     // dd($passwordHasher->verify($hash, strval($model->nomer_identitas))); // returns true (valid)

    //     $user = User::find()->where(['access_token' => $token])->one();
    //     // $user = User::find()->all();
    //     // dd($user);
    //     if ($user) {
    //         Yii::$app->user->login($user);
    //         return $this->redirect(['/']); // Ganti dengan halaman yang sesuai
    //     } else {
    //         return $this->redirect(['/user/login']); // Ganti dengan halaman login atau halaman error
    //     }
    // }
}
