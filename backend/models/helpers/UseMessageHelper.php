<?php

namespace backend\models\helpers;

use amnah\yii2\user\models\User;
use backend\models\AtasanKaryawan;
use Yii;


class UseMessageHelper
{

    // dapatkan atasan yang akan menerima message
    public function getUserAtasanReceiver($id_karyawan)
    {
        $atasan = AtasanKaryawan::find()->where(['id_karyawan' => $id_karyawan])->asArray()->one();
        if ($atasan != null) {
            $adminUsers = User::find()->select(['id', 'email', 'role_id'])->where(['id_karyawan' => $atasan['id_atasan']])->all();
        } else {
            $adminUsers = User::find()->select(['id', 'email', 'role_id'])->where(['role_id' => [1, 3]])->all();
        }

        return $adminUsers;
    }
}
