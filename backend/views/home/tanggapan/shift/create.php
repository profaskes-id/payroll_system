<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjangan $model */

$this->title = Yii::t('app', 'Tambah Pengajuan Shift Karyawan');
?>
<div class="relative z-50 mx-10 mt-5">


    <div class="flex items-center justify-between">
        <h1 class="mb-6 text-2xl font-bold "><?= Html::encode($this->title) ?></h1>
        <p class="">
            <?= Html::a('Back', ['/tanggapan/shift'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'karyawanBawahanAdmin' => $karyawanBawahanAdmin
    ]) ?>

</div>