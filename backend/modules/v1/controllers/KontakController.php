<?php

namespace app\modules\v1\controllers;

use backend\models\MasterKode as MasterKodeModel;
use yii\rest\ActiveController;
use yii\web\Response;

class KontakController extends ActiveController
{
    public $modelClass = MasterKodeModel::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;


        $waAdmin = $this->modelClass::find()->where(['nama_group' => 'wa-admin'])->asArray()->one();
        $noTelfonAdmin = $this->modelClass::find()->where(['nama_group' => 'no-telfon-admin'])->asArray()->one();
        $emailAdmin = $this->modelClass::find()->where(['nama_group' => 'email-admin'])->asArray()->one();
        return [
            'waAdmin' => $waAdmin,
            'noTelfonAdmin' => $noTelfonAdmin,
            'emailAdmin' => $emailAdmin
        ];
    }
}
