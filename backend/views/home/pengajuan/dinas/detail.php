<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<section class="w-full mx-auto sm:px-6 lg:px-8 min-h-[90dvh] px-5">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/dinas', 'title' => 'Pengajuan Dinas Luar']); ?>


    <div class="bg-gray-100/50 w-full h-[80dvh] rounded-md p-2  relative ">

        <div class="bg-white w-full  rounded-md p-3">
            <p class="text-sm">Diajukan Untuk Tanggal : <?= date('d-m-Y', strtotime($model['tanggal_mulai'])) ?></p>
            <p class="text-sm fw-bold">Status : <?= $model['status'] == '0' ? '<span class="text-yellow-500">Pending</span>' : ($model['status'] == '1' ? '<span class="text-green-500">Disetujui</span>' : '<span class="text-rose-500">Ditolak</span>')  ?></p>
        </div>
        <div class="bg-white text-black  w-full  rounded-md p-2 mt-2">
            <p class="capitalize  text-gray-500 text-sm">Alasan Cuti</p>
            <p><?= $model['keterangan_perjalanan'] ?></p>
            <hr class="my-2">
            <p class="capitalize  text-gray-500 text-sm">diajukan pada</p>
            <div class="flex space-x-3 text-gray-500 text-sm">
                <p><?= date('d-m-Y', strtotime($model['tanggal_mulai']))
                    ?></p>
                <p><?= date('d-m-Y', strtotime($model['tanggal_selesai']))
                    ?></p>

            </div>
        </div>
        <div class="inline-flex items-center justify-center w-full mb-4 relative">
            <hr class="w-64 h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
        </div>
        <?php if (!$model->files == []): ?>
            <div class="bg-white text-black relative  w-full  rounded-md p-2 min-h-32 mt-2">
                <div class="flex justify-between items-center">
                    <p class="capitalize  text-gray-500 font-semibold">Dokumentasi Perjalanan</p>
                    <?= Html::a('Delete All', ['pengajuan/delete-dokumentasi', 'id' => $model->id_pengajuan_dinas], ['class' => 'text-rose-500 rounded-md  p-1']) ?>
                </div>

                <?php
                $data = json_decode($model->files, true);
                foreach ($data as $key => $item) : ?>
                    <?php $key++ ?>
                    <p class="my-1">
                        <?= Html::a("Preview Dokumentasi {$key}", Yii::getAlias('@root') . '/panel/' . $item, ['target' => '_blank', 'class' => 'text-blue-500']) ?>
                    </p>
                <?php endforeach ?>
            </div>
        <?php endif; ?>




        <div class="mt-5">
            <?php

            if ($model['status'] == '1'): ?>
                <?php $form = ActiveForm::begin(['action' =>  'post', 'action' => ['pengajuan/upload-dokumentasi'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <?= $form->field($model, 'files[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full '])->label('Upload Dokumentasi Perjalanan') ?>
                <div class="col-span-12 absolute left-0 right-0 bottom-0">
                    <div class="">
                        <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            <?php endif ?>


        </div>
    </div>

</section>