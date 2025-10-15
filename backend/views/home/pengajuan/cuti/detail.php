<section class="w-full mx-auto sm:px-6 lg:px-8 min-h-[90dvh] px-5">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/cuti', 'title' => 'Pengajuan Cuti']); ?>


    <div class="bg-gray-100/50 w-full h-[80dvh] rounded-md p-2">

        <div class="w-full p-3 bg-white rounded-md">
            <p class="text-sm">Diajukan Pada : <?= $model['tanggal_pengajuan'] ?></p>
            <p class="text-sm fw-bold">Status : <?= $model['status'] == '0' ? '<span class="text-yellow-500">Pending</span>' : ($model['status'] == '1' ? '<span class="text-green-500">Disetujui</span>' : '<span class="text-rose-500">Ditolak</span>')  ?></p>
        </div>
        <div class="w-full p-2 mt-2 text-black bg-white rounded-md">
            <p class="text-sm text-gray-500 capitalize">Alasan Cuti</p>
            <p><?= $model['alasan_cuti'] ?></p>
            <hr class="my-2">
            <p class="text-sm text-gray-500 capitalize">tanggal mulai  </p>
            <div class="flex space-x-3 text-sm text-gray-500">
<div class="flex flex-col space-y-3 text-sm text-gray-700">
    <?php foreach ($model->detailCuti as $detail): ?>
        <?php
            $status = $detail->status;
            $colorClass = match ($status) {
                0 => 'bg-yellow-400 text-yellow-900',
                1 => 'bg-green-500 text-green-100',
                2 => 'bg-red-500 text-red-100',
                default => 'bg-gray-400 text-gray-100',
            };
        ?>
        <div class="flex items-start space-x-3">
            <div class="flex items-center justify-center w-8 h-8 rounded-full <?= $colorClass ?>">
                <span class="text-xs font-bold">-</span>
            </div>
            <div>
                <p class="font-medium"><?= Yii::$app->formatter->asDate($detail->tanggal, 'long') ?></p>
                <p class="text-xs italic text-gray-500">
                    Keterangan: <?= $detail->keterangan ?? '-' ?>
                </p>
            </div>
        </div>
    <?php endforeach; ?>
</div>



            </div>
        </div>
        <div class="inline-flex items-center justify-center w-full">
            <hr class="w-64 h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
        </div>
        <div class="w-full p-2 mt-2 text-black bg-white rounded-md">
            <p class="text-sm text-gray-500 capitalize">tanggapan admin : </p>
            <p class="text-black"><?= $model['catatan_admin'] ?></p>
        </div>


    </div>


</section>