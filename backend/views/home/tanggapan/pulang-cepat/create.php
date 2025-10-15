<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjangan $model */

$this->title = Yii::t('app', 'Pengajuan Pulang Cepat Karyawan');
?>
<div class="relative z-50 mx-10 mt-5">

    <h1 class="mb-6 text-2xl font-bold "><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'karyawanBawahanAdmin' => $karyawanBawahanAdmin
    ]) ?>

</div>