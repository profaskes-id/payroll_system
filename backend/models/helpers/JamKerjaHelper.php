<?php

namespace backend\models\helpers;

use backend\models\JamKerja;


class JamKerjaHelper extends JamKerja
{
    public static function getJamKerjaData()
    {
        return JamKerja::find()->asArray()->all();
    }

    public static function getJamKerja($id)
    {

        return JamKerja::find()->where(['id_jam_kerja' => $id])->asArray()->one();
    }
}
