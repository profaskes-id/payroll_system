<?php

use backend\models\MasterKode;
use backend\models\PengajuanLembur;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\/pengajuan/lembur-detail/ $this */
/** @var backend\models\Absensi $model */

$this->title = 'Create Penajuan Lembur';
$this->params['breadcrumbs'][] = ['label' => 'Penajuan Lembur', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$izin = MasterKode::find()->where(['nama_group' => 'status-hadir'])->andWhere(['!=', 'nama_kode', 'Hadir'])->orderBy(['urutan' => SORT_ASC])->all();

?>

<div class="max-w-[500px] mx-auto sm:px-6 lg:px-8 min-h-[90dvh] px-5">
    <?= $this->render('@backend/views/components/_header', ['title' => 'Pengajuan Lembur']); ?>



    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
            <li class="me-2" role="presentation">
                <button class="flex justify-between items-center space-x-1 p-4 border-b-2 rounded-t-lg " id="profile-tab" data-tabs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                    <div class="w-4 h-4 bg-blue-300 rounded-full"></div>
                    <span>
                        All
                    </span>
                </button>
            </li>

            <li class="me-2" role="presentation">
                <button class="flex justify-between items-center space-x-1  p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-tab" data-tabs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">
                    <div class="w-4 h-4 bg-green-300 rounded-full"></div>
                    <span>
                        Disetujui
                    </span>
                </button>
            </li>
            <li role="presentation">
                <button class="flex justify-between items-center space-x-1  p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="contacts-tab" data-tabs-target="#contacts" type="button" role="tab" aria-controls="contacts" aria-selected="false">
                    <div class="w-4 h-4 bg-red-300 rounded-full"></div>
                    <span>
                        Ditolak
                    </span>
                </button>
            </li>
        </ul>
    </div>
    <div id="default-tab-content">

        <a href="/panel/pengajuan/lembur-create" class=" my-3 w-full flex items-center justify-center py-1 px-4  gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-400 text-white hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800 mb-20" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <?php if (!empty($pengajuanLembur)) : ?>
                <div class="w-full grid grid-cols-1 gap-y-4 ">
                    <?php foreach ($pengajuanLembur as $key => $value) : ?>
                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/lembur-detail/', 'id' => $value['id_pengajuan_lembur']]) ?>">
                            <div class="grid grid-cols-12 gap-5 ">

                                <div class="p-2 text-sm col-span-11  bg-white rounded-md w-full">


                                    <p class="text-[15px] capitalize  text-gray-500">Pengajuan Lembur : <strong><?= date('d M Y', strtotime($value['tanggal'])) ?></strong></p>
                                    <div class="flex space-x-3 text-gray-500">
                                        <p><?= date('H:i', strtotime($value['jam_mulai'])) ?></p>
                                        <span>&nbsp;~&nbsp;&nbsp;</span>
                                        <p><?= date('H:i', strtotime($value['jam_selesai'])) ?></p>
                                    </div>
                                    <hr class=" my-2">
                                    <div class="flex justify-between items-center py-2 ">

                                        <div class="">
                                            <?php if ($value['status'] == '0') : ?>
                                                <span class=" px-5 py-1 bg-yellow-300 text-black font-semibold rounded-full ">Pending</span>
                                            <?php elseif ($value['status'] == '1') : ?>
                                                <span class=" px-5 py-1 bg-green-500 text-white font-semibold rounded-full ">Disetujui</span>
                                            <?php elseif ($value['status'] == '2') : ?>
                                                <span class=" px-5 py-1 bg-red-500 text-white font-semibold rounded-full ">Ditolak</span>
                                            <?php endif ?>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="w-full  "></div>
                <div class="p-2 text-sm   bg-white rounded-md w-full">
                    <p class="text-center">Tidak ada data</p>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800 mb-20" id="settings" role="tabpanel" aria-labelledby="settings-tab">
        <div class="w-full grid grid-cols-1 gap-y-4 ">
            <?php if (!empty($pengajuanLembur)) : ?>
                <?php foreach ($pengajuanLembur as $key => $value) : ?>
                    <?php if ($value['status'] == '1') : ?>
                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/lembur-detail/', 'id' => $value['id_pengajuan_lembur']]) ?>">
                            <div class="grid grid-cols-12 gap-5 ">
                                <div class="p-2 text-sm col-span-12  bg-white rounded-md w-full">
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
                <div class="p-2 text-sm   bg-white rounded-md w-full">
                    <p class="text-center">Tidak ada data</p>
                </div>
            <?php endif ?>
        </div>
    </div>
    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
        <div class="w-full grid grid-cols-1 gap-y-4 ">
            <?php if (!empty($pengajuanLembur)) : ?>
                <?php foreach ($pengajuanLembur as $key => $value) : ?>
                    <?php if ($value['status'] == '2') : ?>

                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/lembur-detail/', 'id' => $value['id_pengajuan_lembur']]) ?>">
                            <div class="grid grid-cols-1 gap-5 ">
                                <div class="p-2 text-sm col-span-1 bg-white rounded-md w-full">
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
                <div class="w-full  "></div>
                <div class="p-2 text-sm   bg-white rounded-md w-full">
                    <p class="text-center">Tidak ada data</p>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

</div>


<div class="fixed bottom-0 left-0 right-0 z-50">
    <?= $this->render('@backend/views/components/_footer'); ?>
</div>