<?php

use backend\models\MasterKode;

$this->title = 'Expirience';

?>

<div class="container relative mx-auto px-5 min-h-[90dvh]">





    <?= $this->render('@backend/views/components/_header'); ?>


    <div>
        <div class="flex justify-between items-center pt-10 ">
            <h1 class="font-bold text-md lg:text-3xl">Pengalaman Kerja</h1>
            <a href="/panel/home/expirience-pekerjaan-create" class="px-2 py-1 lg:px-5 lg:py-2 rounded-md bg-sky-500 text-white font-semibold lg:font-bold">Add New</a>
        </div>
        <div class="grid grid-cols-12 gap-y-5 w-full mt-8">


            <?php foreach ($pengalamanKerja as $key => $value) : ?>
                <article class="col-span-12 rounded-xl border-2 border-gray-100 shadow ">
                    <div class="flex items-start gap-4 p-4 sm:p-6 lg:p-8">
                        <a href="/panel/home/expirience-pekerjaan-view?id=<?= $value['id_pengalaman_kerja'] ?>" class="block shrink-0">
                            <img
                                alt=""
                                src="https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8YXZhdGFyfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=800&q=60"
                                class="size-14 rounded-lg object-cover" />
                        </a>

                        <div>
                            <h3 class=" text-lg font-medium sm:text-xl flex justify-start items-start space-y-1 flex-col">
                                <a href="/panel/home/expirience-pekerjaan-view?id=<?= $value['id_pengalaman_kerja'] ?>" class="hover:underline"> <?= $value['posisi'] ?> </a>
                                <a href="/panel/home/expirience-pekerjaan-view?id=<?= $value['id_pengalaman_kerja'] ?>" class="hover:underline text-gray-700"><?= $value['perusahaan'] ?> </a>
                            </h3>

                            <p class="mt-1 text-sm text-gray-700 flex justify-start items-center space-x-3">
                                <span class="hover:underline"><?= date('d-M-Y', strtotime($value['masuk_pada'])); ?> </span>
                                <span>-</span>
                                <span class="hover:underline"><?= date('d-M-Y', strtotime($value['keluar_pada'])); ?> </span>
                            </p>


                        </div>
                    </div>

                </article>


            <?php endforeach ?>

        </div>
    </div>



    <div>
        <div class="flex justify-between items-center pt-10 ">
            <h1 class="font-bold text-md lg:text-3xl">Riwayat Pendidikan</h1>
            <a href="/panel/home/expirience-pendidikan-create" class="px-2 py-1 lg:px-5 lg:py-2 rounded-md bg-sky-500 text-white font-semibold lg:font-bold">Add New</a>
        </div>
        <div class="grid grid-cols-12 gap-y-5 w-full mt-8">


            <?php foreach ($riwayatPendidikan as $key => $value) : ?>
                <article class="rounded-xl border-2 border-gray-100 bg-white col-span-12">
                    <div class="flex items-start gap-4 p-4 sm:p-6 lg:p-8">
                        <a href="/panel/home/expirience-pendidikan-view?id=<?= $value['id_riwayat_pendidikan'] ?>" class="block shrink-0">
                            <img
                                alt=""
                                src="https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8YXZhdGFyfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=800&q=60"
                                class="size-14 rounded-lg object-cover" />
                        </a>

                        <div>
                            <h3 class=" text-lg font-medium sm:text-xl flex justify-start items-start space-y-1 flex-col">
                                <?php

                                $jenajngPendidikan = MasterKode::find()->select('nama_kode')->where(['nama_group' => 'jenjang-pendidikan', 'kode' => $value['jenjang_pendidikan']])->one();
                                ?>
                                <a href="/panel/home/expirience-pendidikan-view?id=<?= $value['id_riwayat_pendidikan'] ?>" class="hover:underline"><?= $jenajngPendidikan['nama_kode'] ?> </a>
                                <a href="/panel/home/expirience-pendidikan-view?id=<?= $value['id_riwayat_pendidikan'] ?>" class="hover:underline text-gray-700"><?= $value['institusi'] ?> </a>
                            </h3>

                            <p class="mt-1 text-sm text-gray-700 flex justify-start items-center space-x-3">
                                <span class="hover:underline"><?= date('d-M-Y', strtotime($value['tahun_masuk'])); ?> </span>
                                <span>-</span>
                                <span class="hover:underline"><?= date('d-M-Y', strtotime($value['tahun_keluar'])); ?> </span>
                            </p>


                        </div>
                    </div>

                </article>


            <?php endforeach ?>

        </div>
    </div>
</div>


<div class="lg:hidden">

    <?= $this->render('@backend/views/components/_footer'); ?>
</div>