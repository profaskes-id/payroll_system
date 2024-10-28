<section class="w-full container  px-5 my-3">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/wfh', 'title' => 'Pengajuan WFH']); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</section>