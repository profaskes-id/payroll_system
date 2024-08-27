<div class="max-w-[500px] mx-auto sm:px-6 lg:px-8 min-h-[90dvh] px-5">
    <?= $this->render('@backend/views/components/_header', ['title' => 'Pengumuman']); ?>
    <div class="bg-gray-50 rounded-md p-5">

        <h1 class="text-[#272727] text-2xl font-bold my-5"><?= $pengumuman->judul ?></h1>
        <h1 class="text-[#272727]//80 my-5"><?= $pengumuman->deskripsi ?></h1>

        <p class="text-gray-500">Dibuat Pada : <?= date('d-M-Y', strtotime($pengumuman->dibuat_pada)) ?></p>
        <?php if ($pengumuman->update_pada != null) : ?>
            <p>Diperbarui Pada : <?= date('d-M-Y', strtotime($pengumuman->update_pada)) ?></p>
        <?php endif ?>
    </div>
</div>




<div class="fixed bottom-0 left-0 right-0 z-50">
    <?= $this->render('@backend/views/components/_footer'); ?>
</div>