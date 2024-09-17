<?php

use backend\models\MasterKode;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Expirience';

?>

<div class="container relative mx-auto px-5 min-h-[90dvh]">
    <?= $this->render('@backend/views/components/_header', ['title' => 'Data & Riwayat']); ?>
    <section>


        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab" data-tabs-toggle="#default-styled-tab-content" data-tabs-active-classes="text-purple-600 hover:text-purple-600 dark:text-purple-500 dark:hover:text-purple-500 border-purple-600 dark:border-purple-500" data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-styled-tab" data-tabs-target="#styled-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">RIW Pekerjaan
                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="dashboard-styled-tab" data-tabs-target="#styled-dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">RIW Pendidikan

                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-styled-tab" data-tabs-target="#styled-settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Keluarga

                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-styled-tab" data-tabs-target="#styled-pelatihan" type="button" role="tab" aria-controls="pelatihan" aria-selected="false">RIW Pelatihan

                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-styled-tab" data-tabs-target="#styled-kesehatan" type="button" role="tab" aria-controls="kesehatan" aria-selected="false">Kesehatan

                    </button>
                </li>

            </ul>
        </div>
        <div id="default-styled-tab-content">
            <div class="hidden p-4 rounded-lg bg-gray-100 dark:bg-gray-800" id="styled-profile" role="tabpanel" aria-labelledby="profile-tab">

                <h2 class=" font-semibold text-gray-900 ">Pengalaman Kerja <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"><?= count($pengalamanKerja) ?></span>
                </h2>
                <a href="/panel/home/expirience-pekerjaan-create" class=" my-3 w-full flex items-center justify-center py-1 px-4  gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-400 text-white hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
                <div class="mt-2 grid grid-cols-1 gap-y-3">

                    <?php if (!empty($pengalamanKerja)) : ?>
                        <?php foreach ($pengalamanKerja as $key => $value) : ?>

                            <div class=" bg-white rounded p-2 w-full flex flex-col space-y-1">
                                <div>
                                    <p class="font-semibold text-[#272727]"><?= $value['posisi'] ?></p>
                                    <p class="text-[13px] text-black/80"><?= $value['perusahaan'] ?></p>
                                </div>
                                <hr>
                                <div class="flex justify-between">
                                    <p class="text-[13px] text-black/80"><?= date('d-m-Y', strtotime($value['masuk_pada'])) ?> &nbsp;&nbsp;~&nbsp;&nbsp; <?= date('d-m-Y', strtotime($value['keluar_pada'])) ?></p>
                                    <div class="flex space-x-2">
                                        <a href="/panel/home/expirience-pekerjaan-update?id=<?= $value['id_pengalaman_kerja'] ?>" class="flex items-center justify-center">

                                            <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="black" fill-rule="evenodd" d="M21.455 5.416a.75.75 0 0 1-.096.943l-9.193 9.192a.75.75 0 0 1-.34.195l-3.829 1a.75.75 0 0 1-.915-.915l1-3.828a.8.8 0 0 1 .161-.312L17.47 2.47a.75.75 0 0 1 1.06 0l2.829 2.828a1 1 0 0 1 .096.118m-1.687.412L18 4.061l-8.518 8.518l-.625 2.393l2.393-.625z" clip-rule="evenodd" />
                                                    <path fill="black" d="M19.641 17.16a44.4 44.4 0 0 0 .261-7.04a.4.4 0 0 1 .117-.3l.984-.984a.198.198 0 0 1 .338.127a46 46 0 0 1-.21 8.372c-.236 2.022-1.86 3.607-3.873 3.832a47.8 47.8 0 0 1-10.516 0c-2.012-.225-3.637-1.81-3.873-3.832a46 46 0 0 1 0-10.67c.236-2.022 1.86-3.607 3.873-3.832a48 48 0 0 1 7.989-.213a.2.2 0 0 1 .128.34l-.993.992a.4.4 0 0 1-.297.117a46 46 0 0 0-6.66.255a2.89 2.89 0 0 0-2.55 2.516a44.4 44.4 0 0 0 0 10.32a2.89 2.89 0 0 0 2.55 2.516c3.355.375 6.827.375 10.183 0a2.89 2.89 0 0 0 2.55-2.516" />
                                                </svg></p>
                                            <p>
                                        </a>


                                        <form action="/panel/home/expirience-pekerjaan-delete" method="POST">
                                            <input type="text" name="id" value="<?= $value['id_pengalaman_kerja'] ?>" hidden>
                                            <button type="button" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class=" grid place-items-center" onclick="deleteClick(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="red" d="M10 5h4a2 2 0 1 0-4 0M8.5 5a3.5 3.5 0 1 1 7 0h5.75a.75.75 0 0 1 0 1.5h-1.32l-1.17 12.111A3.75 3.75 0 0 1 15.026 22H8.974a3.75 3.75 0 0 1-3.733-3.389L4.07 6.5H2.75a.75.75 0 0 1 0-1.5zm2 4.75a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0zM14.25 9a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-1.5 0v-7.5a.75.75 0 0 1 .75-.75m-7.516 9.467a2.25 2.25 0 0 0 2.24 2.033h6.052a2.25 2.25 0 0 0 2.24-2.033L18.424 6.5H5.576z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php else : ?>
                        <div class=" bg-white rounded p-2 w-full flex flex-col space-y-1">
                            <div>
                                <p class="py-2 text-[#272727]">Belum Ada Data</p>
                            </div>
                        </div>
                    <?php endif ?>


                </div>
            </div>
            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                <h2 class=" font-semibold text-gray-900 ">Riwayat Pendidikan <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"><?= count($riwayatPendidikan) ?></span> </h2>
                <a href="/panel/home/expirience-pendidikan-create" class=" my-3 w-full flex items-center justify-center py-1 px-4  gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-400 text-white hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
                <div class="mt-2 grid grid-cols-1 gap-y-3">

                    <?php if (!empty($riwayatPendidikan)) : ?>
                        <?php foreach ($riwayatPendidikan as $key => $value) : ?>
                            <?php $jenajngPendidikan = MasterKode::find()->select('nama_kode')->where(['nama_group' => 'jenjang-pendidikan', 'kode' => $value['jenjang_pendidikan']])->one(); ?>
                            <div class=" bg-white rounded p-2 w-full flex flex-col space-y-1">
                                <div>
                                    <p class="font-semibold text-[#272727]"><?= $jenajngPendidikan['nama_kode'] ?></p>
                                    <p class="text-[13px] text-black/80"><?= $value['institusi'] ?></p>
                                </div>
                                <hr>
                                <div class="flex justify-between">
                                    <p class="text-[13px] text-black/80"><?= $value['tahun_masuk']; ?> &nbsp;&nbsp;~&nbsp;&nbsp; <?= $value['tahun_keluar']; ?></p>
                                    <div class="flex space-x-2">
                                        <a href="/panel/home/expirience-pendidikan-update?id=<?= $value['id_riwayat_pendidikan'] ?>" class="flex items-center justify-center">

                                            <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="black" fill-rule="evenodd" d="M21.455 5.416a.75.75 0 0 1-.096.943l-9.193 9.192a.75.75 0 0 1-.34.195l-3.829 1a.75.75 0 0 1-.915-.915l1-3.828a.8.8 0 0 1 .161-.312L17.47 2.47a.75.75 0 0 1 1.06 0l2.829 2.828a1 1 0 0 1 .096.118m-1.687.412L18 4.061l-8.518 8.518l-.625 2.393l2.393-.625z" clip-rule="evenodd" />
                                                    <path fill="black" d="M19.641 17.16a44.4 44.4 0 0 0 .261-7.04a.4.4 0 0 1 .117-.3l.984-.984a.198.198 0 0 1 .338.127a46 46 0 0 1-.21 8.372c-.236 2.022-1.86 3.607-3.873 3.832a47.8 47.8 0 0 1-10.516 0c-2.012-.225-3.637-1.81-3.873-3.832a46 46 0 0 1 0-10.67c.236-2.022 1.86-3.607 3.873-3.832a48 48 0 0 1 7.989-.213a.2.2 0 0 1 .128.34l-.993.992a.4.4 0 0 1-.297.117a46 46 0 0 0-6.66.255a2.89 2.89 0 0 0-2.55 2.516a44.4 44.4 0 0 0 0 10.32a2.89 2.89 0 0 0 2.55 2.516c3.355.375 6.827.375 10.183 0a2.89 2.89 0 0 0 2.55-2.516" />
                                                </svg></p>
                                            <p>
                                        </a>


                                        <form action="/panel/home/expirience-pendidikan-delete" method="POST">
                                            <input type="text" name="id" value="<?= $value['id_riwayat_pendidikan'] ?>" hidden>
                                            <button type="button" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class=" grid place-items-center" onclick="deleteClick(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="red" d="M10 5h4a2 2 0 1 0-4 0M8.5 5a3.5 3.5 0 1 1 7 0h5.75a.75.75 0 0 1 0 1.5h-1.32l-1.17 12.111A3.75 3.75 0 0 1 15.026 22H8.974a3.75 3.75 0 0 1-3.733-3.389L4.07 6.5H2.75a.75.75 0 0 1 0-1.5zm2 4.75a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0zM14.25 9a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-1.5 0v-7.5a.75.75 0 0 1 .75-.75m-7.516 9.467a2.25 2.25 0 0 0 2.24 2.033h6.052a2.25 2.25 0 0 0 2.24-2.033L18.424 6.5H5.576z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>


                    <?php else : ?>
                        <div class=" bg-white rounded p-2 w-full flex flex-col space-y-1">
                            <div>
                                <p class="py-2 text-[#272727]">Belum Ada Data</p>
                            </div>

                        </div>
                    <?php endif ?>

                </div>
            </div>
            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-settings" role="tabpanel" aria-labelledby="settings-tab">

                <h2 class=" font-semibold text-gray-900 ">Data Keluarga <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"><?= count($keluarga) ?></span> </h2>
                <a href="/panel/home/data-keluarga-create" class=" my-3 w-full flex items-center justify-center py-1 px-4  gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-400 text-white hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
                <div class="mt-2 grid grid-cols-1 gap-y-3">

                    <?php if (!empty($keluarga)) : ?>
                        <?php foreach ($keluarga as $key => $value) : ?>
                            <div class=" bg-white rounded p-2 w-full flex flex-col space-y-1">
                                <div>
                                    <p class="font-semibold text-[#272727]">Nama : <?= $value['nama_anggota_keluarga'] ?></p>
                                    <p class="text-[13px] text-black/80">Hubungan : <?= $value->jenisHubungan->nama_kode ?></p>
                                    <p class="text-[13px] text-black/80">lahir pada : <?= $value['tahun_lahir'] ?> </p>
                                </div>
                                <hr>
                                <div class="flex justify-between">
                                    <p class="text-[13px] text-black/80">Pekerjaan : <?= $value['pekerjaan'] ?></p>
                                    <div class="flex space-x-2">
                                        <a href="/panel/home/data-keluarga-update?id=<?= $value['id_data_keluarga'] ?>" class="flex items-center justify-center">

                                            <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="black" fill-rule="evenodd" d="M21.455 5.416a.75.75 0 0 1-.096.943l-9.193 9.192a.75.75 0 0 1-.34.195l-3.829 1a.75.75 0 0 1-.915-.915l1-3.828a.8.8 0 0 1 .161-.312L17.47 2.47a.75.75 0 0 1 1.06 0l2.829 2.828a1 1 0 0 1 .096.118m-1.687.412L18 4.061l-8.518 8.518l-.625 2.393l2.393-.625z" clip-rule="evenodd" />
                                                    <path fill="black" d="M19.641 17.16a44.4 44.4 0 0 0 .261-7.04a.4.4 0 0 1 .117-.3l.984-.984a.198.198 0 0 1 .338.127a46 46 0 0 1-.21 8.372c-.236 2.022-1.86 3.607-3.873 3.832a47.8 47.8 0 0 1-10.516 0c-2.012-.225-3.637-1.81-3.873-3.832a46 46 0 0 1 0-10.67c.236-2.022 1.86-3.607 3.873-3.832a48 48 0 0 1 7.989-.213a.2.2 0 0 1 .128.34l-.993.992a.4.4 0 0 1-.297.117a46 46 0 0 0-6.66.255a2.89 2.89 0 0 0-2.55 2.516a44.4 44.4 0 0 0 0 10.32a2.89 2.89 0 0 0 2.55 2.516c3.355.375 6.827.375 10.183 0a2.89 2.89 0 0 0 2.55-2.516" />
                                                </svg></p>
                                            <p>
                                        </a>


                                        <form action="/panel/home/data-keluarga-delete" method="POST">
                                            <input type="text" name="id" value="<?= $value['id_data_keluarga'] ?>" hidden>
                                            <button type="button" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class=" grid place-items-center" onclick="deleteClick(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="red" d="M10 5h4a2 2 0 1 0-4 0M8.5 5a3.5 3.5 0 1 1 7 0h5.75a.75.75 0 0 1 0 1.5h-1.32l-1.17 12.111A3.75 3.75 0 0 1 15.026 22H8.974a3.75 3.75 0 0 1-3.733-3.389L4.07 6.5H2.75a.75.75 0 0 1 0-1.5zm2 4.75a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0zM14.25 9a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-1.5 0v-7.5a.75.75 0 0 1 .75-.75m-7.516 9.467a2.25 2.25 0 0 0 2.24 2.033h6.052a2.25 2.25 0 0 0 2.24-2.033L18.424 6.5H5.576z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>

                    <?php else: ?>
                        <div class=" bg-white rounded p-2 w-full flex flex-col space-y-1">
                            <div>
                                <p class="py-2 text-[#272727]">Belum Ada Data</p>
                            </div>

                        </div>
                    <?php endif ?>



                </div>
            </div>
            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-pelatihan" role="tabpanel" aria-labelledby="pelatihan-tab">

                <h2 class=" font-semibold text-gray-900 ">Riwayat Pelatihan <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"><?= count($RiwayatPelatihan) ?></span> </h2>
                <a href="/panel/home/riwayat-pelatihan-create" class=" my-3 w-full flex items-center justify-center py-1 px-4  gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-400 text-white hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
                <div class="mt-2 grid grid-cols-1 gap-y-3">

                    <?php if (!empty($RiwayatPelatihan)) : ?>
                        <?php foreach ($RiwayatPelatihan as $key => $value) : ?>
                            <div class=" bg-white rounded p-2 w-full flex flex-col space-y-1">
                                <div>
                                    <p class="font-semibold text-[#272727]"><?= $value['judul_pelatihan'] ?></p>
                                    <p class="text-[13px] text-black/80"><?= $value['penyelenggara'] ?></p>
                                </div>
                                <hr>
                                <div class="flex justify-between">
                                    <p class="text-[13px] text-black/80"><?= date('d-m-Y', strtotime($value['tanggal_mulai'])) ?> &nbsp;&nbsp;~&nbsp;&nbsp; <?= date('d-m-Y', strtotime($value['tanggal_selesai'])) ?></p>
                                    <div class="flex space-x-2">
                                        <a href="/panel/home/riwayat-pelatihan-update?id=<?= $value['id_riwayat_pelatihan'] ?>" class="flex items-center justify-center">

                                            <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="black" fill-rule="evenodd" d="M21.455 5.416a.75.75 0 0 1-.096.943l-9.193 9.192a.75.75 0 0 1-.34.195l-3.829 1a.75.75 0 0 1-.915-.915l1-3.828a.8.8 0 0 1 .161-.312L17.47 2.47a.75.75 0 0 1 1.06 0l2.829 2.828a1 1 0 0 1 .096.118m-1.687.412L18 4.061l-8.518 8.518l-.625 2.393l2.393-.625z" clip-rule="evenodd" />
                                                    <path fill="black" d="M19.641 17.16a44.4 44.4 0 0 0 .261-7.04a.4.4 0 0 1 .117-.3l.984-.984a.198.198 0 0 1 .338.127a46 46 0 0 1-.21 8.372c-.236 2.022-1.86 3.607-3.873 3.832a47.8 47.8 0 0 1-10.516 0c-2.012-.225-3.637-1.81-3.873-3.832a46 46 0 0 1 0-10.67c.236-2.022 1.86-3.607 3.873-3.832a48 48 0 0 1 7.989-.213a.2.2 0 0 1 .128.34l-.993.992a.4.4 0 0 1-.297.117a46 46 0 0 0-6.66.255a2.89 2.89 0 0 0-2.55 2.516a44.4 44.4 0 0 0 0 10.32a2.89 2.89 0 0 0 2.55 2.516c3.355.375 6.827.375 10.183 0a2.89 2.89 0 0 0 2.55-2.516" />
                                                </svg></p>
                                            <p>
                                        </a>


                                        <form action="/panel/home/riwayat-pelatihan-delete" method="POST">
                                            <input type="text" name="id" value="<?= $value['id_riwayat_pelatihan'] ?>" hidden>
                                            <button type="button" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class=" grid place-items-center" onclick="deleteClick(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="red" d="M10 5h4a2 2 0 1 0-4 0M8.5 5a3.5 3.5 0 1 1 7 0h5.75a.75.75 0 0 1 0 1.5h-1.32l-1.17 12.111A3.75 3.75 0 0 1 15.026 22H8.974a3.75 3.75 0 0 1-3.733-3.389L4.07 6.5H2.75a.75.75 0 0 1 0-1.5zm2 4.75a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0zM14.25 9a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-1.5 0v-7.5a.75.75 0 0 1 .75-.75m-7.516 9.467a2.25 2.25 0 0 0 2.24 2.033h6.052a2.25 2.25 0 0 0 2.24-2.033L18.424 6.5H5.576z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>

                    <?php else: ?>
                        <div class=" bg-white rounded p-2 w-full flex flex-col space-y-1">
                            <div>
                                <p class="py-2 text-[#272727]">Belum Ada Data</p>
                            </div>

                        </div>
                    <?php endif ?>



                </div>
            </div>
            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-kesehatan" role="tabpanel" aria-labelledby="kesehatan-tab">

                <h2 class=" font-semibold text-gray-900 ">Riwayat Kesehatan <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"><?= count($RiwayatKesehatan) ?></span> </h2>
                <a href="/panel/home/riwayat-kesehatan-create" class=" my-3 w-full flex items-center justify-center py-1 px-4  gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-400 text-white hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
                <div class="mt-2 grid grid-cols-1 gap-y-3">

                    <?php if (!empty($RiwayatKesehatan)) : ?>
                        <?php foreach ($RiwayatKesehatan as $key => $value) : ?>
                            <div class=" bg-white rounded p-2 w-full flex flex-col space-y-1">
                                <div>
                                    <p class="font-semibold text-[#272727]"><?= $value['nama_pengecekan'] ?></p>
                                    <p class="text-[13px] text-black/80"><?= $value['keterangan'] ?></p>
                                </div>
                                <hr>
                                <div class="flex justify-between">
                                    <p class="text-[13px] text-black/80"><?= date('d-m-Y', strtotime($value['tanggal'])) ?> </p>
                                    <div class="flex space-x-2">
                                        <a href="/panel/home/riwayat-kesehatan-update?id=<?= $value['id_riwayat_kesehatan'] ?>" class="flex items-center justify-center">

                                            <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="black" fill-rule="evenodd" d="M21.455 5.416a.75.75 0 0 1-.096.943l-9.193 9.192a.75.75 0 0 1-.34.195l-3.829 1a.75.75 0 0 1-.915-.915l1-3.828a.8.8 0 0 1 .161-.312L17.47 2.47a.75.75 0 0 1 1.06 0l2.829 2.828a1 1 0 0 1 .096.118m-1.687.412L18 4.061l-8.518 8.518l-.625 2.393l2.393-.625z" clip-rule="evenodd" />
                                                    <path fill="black" d="M19.641 17.16a44.4 44.4 0 0 0 .261-7.04a.4.4 0 0 1 .117-.3l.984-.984a.198.198 0 0 1 .338.127a46 46 0 0 1-.21 8.372c-.236 2.022-1.86 3.607-3.873 3.832a47.8 47.8 0 0 1-10.516 0c-2.012-.225-3.637-1.81-3.873-3.832a46 46 0 0 1 0-10.67c.236-2.022 1.86-3.607 3.873-3.832a48 48 0 0 1 7.989-.213a.2.2 0 0 1 .128.34l-.993.992a.4.4 0 0 1-.297.117a46 46 0 0 0-6.66.255a2.89 2.89 0 0 0-2.55 2.516a44.4 44.4 0 0 0 0 10.32a2.89 2.89 0 0 0 2.55 2.516c3.355.375 6.827.375 10.183 0a2.89 2.89 0 0 0 2.55-2.516" />
                                                </svg></p>
                                            <p>
                                        </a>


                                        <form action="/panel/home/riwayat-kesehatan-delete" method="POST">
                                            <input type="text" name="id" value="<?= $value['id_riwayat_kesehatan'] ?>" hidden>
                                            <button type="button" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class=" grid place-items-center" onclick="deleteClick(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="red" d="M10 5h4a2 2 0 1 0-4 0M8.5 5a3.5 3.5 0 1 1 7 0h5.75a.75.75 0 0 1 0 1.5h-1.32l-1.17 12.111A3.75 3.75 0 0 1 15.026 22H8.974a3.75 3.75 0 0 1-3.733-3.389L4.07 6.5H2.75a.75.75 0 0 1 0-1.5zm2 4.75a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0zM14.25 9a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-1.5 0v-7.5a.75.75 0 0 1 .75-.75m-7.516 9.467a2.25 2.25 0 0 0 2.24 2.033h6.052a2.25 2.25 0 0 0 2.24-2.033L18.424 6.5H5.576z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>

                    <?php else: ?>
                        <div class=" bg-white rounded p-2 w-full flex flex-col space-y-1">
                            <div>
                                <p class="py-2 text-[#272727]">Belum Ada Data</p>
                            </div>

                        </div>
                    <?php endif ?>



                </div>
            </div>

        </div>

    </section>
</div>




<div class="">
    <?= $this->render('@backend/views/components/_footer'); ?>
</div>




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