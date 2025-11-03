<section class="container relative z-50 w-full px-2 my-3 md:px-5">
    <?= $this->render('@backend/views/components/_header', [
        'link' => '/panel/pengajuan/tugas-luar',
        'title' => 'Edit Pengajuan Tugas Luar'
    ]); ?>

    <div>
        <?= $this->render('_form', [
            'model' => $model,
            'details' => $details
        ]) ?>
    </div>
</section>