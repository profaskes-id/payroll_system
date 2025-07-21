<section class="container w-full px-5 my-3">
    <?= $this->render('@backend/views/components/_header', [
        'link' => '/panel/absensi-tertinggal',
        'title' => 'Update Pengajuan Absensi'
    ]); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</section>