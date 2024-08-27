<?php

use backend\models\MasterKode;
use backend\models\PengajuanCuti;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\/pengajuan/dinas-detail/ $this */
/** @var backend\models\Absensi $model */

$this->title = 'Tambah Pengajuan Dinas';
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Cutos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$izin = MasterKode::find()->where(['nama_group' => 'status-hadir'])->andWhere(['!=', 'nama_kode', 'Hadir'])->orderBy(['urutan' => SORT_ASC])->all();

?>

<div class="max-w-[500px] mx-auto sm:px-6 lg:px-8 min-h-[90dvh] px-5">
    <?= $this->render('@backend/views/components/_header', ['title' => 'Pengajuan Dinas']); ?>



    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
            <li class="me-2" role="presentation">
                <button class="flex justify-between items-center space-x-1 p-4 border-b-2 rounded-t-lg " id="profile-tab" data-tabs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                    <div class="w-4 h-4 bg-blue-100 rounded-full"></div>
                    <span>
                        All
                    </span>
                </button>
            </li>

            <li class="me-2" role="presentation">
                <button class="flex justify-between items-center space-x-1  p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-tab" data-tabs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">
                    <div class="w-4 h-4 bg-green-100 rounded-full"></div>
                    <span>
                        Disetujui
                    </span>
                </button>
            </li>
            <li role="presentation">
                <button class="flex justify-between items-center space-x-1  p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="contacts-tab" data-tabs-target="#contacts" type="button" role="tab" aria-controls="contacts" aria-selected="false">
                    <div class="w-4 h-4 bg-red-100 rounded-full"></div>
                    <span>
                        Ditolak
                    </span>
                </button>
            </li>
        </ul>
    </div>
    <div id="default-tab-content">

        <a href="/panel/pengajuan/dinas-create" class=" my-3 w-full flex items-center justify-center py-1 px-4  gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-400 text-white hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800 mb-20" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <?php if (!empty($pengajuanDinas)) : ?>
                <div class="w-full grid grid-cols-1 gap-y-4 ">
                    <?php foreach ($pengajuanDinas as $key => $value) : ?>
                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/dinas-detail/', 'id' => $value['id_pengajuan_dinas']]) ?>">
                            <div class="grid grid-cols-12 gap-5 ">
                                <?php if ($value['status'] == '0') : ?>
                                    <div class="col-span-1 h-full bg-yellow-200 rounded-full "></div>
                                <?php elseif ($value['status'] == '1') : ?>
                                    <div class="col-span-1 h-full bg-green-200 rounded-full "></div>
                                <?php elseif ($value['status'] == '2') : ?>
                                    <div class="col-span-1 h-full bg-red-200 rounded-full "></div>
                                <?php endif ?>
                                <div class="p-2 text-sm col-span-11  bg-white rounded-md w-full">

                                    <?php
                                    $teks = $value['keterangan_perjalanan'];
                                    $kata = explode(' ', $teks);
                                    $kataTerbatas = array_slice($kata, 0, 10); // Ubah 10 menjadi jumlah kata yang diinginkan
                                    $teksTerbatas = implode(' ', $kataTerbatas);
                                    ?>
                                    <p><?= $teksTerbatas ?>...</p>


                                    <hr class="w-1/3 my-2">
                                    <div class="flex space-x-3 text-gray-500">
                                        <p><?= date('d-m-Y', strtotime($value['tanggal'])) ?></p>
                                        <span>&nbsp;~&nbsp;&nbsp;</span>
                                        <!-- <p><? // // date('d-m-Y', strtotime($value['tanggal_selesai'])) 
                                                ?></p> -->
                                    </div>
                                    <hr class="my-2">
                                    <!-- <p class="text-[12px] capitalize text-end text-gray-500">diajukan pada :<?php //$value[''] 
                                                                                                                    ?></p> -->

                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="p-2 text-sm   bg-white rounded-md w-full">
                    <p class="text-center">Tidak ada data</p>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800 mb-20" id="settings" role="tabpanel" aria-labelledby="settings-tab">
        <div class="w-full grid grid-cols-1 gap-y-4 ">
            <?php if (!empty($pengajuanDinas)): ?>
                <?php foreach ($pengajuanDinas as $key => $value) : ?>
                    <?php if ($value['status'] == '1') : ?>

                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/dinas-detail/', 'id' => $value['id_pengajuan_dinas']]) ?>">
                            <div class="grid grid-cols-12 gap-5 ">
                                <div class="w-full grid grid-cols-1 gap-y-4 ">
                                    <?php foreach ($pengajuanDinas as $key => $value) : ?>
                                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/dinas-detail/', 'id' => $value['id_pengajuan_dinas']]) ?>">
                                            <div class="grid grid-cols-12 gap-5 ">
                                                <?php if ($value['status'] == '0') : ?>
                                                    <div class="col-span-1 h-full bg-yellow-200 rounded-full "></div>
                                                <?php elseif ($value['status'] == '1') : ?>
                                                    <div class="col-span-1 h-full bg-green-200 rounded-full "></div>
                                                <?php elseif ($value['status'] == '2') : ?>
                                                    <div class="col-span-1 h-full bg-red-200 rounded-full "></div>
                                                <?php endif ?>
                                                <div class="p-2 text-sm col-span-11  bg-white rounded-md w-full">

                                                    <?php
                                                    $teks = $value['keterangan_perjalanan'];
                                                    $kata = explode(' ', $teks);
                                                    $kataTerbatas = array_slice($kata, 0, 10); // Ubah 10 menjadi jumlah kata yang diinginkan
                                                    $teksTerbatas = implode(' ', $kataTerbatas);
                                                    ?>
                                                    <p><?= $teksTerbatas ?>...</p>


                                                    <hr class="w-1/3 my-2">
                                                    <div class="flex space-x-3 text-gray-500">
                                                        <p><?= date('d-m-Y', strtotime($value['tanggal'])) ?></p>
                                                        <span>&nbsp;~&nbsp;&nbsp;</span>
                                                        <!-- <p><? // // date('d-m-Y', strtotime($value['tanggal_selesai'])) 
                                                                ?></p> -->
                                                    </div>
                                                    <hr class="my-2">
                                                    <!-- <p class="text-[12px] capitalize text-end text-gray-500">diajukan pada :<?php //$value[''] 
                                                                                                                                    ?></p> -->

                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
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
            <?php if (!empty($pengajuanDinas)): ?>
                <?php foreach ($pengajuanDinas as $key => $value) : ?>
                    <?php if ($value['status'] == '2') : ?>

                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/dinas-detail/', 'id' => $value['id_pengajuan_dinas']]) ?>">
                            <div class="grid grid-cols-12 gap-5 ">
                                <div class="w-full grid grid-cols-1 gap-y-4 ">
                                    <?php foreach ($pengajuanDinas as $key => $value) : ?>
                                        <a href="<?php echo \yii\helpers\Url::to(['/pengajuan/dinas-detail/', 'id' => $value['id_pengajuan_dinas']]) ?>">
                                            <div class="grid grid-cols-12 gap-5 ">
                                                <?php if ($value['status'] == '0') : ?>
                                                    <div class="col-span-1 h-full bg-yellow-200 rounded-full "></div>
                                                <?php elseif ($value['status'] == '1') : ?>
                                                    <div class="col-span-1 h-full bg-green-200 rounded-full "></div>
                                                <?php elseif ($value['status'] == '2') : ?>
                                                    <div class="col-span-1 h-full bg-red-200 rounded-full "></div>
                                                <?php endif ?>
                                                <div class="p-2 text-sm col-span-11  bg-white rounded-md w-full">

                                                    <?php
                                                    $teks = $value['keterangan_perjalanan'];
                                                    $kata = explode(' ', $teks);
                                                    $kataTerbatas = array_slice($kata, 0, 10); // Ubah 10 menjadi jumlah kata yang diinginkan
                                                    $teksTerbatas = implode(' ', $kataTerbatas);
                                                    ?>
                                                    <p><?= $teksTerbatas ?>...</p>


                                                    <hr class="w-1/3 my-2">
                                                    <div class="flex space-x-3 text-gray-500">
                                                        <p><?= date('d-m-Y', strtotime($value['tanggal'])) ?></p>
                                                        <span>&nbsp;~&nbsp;&nbsp;</span>
                                                        <!-- <p><? // date('d-m-Y', strtotime($value['tanggal_selesai'])) 
                                                                ?></p> -->
                                                    </div>
                                                    <hr class="my-2">
                                                    <!-- <p class="text-[12px] capitalize text-end text-gray-500">diajukan pada :<?php //$value[''] 
                                                                                                                                    ?></p> -->

                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
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
</div>

</div>


<div class="fixed bottom-0 left-0 right-0 z-50">
    <?= $this->render('@backend/views/components/_footer'); ?>
</div>