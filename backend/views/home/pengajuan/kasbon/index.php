<?php

use backend\models\MasterKode;


/** @var yii\web\/pengajuan/kasbon-detail/ $this */
/** @var backend\models\Absensi $model */

$this->title = 'Tambah Pengajuan Kasbon';
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Cuti', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$izin = MasterKode::find()->where(['nama_group' => 'status-hadir'])->andWhere(['!=', 'nama_kode', 'Hadir'])->orderBy(['urutan' => SORT_ASC])->all();

?>

<div class="w-full mx-auto md:px-5 lg:px-8 min-h-[90dvh]  relative z-50">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/home', 'title' => 'Pengajuan Kasbon']); ?>



    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
            <li class="me-2" role="presentation">
                <button class="flex items-center justify-between p-4 space-x-1 border-b-2 rounded-t-lg " id="profile-tab" data-tabs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                    <div class="w-4 h-4 bg-blue-300 rounded-full"></div>
                    <span>
                        All
                    </span>
                </button>
            </li>

            <li class="me-2" role="presentation">
                <button class="flex items-center justify-between p-4 space-x-1 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-tab" data-tabs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">
                    <div class="w-4 h-4 bg-green-300 rounded-full"></div>
                    <span>
                        Disetujui
                    </span>
                </button>
            </li>
            <li role="presentation">
                <button class="flex items-center justify-between p-4 space-x-1 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="contacts-tab" data-tabs-target="#contacts" type="button" role="tab" aria-controls="contacts" aria-selected="false">
                    <div class="w-4 h-4 bg-red-300 rounded-full"></div>
                    <span>
                        Ditolak
                    </span>
                </button>
            </li>
        </ul>
    </div>
    <div id="default-tab-content">
        <a href="/panel/pengajuan/kasbon-create"
            class="flex items-center justify-center w-full px-4 py-1 my-3 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600">
            + Tambah Kasbon Baru
        </a>

        <!-- Semua pengajuan -->
        <div class="hidden mb-20 rounded-lg bg-gray-50 dark:bg-gray-800" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <?php if (!empty($pengajuanKasbon)) : ?>
                <div class="grid w-full grid-cols-1 gap-y-4">
                    <?php foreach ($pengajuanKasbon as $value) : ?>
                        <a href="<?= \yii\helpers\Url::to(['/pengajuan/kasbon-detail', 'id' => $value['id_pengajuan_kasbon']]) ?>">
                            <div class="w-full p-3 text-sm transition bg-white rounded-md shadow-sm hover:shadow-md">
                                <p class="font-semibold text-gray-800">
                                    Jumlah Kasbon: Rp <?= number_format($value['jumlah_kasbon'], 0, ',', '.') ?>
                                </p>
                                <p class="text-xs text-gray-600">
                                    Lama Cicilan: <?= $value['lama_cicilan'] ?> bulan <br>
                                    Angsuran: Rp <?= number_format($value['angsuran_perbulan'], 0, ',', '.') ?> / bulan
                                </p>

                                <p class="mt-2 text-xs italic text-gray-500">
                                    <?= \yii\helpers\StringHelper::truncateWords($value['keterangan'], 10, '...') ?>
                                </p>

                                <hr class="my-2">
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <p>Diajukan: <?= date('d-m-Y', strtotime($value['tanggal_pengajuan'])) ?></p>
                                    <div>
                                        <?php if ($value['status'] == '0'): ?>
                                            <span class="px-3 py-1 font-semibold text-black bg-yellow-300 rounded-full">Pending</span>
                                        <?php elseif ($value['status'] == '1'): ?>
                                            <span class="px-3 py-1 font-semibold text-white bg-green-500 rounded-full">Disetujui</span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 font-semibold text-white bg-red-500 rounded-full">Ditolak</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="w-full p-3 text-sm text-center bg-white rounded-md">Tidak ada data</div>
            <?php endif; ?>
        </div>

        <!-- Disetujui -->
        <div class="hidden py-3 mb-20 rounded-lg bg-gray-50" id="settings" role="tabpanel" aria-labelledby="settings-tab">
            <div class="grid w-full grid-cols-1 gap-y-4">
                <?php if (!empty($pengajuanKasbon)) : ?>
                    <?php foreach ($pengajuanKasbon as $value) : ?>
                        <?php if ($value['status'] == '1') : ?>
                            <a href="<?= \yii\helpers\Url::to(['/pengajuan/kasbon-detail', 'id' => $value['id_pengajuan_kasbon']]) ?>">
                                <div class="w-full p-3 text-sm transition bg-white rounded-md shadow-sm hover:shadow-md">
                                    <p class="font-semibold text-gray-800">
                                        Jumlah Kasbon: Rp <?= number_format($value['jumlah_kasbon'], 0, ',', '.') ?>
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        Lama Cicilan: <?= $value['lama_cicilan'] ?> bulan
                                    </p>
                                    <p class="mt-2 text-xs italic text-gray-500">
                                        <?= \yii\helpers\StringHelper::truncateWords($value['keterangan'], 10, '...') ?>
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">Tanggal Pengajuan: <?= date('d-m-Y', strtotime($value['tanggal_pengajuan'])) ?></p>
                                </div>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="w-full p-3 text-sm text-center bg-white rounded-md">Tidak ada data</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="hidden rounded-lg bg-gray-50" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
            <div class="grid w-full grid-cols-1 gap-y-4">
                <?php if (!empty($pengajuanKasbon)) : ?>
                    <?php foreach ($pengajuanKasbon as $value) : ?>
                        <?php if ($value['status'] == '2') : ?>
                            <a href="<?= \yii\helpers\Url::to(['/pengajuan/kasbon-detail', 'id' => $value['id_pengajuan_kasbon']]) ?>">
                                <div class="w-full p-3 text-sm transition bg-white rounded-md shadow-sm hover:shadow-md">
                                    <p class="font-semibold text-gray-800">
                                        Jumlah Kasbon: Rp <?= number_format($value['jumlah_kasbon'], 0, ',', '.') ?>
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        Lama Cicilan: <?= $value['lama_cicilan'] ?> bulan
                                    </p>
                                    <p class="mt-2 text-xs italic text-gray-500">
                                        <?= \yii\helpers\StringHelper::truncateWords($value['keterangan'], 10, '...') ?>
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">Tanggal Pengajuan: <?= date('d-m-Y', strtotime($value['tanggal_pengajuan'])) ?></p>
                                </div>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="w-full p-3 text-sm text-center bg-white rounded-md">Tidak ada data</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

</div>