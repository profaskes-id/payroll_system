<section class="w-full">
    <?= $this->render('@backend/views/components/_header'); ?>



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</section>