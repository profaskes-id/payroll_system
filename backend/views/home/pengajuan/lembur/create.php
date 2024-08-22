<section class="w-full container  px-5 my-3">
    <?= $this->render('@backend/views/components/_header', ['title' => 'Pengajuan Lmbur']); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</section>