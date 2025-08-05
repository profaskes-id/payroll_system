<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view pengumuman</title>
</head>

<body class="pb-[250px]">


    <div class="w-full mx-auto sm:px-6 lg:px-8 h-[120dvh]  px-5 relative z-500">
        <?= $this->render('@backend/views/components/_header', ['title' => 'Pengumuman', 'link' => '/panel/home/pengumuman']); ?>
        <div class="p-5 rounded-md bg-gray-50">

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
</body>

</html>
</div>