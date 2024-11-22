<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman</title>
</head>

<?php
// Fungsi untuk memotong deskripsi dan menambahkan link
function getShortDescription($description, $wordLimit = 5)
{
    $words = explode(' ', $description);
    if (count($words) > $wordLimit) {
        $shortDescription = implode(' ', array_slice($words, 0, $wordLimit)) . '...';
    } else {
        $shortDescription = $description;
    }

    return $shortDescription;
}

?>

<body>

    <div class="container px-5">

        <?= $this->render('@backend/views/components/_header', ['title' => 'Pengumuman']); ?>
        <div class="grid grid-cols-12  mb-20   w-full mt-5 min-w-screen   relative">

            <?php if (empty($pengumuman)) : ?>
                <h1 class="text-center font-semibold">Tidak Ada Pengumuman</h1>
            <?php else : ?>
                <?php foreach ($pengumuman as $value) : ?>



                    <article class="flex bg-white transition hover:shadow-xl col-span-12 md:col-span-4 mb-10 md:mb-5 mx-0 md:mx-5">
                        <div class="rotate-180 p-2 [writing-mode:_vertical-lr]">
                            <time
                                datetime="2022-10-10"
                                class="flex items-center justify-between gap-4 text-xs font-bold uppercase text-gray-900">
                                <span><?= date('Y', strtotime($value->dibuat_pada)) ?></span>
                                <span class="w-px flex-1 bg-gray-900/10"></span>
                                <span><?= date('d-M', strtotime($value->dibuat_pada)) ?></span>
                            </time>
                        </div>



                        <div class="flex flex-1 flex-col justify-between">
                            <div class="border-s border-gray-900/10 p-4 sm:border-l-transparent sm:p-6">
                                <a href="#">
                                    <h3 class="font-bold uppercase text-gray-900">
                                        <?php
                                        echo getShortDescription($value->judul);
                                        ?>
                                    </h3>
                                </a>

                                <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-700">

                                    <?php
                                    echo getShortDescription($value->deskripsi);
                                    ?>
                                </p>
                            </div>

                            <div class="sm:flex sm:items-end sm:justify-end">
                                <a
                                    href="/panel/home/pengumuman-view?id_pengumuman=<?= $value->id_pengumuman ?>"
                                    class="block bg-yellow-300 px-5 py-3 text-center text-xs font-bold uppercase text-gray-900 transition hover:bg-yellow-400">
                                    Selengkapnya
                                </a>
                            </div>
                        </div>
                    </article>




                <?php endforeach ?>
            <?php endif ?>

        </div>
    </div>

</body>

</html>