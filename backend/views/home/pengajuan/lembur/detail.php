<?php

use amnah\yii2\user\models\User;
?>

<section class="max-w-[500px] mx-auto sm:px-6 lg:px-8 min-h-[90dvh] px-5">
    <?= $this->render('@backend/views/components/_header', ['title' => 'Pengajuan lembur']); ?>


    <div class="bg-gray-100/50 w-full h-[80dvh] rounded-md p-2">

        <div class="bg-white w-full  rounded-md p-3">
            <p class="text-sm">Dikerjaan Pada : <?= $model['tanggal'] ?></p>
            <p class="text-sm font-bold">Status : <?= $model['status'] == '0' ? '<span class="text-yellow-500">Pending</span>' : ($model['status'] == '1' ? '<span class="text-green-500">Disetujui</span>' : '<span class="text-rose-500">Ditolak</span>')  ?></p>
        </div>
        <div class="bg-white text-black  w-full  rounded-md p-2 mt-2">
            <p class="capitalize  text-gray-500 ">List Pekerjaan</p>
            <ul class="list-disc list-inside px-2 my-2">

                <?php if (!empty($poinArray)) : ?>
                    <?php foreach ($poinArray as $item) : ?>
                        <li><?= $item ?></li>
                    <?php endforeach ?>
                <?php else : ?>
                    <li>Tidak Ada List Pekerjaan Yang Tercantum</li>
                <?php endif ?>
            </ul>
            <hr class="my-2">
            <p class="capitalize  text-gray-500 text-sm">Jam Lembur</p>
            <div class="flex space-x-3 text-gray-500 text-sm">
                <p><?= date('d-m-Y', strtotime($model['jam_mulai'])) ?></p>
                <p><?= date('d-m-Y', strtotime($model['jam_selesai'])) ?></p>

            </div>
        </div>
        <div class="inline-flex items-center justify-center w-full">
            <hr class="w-64 h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
        </div>
        <div class="bg-white text-black  w-full  rounded-md p-2 mt-2">
            <p class="capitalize  text-gray-500 text-sm">Disetujui Oleh : </p>
            <p class="text-black">
                <?php
                $nama = User::find()->where(['id' => $model['disetujui_oleh']])->one(); ?>
                <?= $nama->username ?? $model['disetujui_oleh'] ?>
            </p>
        </div>


    </div>

    <div class="fixed bottom-0 left-0 right-0 z-50">
        <?= $this->render('@backend/views/components/_footer'); ?>
    </div>
</section>