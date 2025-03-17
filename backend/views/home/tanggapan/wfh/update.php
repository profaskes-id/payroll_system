<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjangan $model */

$this->title = Yii::t('app', 'Update Pengajuan WFH Karyawan');
?>
<div class="mx-10 mt-5">


    <div class="flex items-center justify-between">
        <h1 class="mb-6 text-2xl font-bold "><?= Html::encode($this->title) ?></h1>
        <p class="">
            <?= Html::a('Back', ['/tanggapan/wfh'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'karyawanBawahanAdmin' => $karyawanBawahanAdmin,
        'tanggal_awal' => $tanggal_awal,
        'tanggal_akhir' => $tanggal_akhir
    ]) ?>

</div>