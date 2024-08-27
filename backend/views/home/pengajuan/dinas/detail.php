<section class="max-w-[500px] mx-auto sm:px-6 lg:px-8 min-h-[90dvh] px-5">
    <?= $this->render('@backend/views/components/_header', ['title' => 'Pengajuan Cuti']); ?>


    <div class="bg-gray-100/50 w-full h-[80dvh] rounded-md p-2">

        <div class="bg-white w-full  rounded-md p-3">
            <p class="text-sm">Diajukan Untuk : <?= $model['tanggal'] ?></p>
            <p class="text-sm fw-bold">Status : <?= $model['status'] == '0' ? '<span class="text-yellow-500">Pending</span>' : ($model['status'] == '1' ? '<span class="text-green-500">Disetujui</span>' : '<span class="text-rose-500">Ditolak</span>')  ?></p>
        </div>
        <div class="bg-white text-black  w-full  rounded-md p-2 mt-2">
            <p class="capitalize  text-gray-500 text-sm">Alasan Cuti</p>
            <p><?= $model['keterangan_perjalanan'] ?></p>
            <hr class="my-2">
            <p class="capitalize  text-gray-500 text-sm">diajukan pada</p>
            <div class="flex space-x-3 text-gray-500 text-sm">
                <!-- <p><? // date('d-m-Y', strtotime($model['tanggal_mulai'])) 
                        ?></p> -->
                <!-- <p><? // date('d-m-Y', strtotime($model['tanggal_selesai'])) 
                        ?></p> -->

            </div>
        </div>
        <div class="inline-flex items-center justify-center w-full">
            <hr class="w-64 h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
        </div>
        <div class="bg-white text-black  w-full  rounded-md p-2 mt-2">
            <p class="capitalize  text-gray-500 text-sm">tanggapan admin : </p>
            <p class="text-black"><? // $model['catatan_admin'] 
                                    ?></p>
        </div>


    </div>

    <div class="fixed bottom-0 left-0 right-0 z-50">
        <?= $this->render('@backend/views/components/_footer'); ?>
    </div>
</section>