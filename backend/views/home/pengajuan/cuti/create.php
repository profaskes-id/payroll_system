<section class="w-full container  px-5 my-3">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/cuti', 'title' => 'Pengajuan Cuti']); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'jenisCuti' => $jenisCuti,
        'rekapCuti' => $rekapCuti,
    ]) ?>

</section>