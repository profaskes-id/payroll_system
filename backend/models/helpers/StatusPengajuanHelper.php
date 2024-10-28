<?php

namespace backend\models\helpers;

use backend\models\MasterKode;

class StatusPengajuanHelper extends MasterKode
{
    public static function getStatusPengajuan()
    {
        return MasterKode::find()->where(['nama_group' => 'status-pengajuan'])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all();
    }
}
