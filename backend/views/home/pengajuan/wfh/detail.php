<?php

use amnah\yii2\user\models\User;
?>

<?php
$assrray = json_decode($model['tanggal_array']);
$tglMulai = $assrray[0] ?? "2024-10-10";
$tglMulai = end($assrray);;

?>
<section class="w-full mx-auto sm:px-6 lg:px-8 min-h-[90dvh] px-5">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/wfh', 'title' => 'Pengajuan WFH']); ?>


    <div class="bg-gray-100/50 w-full h-[80dvh] rounded-md p-2">

        <div class="flex justify-between items-start bg-white w-full  rounded-md p-3">
            <div>
                <p class="text-sm font-bold">Status : <?= $model['status'] == '0' ? '<span class="text-yellow-500">Pending</span>' : ($model['status'] == '1' ? '<span class="text-green-500">Disetujui</span>' : '<span class="text-rose-500">Ditolak</span>')  ?></p>
            </div>

        </div>
        <div class="bg-white text-black  w-full  rounded-md p-2 mt-2">
            <p class="capitalize  text-gray-500 text-sm">Catatan Admin : </p>
            <p><?= $model['catatan_admin']  ?? '-' ?></p>
            <hr class="my-2">
            <p class="capitalize  text-gray-500 text-sm">Mulai WFH</p>
            <div class="flex space-x-3 text-gray-500 text-sm">
                <p><?= date('d-m-y', strtotime($tglMulai)) ?></p>
                <p>~</p>
                <p><?= date('d-m-y', strtotime($tglMulai)) ?></p>

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