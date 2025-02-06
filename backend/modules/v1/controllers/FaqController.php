<?php

namespace app\modules\v1\controllers;

use yii\rest\ActiveController;
use backend\models\Faq as FaqModel;

class FaqController extends ActiveController
{
  public $modelClass = FaqModel::class;
}