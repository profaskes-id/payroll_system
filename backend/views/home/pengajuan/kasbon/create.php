<section class="container relative z-50 w-full px-2 my-3 md:px-5">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/kasbon', 'title' => 'Pengajuan Kasbon']); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'gajiPokok' => $gajiPokok,
    ]) ?>

</section>