<?php

use amnah\yii2\user\models\User;
use yii\helpers\Html;

?>
<section class="w-full mx-auto md:px-5 lg:px-8 min-h-[90dvh] relative z-50">
    <?= $this->render('@backend/views/components/_header', [
        'link' => '/panel/pengajuan/kasbon',
        'title' => 'Detail Pengajuan Kasbon'
    ]); ?>

    <div class="relative w-full p-3 rounded-md bg-gray-100/50">

        <!-- Informasi Utama -->
        <div class="w-full p-3 bg-white rounded-md shadow-sm">
            <p class="text-sm">Tanggal Pengajuan: <strong><?= date('d-m-Y', strtotime($model['tanggal_pengajuan'])) ?></strong></p>
            <?php if (!empty($model['tanggal_pencairan'])) : ?>
                <p class="text-sm">Tanggal Pencairan: <strong><?= date('d-m-Y', strtotime($model['tanggal_pencairan'])) ?></strong></p>
            <?php endif; ?>
            <p class="mt-1 text-sm">
                Status:
                <?= $model['status'] == '0'
                    ? '<span class="font-semibold text-yellow-500">Pending</span>'
                    : ($model['status'] == '1'
                        ? '<span class="font-semibold text-green-500">Disetujui</span>'
                        : '<span class="font-semibold text-rose-500">Ditolak</span>') ?>
            </p>
        </div>

        <!-- Detail Kasbon -->
        <div class="w-full p-3 mt-3 bg-white rounded-md shadow-sm">
            <p class="mb-1 text-sm text-gray-500 capitalize">Jumlah Kasbon</p>
            <p class="text-base font-semibold">Rp <?= number_format($model['jumlah_kasbon'], 0, ',', '.') ?></p>
            <hr class="my-2">

            <p class="mb-1 text-sm text-gray-500 capitalize">Lama Cicilan</p>
            <p><?= $model['lama_cicilan'] ?> bulan</p>
            <hr class="my-2">

            <p class="mb-1 text-sm text-gray-500 capitalize">Angsuran per Bulan</p>
            <p>Rp <?= number_format($model['angsuran_perbulan'], 0, ',', '.') ?></p>
            <hr class="my-2">

            <?php if (!empty($model['tanggal_mulai_potong'])) : ?>
                <p class="mb-1 text-sm text-gray-500 capitalize">Tanggal Mulai Potong Gaji</p>
                <p><?= date('d-m-Y', strtotime($model['tanggal_mulai_potong'])) ?></p>
                <hr class="my-2">
            <?php endif; ?>

            <p class="mb-1 text-sm text-gray-500 capitalize">Keterangan</p>
            <p><?= !empty($model['keterangan']) ? nl2br(Html::encode($model['keterangan'])) : '-' ?></p>
        </div>

        <!-- Bagian Persetujuan -->
        <div class="w-full p-3 mt-3 bg-white rounded-md shadow-sm">
            <p class="mb-1 text-sm text-gray-500 capitalize">Tanggal Ditanggapi</p>
            <p><?= $model['tanggal_disetujui'] ? date('d-m-Y', strtotime($model['tanggal_disetujui'])) : '-' ?></p>
            <hr class="my-2">

            <p class="mb-1 text-sm text-gray-500 capitalize">Ditanggapi Oleh</p>
            <?php

            $namaPenanggap = '-';

            if (!empty($model['disetujui_oleh'])) {
                $user = User::find()->where(['id' => $model['disetujui_oleh']])->one();

                if ($user) {
                    if (!empty($user->profile) && !empty($user->profile->full_name)) {
                        $namaPenanggap = $user->profile->full_name;
                    } elseif (!empty($user->username)) {
                        $namaPenanggap = $user->username;
                    } else {
                        $namaPenanggap = $model['disetujui_oleh'];
                    }
                } else {
                    $namaPenanggap = $model['disetujui_oleh'];
                }
            }
            ?>

            <p><?= $namaPenanggap ?></p>
            <hr class="my-2">


            <p class="mb-1 text-sm text-gray-500 capitalize">Tipe Potongan</p>
            <p>
                <?= $model['tipe_potongan'] == 0 ? 'manua' : ($model['tipe_potongan'] == 1 ? 'Potong Gaji' : 'Lainnya') ?>
            </p>
        </div>
    </div>
</section>