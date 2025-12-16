<?php

namespace backend\controllers;

use backend\models\UserInduk;
use backend\models\ProfileInduk;
use amnah\yii2\user\models\Profile;
use yii\web\Controller;
use amnah\yii2\user\models\User;
use backend\models\Karyawan;
use mdm\admin\models\Assignment;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Yii;
use yii\web\BadRequestHttpException;

class AutoLoginController extends Controller
{
    public function beforeAction($action)
    {
        if ($action->id == 'login') {
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
        $kode_karyawan = Yii::$app->request->post('kode_karyawan');
        $password = Yii::$app->request->post('password');

        if (Yii::$app->request->isPost) {
            // Cari karyawan berdasarkan kode_karyawan
            $model = Karyawan::find()
                ->select(['id_karyawan', 'email', 'nama', 'nomer_identitas'])
                ->where(['kode_karyawan' => $kode_karyawan])
                ->one();

            if (!$model) {
                throw new BadRequestHttpException('Karyawan tidak ditemukan.');
            }

            // Verifikasi token
            $factory = new PasswordHasherFactory([
                'common' => ['algorithm' => 'bcrypt'],
                'memory-hard' => ['algorithm' => 'sodium'],
            ]);

            $passwordHasher = $factory->getPasswordHasher('common');

            if (!$passwordHasher->verify($params['token'], $kode_karyawan)) {
                throw new BadRequestHttpException('Token tidak valid.');
            }

            // Simpan user ke database utama (db)
            $user = new User();
            $user->id_karyawan = $model->id_karyawan;
            $user->email = $model->email;
            $user->newPassword = $password;
            $user->setRegisterAttributes(2, 1);
            $user->base_url = Yii::$app->params['base_url'];



            if ($user->save()) {
                $items = ['Karyawan'];
                $model = new Assignment($user->id);
                $success = $model->assign($items);
                Yii::$app->getResponse()->format = 'json';

                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->full_name = $model->nama;

                if ($profile->save()) {

                    // Simpan user ke database induk (db_induk)
                    if (Yii::$app->params['base_url'] != Yii::$app->params['base_parent_url']) {
                        $userInduk = new UserInduk(); // Gunakan model UserInduk
                        $userInduk->id_karyawan = $user->id_karyawan;
                        $userInduk->email = $user->email;
                        $userInduk->newPassword = $user->newPassword;
                        $userInduk->setRegisterAttributes(2, 1);
                        $userInduk->base_url = Yii::$app->params['base_url'];
                        if ($userInduk->save()) {
                            // Simpan profil user ke database induk (db_induk)
                            $profileInduk = new ProfileInduk(); // Gunakan model ProfileInduk
                            $profileInduk->user_id = $userInduk->id;
                            $profileInduk->full_name = $profile->full_name;

                            if ($profileInduk->save()) {
                                Yii::$app->session->setFlash('success', 'Login berhasil .');
                                return $this->redirect(['/']);
                            } else {
                                // Yii::$app->session->setFlash('error', 'Gagal menyimpan profil di db_induk.');
                                return $this->redirect(['/']);
                            }
                        } else {
                            // Yii::$app->session->setFlash('error', 'Gagal menyimpan user di db_induk.');
                            return $this->redirect(['/']);
                        }
                    } else {
                        Yii::$app->session->setFlash('success', ' Login berhasil dan data disimpan .');
                        return $this->redirect(['/']);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal menyimpan profil .');
                    return $this->redirect(['/']);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Gagal menyimpan user .');
                return $this->redirect(['/']);
            }
        }

        return $this->render('register', [
            'kode_karyawan' => $params['id'] ?? null,
        ]);
    }
}
