<div class="max-w-[500px] mx-auto px-5 lg:px-8">



    <?= $this->render('@backend/views/components/_header', ['title' => 'Izin Pulang Cepat']); ?>
    <?php if ($model == null) : ?>
        <script>
            window.location.href = '/panel/home/pulang-cepat-create';
        </script>
    <?php else: ?>
        <section class="grid grid-cols-1 justify-center mt-2 ">

            <p class="text-sm py-2 text-gray-600">Alasan Pulang Cepat</p>
            <p class="p-2 bg-gray-100 rounded-md"><?= $model->alasan ?? '-' ?></p>
            <hr class="my-3">
            <p class="text-sm py-2 text-gray-600">Tanggapan Admin</p>
            <p class="p-2 bg-gray-100 rounded-md"><?= $model->catatan_admin ?? '-' ?></p>
            <hr class="my-3">
            <?php if ($model->statusPengajuan->nama_kode) : ?>
                <?php if ($model->statusPengajuan->kode == 0) : ?>
                    <p class="max-w-[100px] text-center text-yellow-600 bg-yellow-200 p-1 rounded-full ">Pending</p>
                <?php elseif ($model->statusPengajuan->kode == 1): ?>
                    <p class="max-w-[100px] text-center text-green-600 bg-green-200 p-1 rounded-full ">Disetujui</p>
                <?php elseif ($model->statusPengajuan->kode == 2): ?>
                    <p class="max-w-[100px] text-center text-red-600 bg-rose-200 p-1 rounded-full ">Ditolak</p>
                <?php else: ?>
                    <p class="max-w-[100px] text-center text-gray-600 bg-gray-200 p-1 rounded-full ">Ditolak</p>
                <?php endif ?>
            <?php else : ?>
                <p class="text-black">-</p>
            <?php endif ?>
        </section>
    <?php endif; ?>


</div>