<section class="container w-full px-5 my-3">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/tugasluar', 'title' => 'Pengajuan Tugas Luar']); ?>

    <div>
        <?= $this->render('_form', [
            'model' => $model,
            'details' => $details
        ]) ?>
    </div>
</section>