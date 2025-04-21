<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjangan $model */

$this->title = Yii::t('app', 'Update Pengajuan WFH Karyawan');
?>
<div class="mx-3 mt-5 md:mx-10">
    <div class="flex items-center justify-between">
        <h1 class="mb-6 text-base font-bold md:text-2xl "><?= Html::encode($this->title) ?></h1>
        <p class="">
            <?= Html::a('Back', ['/tanggapan/shift'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'karyawanBawahanAdmin' => $karyawanBawahanAdmin,
        // 'tanggal_awal' => $tanggal_awal,
        // 'tanggal_akhir' => $tanggal_akhir
    ]) ?>

</div>