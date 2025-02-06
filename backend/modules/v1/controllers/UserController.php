<?php

namespace app\modules\v1\controllers;

use amnah\yii2\user\models\User as User;
use yii\rest\Controller;
use backend\models\Absensi;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = User::class;
}
