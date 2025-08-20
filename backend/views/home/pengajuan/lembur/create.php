<section class="container relative z-40 w-full px-5 my-3">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/lembur', 'title' => 'Pengajuan Lembur']); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'poinArray' => $poinArray
    ]) ?>

</section>