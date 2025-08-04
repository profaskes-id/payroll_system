<?php

use backend\models\MasterKode;

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\/pengajuan/lembur-detail/ $this */
/** @var backend\models\Absensi $model */

$this->title = 'Create Penajuan Tugas Luar';
$this->params['breadcrumbs'][] = ['label' => 'Penajuan Tugas Luar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$izin = MasterKode::find()->where(['nama_group' => 'status-hadir'])->andWhere(['!=', 'nama_kode', 'Hadir'])->orderBy(['urutan' => SORT_ASC])->all();

?>

<div class="w-full mx-auto sm:px-6 lg:px-8 min-h-[90dvh] px-5 relative z-50">
    <?php // $this->render('@backend/views/components/_header', ['link' => '/panel/home', 'title' => 'Pengajuan Tugas Luar']); 
    ?>



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

        <a href="/panel/pengajuan/tugas-luar-create" class="flex items-center justify-center w-full px-4 py-1 my-3 text-sm font-medium text-white bg-blue-400 border border-transparent rounded-lg gap-x-2 hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
        <div class="hidden p-4 mb-20 rounded-lg bg-gray-50 dark:bg-gray-800" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <?php if (!empty($pengajuanTugasLuar)) : ?>
                <div class="grid w-full grid-cols-1 gap-y-4 ">
                    <?php foreach ($pengajuanTugasLuar as $key => $value) : ?>
                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/tugas-luar-detail', 'id' => $value['id_tugas_luar']]) ?>">
                            <div class="relative grid grid-cols-12 gap-5">

                                <div class="w-full col-span-12 p-2 text-sm bg-white rounded-md">

                                    <div class="flex items-center justify-between">
                                        <p class="text-[15px] capitalize text-gray-500"><?php echo date('d M Y H:i', strtotime($value['created_at'])); ?>
                                        </p>
                                        <p class="text-[15px] capitalize text-gray-500"><?= count($value->detailTugasLuars); ?> agenda </p>
                                    </div>
                                    <div class="absolute -top-2 -left-1">
                                        <div class="flex items-center justify-between py-2 ">

                                            <div class="">
                                                <?php if ($value['status_pengajuan'] == '0') : ?>
                                                    <div class="px-1 py-1 font-semibold text-black bg-yellow-300 rounded-full "></div>
                                                <?php elseif ($value['status_pengajuan'] == '1') : ?>
                                                    <div class="px-1 py-1 font-semibold text-white bg-green-500 rounded-full "></div>
                                                <?php elseif ($value['status_pengajuan'] == '2') : ?>
                                                    <div class="px-1 py-1 font-semibold text-white bg-red-500 rounded-full "></div>
                                                <?php endif ?>
                                            </div>
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

    <div class="hidden p-4 mb-20 rounded-lg bg-gray-50 dark:bg-gray-800" id="settings" role="tabpanel" aria-labelledby="settings-tab">
        <div class="grid w-full grid-cols-1 gap-y-4 ">
            <?php if (!empty($pengajuanTugasLuar)) : ?>
                <?php foreach ($pengajuanTugasLuar as $key => $value) : ?>
                    <?php if ($value['status_pengajuan'] == '1') : ?>
                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/tugas-luar-detail', 'id' => $value['id_tugas_luar']]) ?>">
                            <div class="relative grid grid-cols-12 gap-5">

                                <div class="w-full col-span-12 p-2 text-sm bg-white rounded-md">

                                    <div class="flex items-center justify-between">
                                        <p class="text-[15px] capitalize text-gray-500"><?php echo date('d M Y H:i', strtotime($value['created_at'])); ?>
                                        </p>
                                        <p class="text-[15px] capitalize text-gray-500"><?= count($value->detailTugasLuars); ?> agenda </p>
                                    </div>
                                    <div class="absolute -top-2 -left-1">
                                        <div class="flex items-center justify-between py-2 ">

                                            <div class="">
                                                <?php if ($value['status_pengajuan'] == '0') : ?>
                                                    <div class="px-1 py-1 font-semibold text-black bg-yellow-300 rounded-full "></div>
                                                <?php elseif ($value['status_pengajuan'] == '1') : ?>
                                                    <div class="px-1 py-1 font-semibold text-white bg-green-500 rounded-full "></div>
                                                <?php elseif ($value['status_pengajuan'] == '2') : ?>
                                                    <div class="px-1 py-1 font-semibold text-white bg-red-500 rounded-full "></div>
                                                <?php endif ?>
                                            </div>
                                        </div>
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
    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
        <div class="grid w-full grid-cols-1 gap-y-4 ">
            <?php if (!empty($pengajuanTugasLuar)) : ?>
                <?php foreach ($pengajuanTugasLuar as $key => $value) : ?>
                    <?php if ($value['status_pengajuan'] == '2') : ?>

                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/tugas-luar-detail', 'id' => $value['id_tugas_luar']]) ?>">
                            <div class="relative grid grid-cols-12 gap-5">

                                <div class="w-full col-span-12 p-2 text-sm bg-white rounded-md">

                                    <div class="flex items-center justify-between">
                                        <p class="text-[15px] capitalize text-gray-500"><?php echo date('d M Y H:i', strtotime($value['created_at'])); ?>
                                        </p>
                                        <p class="text-[15px] capitalize text-gray-500"><?= count($value->detailTugasLuars); ?> agenda </p>
                                    </div>
                                    <div class="absolute -top-2 -left-1">
                                        <div class="flex items-center justify-between py-2 ">

                                            <div class="">
                                                <?php if ($value['status_pengajuan'] == '0') : ?>
                                                    <div class="px-1 py-1 font-semibold text-black bg-yellow-300 rounded-full "></div>
                                                <?php elseif ($value['status_pengajuan'] == '1') : ?>
                                                    <div class="px-1 py-1 font-semibold text-white bg-green-500 rounded-full "></div>
                                                <?php elseif ($value['status_pengajuan'] == '2') : ?>
                                                    <div class="px-1 py-1 font-semibold text-white bg-red-500 rounded-full "></div>
                                                <?php endif ?>
                                            </div>
                                        </div>
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