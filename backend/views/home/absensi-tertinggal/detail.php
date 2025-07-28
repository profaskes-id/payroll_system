<?php

use amnah\yii2\user\models\User;
?>

<section class="w-full mx-auto sm:px-6 lg:px-8 min-h-[90dvh] px-5 relative z-50">
    <?= $this->render('@backend/views/components/_header', [
        'link' => '/panel/absensi-tertinggal',
        'title' => 'Detail Pengajuan Absensi'
    ]); ?>

    <div class="bg-gray-100/50 w-full h-[80dvh] rounded-md p-2">
        <div class="flex items-start justify-between w-full p-3 bg-white rounded-md">
            <div>
                <p class="text-sm">Tanggal Absen: <?= date('d-m-Y', strtotime($model->tanggal_absen)) ?></p>
                <p class="text-sm font-bold">Status:
                    <?= $model->status == '0' ? '<span class="text-yellow-500">Pending</span>' : ($model->status == '1' ? '<span class="text-green-500">Disetujui</span>' :
                        '<span class="text-rose-500">Ditolak</span>') ?>
                </p>
            </div>
        </div>

        <div class="w-full p-2 mt-2 text-black bg-white rounded-md">
            <p class="text-sm text-gray-500 capitalize">Jam Absensi</p>
            <div class="flex space-x-3 text-sm text-gray-500">
                <p>Masuk: <?= date('H:i', strtotime($model->jam_masuk)) ?></p>
                <p>Keluar: <?= date('H:i', strtotime($model->jam_keluar)) ?></p>
            </div>

            <hr class="my-2">

            <p class="text-sm text-gray-500 capitalize">Alasan Pengajuan:</p>
            <p><?= $model->alasan_pengajuan ?? '-' ?></p>

            <hr class="my-2">

            <p class="text-sm text-gray-500 capitalize">Catatan Approver:</p>
            <p><?= $model->catatan_approver ?? '-' ?></p>
        </div>

        <div class="inline-flex items-center justify-center w-full">
            <hr class="w-64 h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
        </div>

        <div class="w-full p-2 mt-2 text-black bg-white rounded-md">
            <p class="text-sm text-gray-500 capitalize">Ditanggapi Oleh:</p>
            <p class="text-black">
                <?php $nama = User::find()->where(['id' => $model->id_approver])->one(); ?>
                <?= $nama->username ?? $model->id_approver ?>
            </p>

            <p class="mt-2 text-sm text-gray-500 capitalize">Tanggal Disetujui:</p>
            <p class="text-black">
                <?= $model->tanggal_disetujui ? date('d-m-Y H:i', strtotime($model->tanggal_disetujui)) : '-' ?>
            </p>
        </div>
    </div>
</section>

<div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <!-- Modal content (same as your original) -->
</div>

<script>
    function deleteClick(e) {
        let parentForm = e.parentElement;
        document.getElementById("delete-button").addEventListener("click", function() {
            parentForm.submit();
        });
    }
</script>