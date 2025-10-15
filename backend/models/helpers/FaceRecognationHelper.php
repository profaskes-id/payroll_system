<?php

namespace backend\models\helpers;

use backend\models\SettinganUmum;
use Yii;
class FaceRecognationHelper
{
    static function cekFaceRecognation(){

        $setting = SettinganUmum::findOne(['kode_setting' => Yii::$app->params['change_fr']]);
        if ($setting) {
            return $setting->nilai_setting;
        }else{
            return 0;
        }

    }

    static function cekVerificationFr(){
        $setting = SettinganUmum::findOne(['kode_setting' => Yii::$app->params['verifikasi_fr']]);
        if ($setting) {
            return $setting->nilai_setting;
        }else{
            return null;
        }
    }


}
