<section class="w-full container  px-5 my-3">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/dinas', 'title' => 'Pengajuan Dinas Luar']); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</section>