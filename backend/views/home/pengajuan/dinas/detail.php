<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<section class="w-full mx-auto md:px-x lg:px-8 min-h-[90dvh]  relative z-50">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/dinas', 'title' => 'Pengajuan Dinas Luar']); ?>


    <div class="bg-gray-100/50 w-full h-[80dvh] rounded-md   relative ">

        <div class="w-full p-3 bg-white rounded-md">
            <p class="text-sm">Diajukan Untuk Tanggal : <?= date('d-m-Y', strtotime($model['tanggal_mulai'])) ?></p>
            <p class="text-sm fw-bold">Status : <?= $model['status'] == '0' ? '<span class="text-yellow-500">Pending</span>' : ($model['status'] == '1' ? '<span class="text-green-500">Disetujui</span>' : '<span class="text-rose-500">Ditolak</span>')  ?></p>
        </div>
        <div class="w-full p-2 mt-2 text-black bg-white rounded-md">
            <p class="text-sm text-gray-500 capitalize">Alasan Cuti</p>
            <p><?= $model['keterangan_perjalanan'] ?></p>
            <hr class="my-2">
            <p class="text-sm text-gray-500 capitalize">Catatan Admin : </p>
            <p><?= $model['catatan_admin']  ?? '-' ?></p>
            <hr class="my-2">
            <hr class="my-2">
            <p class="text-sm text-gray-500 capitalize">diajukan pada</p>
            <div class="flex space-x-3 text-sm text-gray-500">
                <p><?= date('d-m-Y', strtotime($model['tanggal_mulai']))
                    ?></p>
                <p><?= date('d-m-Y', strtotime($model['tanggal_selesai']))
                    ?></p>

            </div>
        </div>
        <div class="relative inline-flex items-center justify-center w-full mb-4">
            <hr class="w-64 h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
        </div>
        <?php if (!$model->dokumentasi == []): ?>
            <div class="relative w-full p-2 mt-2 text-black bg-white rounded-md min-h-32">
                <div class="flex items-center justify-between">
                    <p class="font-semibold text-gray-500 capitalize">Dokumentasi Perjalanan</p>
                    <?= Html::a('Delete All', ['pengajuan/delete-dokumentasi', 'id' => $model->id_pengajuan_dinas], ['class' => 'text-rose-500 rounded-md  p-1']) ?>
                </div>

                <?php
                $data = json_decode($model->dokumentasi, true);
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
                <?= $form->field($model, 'dokumentasi[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full '])->label('Upload Dokumentasi Perjalanan') ?>
                <div class="absolute bottom-0 left-0 right-0 col-span-12">
                    <div class="">
                        <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            <?php endif ?>


        </div>
    </div>

</section>