<?php

namespace backend\models\helpers;

use backend\models\SettinganUmum;
use Yii;

class ManualSHiftHelper extends SettinganUmum
{
    public static function isManual()
    {
        return SettinganUmum::find()->where(['kode_setting' => Yii::$app->params['manual_shift']])->asArray()->one()['nilai_setting'];
    }
    public static function changeShift()
    {

        return SettinganUmum::find()->where(['kode_setting' => Yii::$app->params['change_shift']])->asArray()->one()['nilai_setting'];
    }
}
