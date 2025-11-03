<?php

use backend\models\MasterKode;
use backend\models\PengajuanLembur;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\/pengajuan/lembur-detail/ $this */
/** @var backend\models\Absensi $model */

$this->title = 'Create Pengajuan Lembur';
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Lembur', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$izin = MasterKode::find()->where(['nama_group' => 'status-hadir'])->andWhere(['!=', 'nama_kode', 'Hadir'])->orderBy(['urutan' => SORT_ASC])->all();

?>

<div class="w-full mx-auto sm:px-2 lg:px-8 min-h-[90dvh] px-2 relative z-50">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/home', 'title' => 'Pengajuan Lembur']); ?>



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

        <a href="/panel/pengajuan/lembur-create" class="flex items-center justify-center w-full px-4 py-1 my-3 text-sm font-medium text-white bg-blue-400 border border-transparent rounded-lg gap-x-2 hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
        <div class="hidden mb-20 rounded-lg bg-gray-50 md:p-2 dark:bg-gray-800" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <?php if (!empty($pengajuanLembur)) : ?>
                <div class="grid w-full grid-cols-1 gap-y-4 ">
                    <?php foreach ($pengajuanLembur as $key => $value) : ?>
                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/lembur-detail/', 'id' => $value['id_pengajuan_lembur']]) ?>">
                            <div class="grid grid-cols-12 gap-5 ">

                                <div class="w-full col-span-12 p-2 text-sm bg-white rounded-md">


                                    <p class="text-[15px] capitalize  text-gray-500">Pengajuan Lembur : <strong><?= date('d M Y', strtotime($value['tanggal'])) ?></strong></p>
                                    <div class="flex space-x-3 text-gray-500">
                                        <p><?= date('H:i', strtotime($value['jam_mulai'])) ?></p>
                                        <span>&nbsp;~&nbsp;&nbsp;</span>
                                        <p><?= date('H:i', strtotime($value['jam_selesai'])) ?></p>
                                    </div>
                                    <hr class="my-2 ">
                                    <div class="flex items-center justify-between py-2 ">

                                        <div class="">
                                            <?php if ($value['status'] == '0') : ?>
                                                <span class="px-5 py-1 font-semibold text-black bg-yellow-300 rounded-full ">Pending</span>
                                            <?php elseif ($value['status'] == '1') : ?>
                                                <span class="px-5 py-1 font-semibold text-white bg-green-500 rounded-full ">Disetujui</span>
                                            <?php elseif ($value['status'] == '2') : ?>
                                                <span class="px-5 py-1 font-semibold text-white bg-red-500 rounded-full ">Ditolak</span>
                                            <?php endif ?>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="w-full "></div>
                <div class="w-full p-2 text-sm bg-white rounded-md">
                    <p class="text-center">Tidak ada data</p>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="hidden mb-20 rounded-lg bg-gray-50 dark:bg-gray-800" id="settings" role="tabpanel" aria-labelledby="settings-tab">
        <div class="grid w-full grid-cols-1 gap-y-4 ">
            <?php if (!empty($pengajuanLembur)) : ?>
                <?php foreach ($pengajuanLembur as $key => $value) : ?>
                    <?php if ($value['status'] == '1') : ?>
                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/lembur-detail/', 'id' => $value['id_pengajuan_lembur']]) ?>">
                            <div class="grid grid-cols-12 gap-5 ">
                                <div class="w-full col-span-12 p-2 text-sm bg-white rounded-md">
                                    <p class="text-[15px] capitalize  text-gray-500">Pengajuan Lembur : <strong><?= date('d M Y', strtotime($value['tanggal'])) ?></strong></p>
                                    <hr class="w-2/3 my-2">
                                    <div class="flex space-x-3 text-gray-500">
                                        <p><?= date('H:i', strtotime($value['jam_mulai'])) ?></p>
                                        <span>&nbsp;~&nbsp;&nbsp;</span>
                                        <p><?= date('H:i', strtotime($value['jam_selesai'])) ?></p>
                                    </div>

                                </div>
                            </div>
                        </a>
                    <?php endif ?>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="w-full p-2 text-sm bg-white rounded-md">
                    <p class="text-center">Tidak ada data</p>
                </div>
            <?php endif ?>
        </div>
    </div>
    <div class="hidden rounded-lg bg-gray-50 dark:bg-gray-800" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
        <div class="grid w-full grid-cols-1 gap-y-4 ">
            <?php if (!empty($pengajuanLembur)) : ?>
                <?php foreach ($pengajuanLembur as $key => $value) : ?>
                    <?php if ($value['status'] == '2') : ?>

                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/lembur-detail/', 'id' => $value['id_pengajuan_lembur']]) ?>">
                            <div class="grid grid-cols-1 gap-5 ">
                                <div class="w-full col-span-1 p-2 text-sm bg-white rounded-md">
                                    <p class="text-[15px] capitalize  text-gray-500">Pengajuan Lembur : <strong><?= date('d M Y', strtotime($value['tanggal'])) ?></strong></p>
                                    <hr class="w-2/3 my-2">
                                    <div class="flex space-x-3 text-gray-500">
                                        <p><?= date('H:i', strtotime($value['jam_mulai'])) ?></p>
                                        <span>&nbsp;~&nbsp;&nbsp;</span>
                                        <p><?= date('H:i', strtotime($value['jam_selesai'])) ?></p>
                                    </div>

                                </div>
                            </div>
                        </a>
                    <?php endif ?>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="w-full "></div>
                <div class="w-full p-2 text-sm bg-white rounded-md">
                    <p class="text-center">Tidak ada data</p>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

</div>