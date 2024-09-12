<?php

namespace backend\controllers;

use yii\web\Controller;
use amnah\yii2\user\models\User;
use Yii;

class AutoLoginController extends Controller
{
    public function actionLogin($token)
    {
        $user = User::find()->where(['access_token' => $token])->one();
        // $user = User::find()->all();
        // dd($user);
        if ($user) {
            Yii::$app->user->login($user);
            return $this->redirect(['/']); // Ganti dengan halaman yang sesuai
        } else {
            return $this->redirect(['/user/login']); // Ganti dengan halaman login atau halaman error
        }
    }
}
