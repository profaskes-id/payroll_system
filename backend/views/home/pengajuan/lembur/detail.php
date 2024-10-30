<?php

use amnah\yii2\user\models\User;
?>

<section class="w-full mx-auto sm:px-6 lg:px-8 min-h-[90dvh] px-5">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/lembur', 'title' => 'Pengajuan Lembur']); ?>


    <div class="bg-gray-100/50 w-full h-[80dvh] rounded-md p-2">

        <div class="flex justify-between items-start bg-white w-full  rounded-md p-3">
            <div>
                <p class="text-sm">Dikerjaan Pada : <?= date('d-m-Y', strtotime($model['tanggal'])) ?></p>
                <p class="text-sm font-bold">Status : <?= $model['status'] == '0' ? '<span class="text-yellow-500">Pending</span>' : ($model['status'] == '1' ? '<span class="text-green-500">Disetujui</span>' : '<span class="text-rose-500">Ditolak</span>')  ?></p>
            </div>
            <div>
                <div class="flex space-x-2">
                    <a href="/panel/pengajuan/lembur-update?id=<?= $model['id_pengajuan_lembur'] ?>" class="flex items-center justify-center">

                        <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="black" fill-rule="evenodd" d="M21.455 5.416a.75.75 0 0 1-.096.943l-9.193 9.192a.75.75 0 0 1-.34.195l-3.829 1a.75.75 0 0 1-.915-.915l1-3.828a.8.8 0 0 1 .161-.312L17.47 2.47a.75.75 0 0 1 1.06 0l2.829 2.828a1 1 0 0 1 .096.118m-1.687.412L18 4.061l-8.518 8.518l-.625 2.393l2.393-.625z" clip-rule="evenodd" />
                                <path fill="black" d="M19.641 17.16a44.4 44.4 0 0 0 .261-7.04a.4.4 0 0 1 .117-.3l.984-.984a.198.198 0 0 1 .338.127a46 46 0 0 1-.21 8.372c-.236 2.022-1.86 3.607-3.873 3.832a47.8 47.8 0 0 1-10.516 0c-2.012-.225-3.637-1.81-3.873-3.832a46 46 0 0 1 0-10.67c.236-2.022 1.86-3.607 3.873-3.832a48 48 0 0 1 7.989-.213a.2.2 0 0 1 .128.34l-.993.992a.4.4 0 0 1-.297.117a46 46 0 0 0-6.66.255a2.89 2.89 0 0 0-2.55 2.516a44.4 44.4 0 0 0 0 10.32a2.89 2.89 0 0 0 2.55 2.516c3.355.375 6.827.375 10.183 0a2.89 2.89 0 0 0 2.55-2.516" />
                            </svg></p>
                        <p>
                    </a>

                    <!-- 
                    <form action="/panel/pengajuan/lembur-delete" method="POST">
                        <input type="text" name="id" value="<?php // $model['id_pengajuan_lembur'] 
                                                            ?>" hidden>
                        <button type="button" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class=" grid place-items-center" onclick="deleteClick(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="red" d="M10 5h4a2 2 0 1 0-4 0M8.5 5a3.5 3.5 0 1 1 7 0h5.75a.75.75 0 0 1 0 1.5h-1.32l-1.17 12.111A3.75 3.75 0 0 1 15.026 22H8.974a3.75 3.75 0 0 1-3.733-3.389L4.07 6.5H2.75a.75.75 0 0 1 0-1.5zm2 4.75a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0zM14.25 9a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-1.5 0v-7.5a.75.75 0 0 1 .75-.75m-7.516 9.467a2.25 2.25 0 0 0 2.24 2.033h6.052a2.25 2.25 0 0 0 2.24-2.033L18.424 6.5H5.576z" />
                            </svg>
                        </button>
                    </form> -->
                </div>
            </div>
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
            <p class="capitalize  text-gray-500 text-sm">Catatan Admin : </p>
            <p><?= $model['catatan_admin']  ?? '-' ?></p>
            <hr class="my-2">
            <p class="capitalize  text-gray-500 text-sm">Jam Lembur</p>
            <div class="flex space-x-3 text-gray-500 text-sm">
                <p><?= date('H:i', strtotime($model['jam_mulai'])) ?></p>
                <p><?= date('H:i', strtotime($model['jam_selesai'])) ?></p>

            </div>
        </div>
        <div class="inline-flex items-center justify-center w-full">
            <hr class="w-64 h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
        </div>
        <div class="bg-white text-black  w-full  rounded-md p-2 mt-2">
            <p class="capitalize  text-gray-500 text-sm">Ditanggapi Oleh : </p>
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


<div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Yakin Melakukan Hapus Data?</h3>
                <button id="delete-button" data-modal-hide="popup-modal" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Yes, Hapus
                </button>
                <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteClick(e) {
        let parentForm = e.parentElement;

        document.getElementById("delete-button").addEventListener("click", function() {
            parentForm.submit();
        });
    }
</script>