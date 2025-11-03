<section class="container w-full px-2 my-3 md:px-5">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/wfh', 'title' => 'Pengajuan WFH']); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</section>