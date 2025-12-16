<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjangan $model */

$this->title = Yii::t('app', 'Pengajuan Dinas Karyawan');
?>
<div class="relative z-50 mx-3 mt-5 md:mx-10">
    <div class="flex items-center justify-between">
        <h1 class="mb-6 text-base font-bold md:text-2xl "><?= Html::encode($this->title) ?></h1>
        <p class="">
            <?= Html::a('Back', ['/tanggapan/dinas'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'detailModels' => $detailModels,
        'karyawanBawahanAdmin' => $karyawanBawahanAdmin,

    ]) ?>

</div>