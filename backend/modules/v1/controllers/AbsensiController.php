<?php

namespace app\modules\v1\controllers;

use backend\models\PengajuanDinas;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class AbsensiController extends ActiveController
{
    public $modelClass = 'backend\models\absensi';
}
