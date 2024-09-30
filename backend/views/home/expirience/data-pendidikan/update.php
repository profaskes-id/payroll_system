<section class="w-full container p-5 my-3">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/home/expirience', 'title' => 'Upadate Data Pendidikan']); ?>



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</section>