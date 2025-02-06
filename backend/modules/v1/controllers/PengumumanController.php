<?php

namespace app\modules\v1\controllers;

use backend\models\Pengumuman as Pengumuman;
use yii\rest\ActiveController;


class PengumumanController extends ActiveController
{
    public $modelClass = Pengumuman::class;
}
