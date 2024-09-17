<?php

use backend\models\MasterKode;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */

$this->title = "Histori";
$this->params['breadcrumbs'][] = ['label' => 'Absensi', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$masterKode = MasterKode::find()->where(['nama_group' => Yii::$app->params['status-hadir']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all();
?>
<div class="absensi-view container mx-auto relative min-h-[90dvh] px-5 ">



    <?= $this->render('@backend/views/components/_header', ['title' => 'Rekap Absensi']); ?>



    <section class="w-full mt-5">
        <?php $form = ActiveForm::begin([
            'method' => 'post',
        ]); ?>

        <input type="text" name="id_user" id="id_user" value="<?= Yii::$app->user->identity->id ?>" hidden>
        <div class=" flex justify-between w-full space-x-1 items-center">

            <div class="relative   w-full ">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                    </svg>
                </div>

                <input autocomplete="off" name=" tanggal_searh" datepicker id="default-datepicker" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm w rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
            </div>

            <div class="col-span-2">
                <a href="">
                    <button type="button" class="text-white bg-red-600 hover:bg-rose-800 focus:ring-4 focus:outline-none focus:ring-rose-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 16 16">
                            <path fill="white" fill-rule="evenodd" d="M10.095.28A8 8 0 0 0 1.5 3.335V1.75a.75.75 0 0 0-1.5 0V6h4.25a.75.75 0 1 0 0-1.5H2.523a6.5 6.5 0 1 1-.526 5.994a.75.75 0 0 0-1.385.575A8 8 0 1 0 10.095.279Z" clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Reset</span>
                    </button>
                </a>
            </div>
            <div class="col-span-2">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
                        <path fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.3" d="m5 27l7.5-7.5M28 13a9 9 0 1 1-18 0a9 9 0 0 1 18 0" />
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="mt-10">


            <div id="accordion-collapse" data-accordion="collapse">

                <?php if (!empty($absensi)) : ?>

                    <?php foreach ($absensi as $key => $value) : ?>
                        <ul class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <li class="py-3">
                                <div class="flex items-center space-x-4 justify-between">
                                    <div class="flex items-center space-x-4 justify-between">
                                        <?php if (strtolower($value->statusHadir->nama_kode) == 'hadir') : ?>
                                            <div class="flex-shrink-0 w-[15px] h-[15px] rounded-xl bg-lime-500 grid place-items-center">
                                            </div>
                                        <?php elseif (strtolower($value->statusHadir->nama_kode) == 'sakit') : ?>
                                            <div class="flex-shrink-0 w-[15px] h-[15px] rounded-xl bg-blue-500 grid place-items-center">
                                            </div>
                                        <?php elseif (strtolower($value->statusHadir->nama_kode) == 'izin') : ?>
                                            <div class="flex-shrink-0 w-[15px] h-[15px] rounded-xl bg-yellow-300 grid place-items-center">
                                            </div>
                                        <?php endif ?>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                <?= $value->statusHadir->nama_kode ?>
                                            </p>
                                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                <?= date('d-M-Y', strtotime($value['tanggal'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="inline-flex items-center text-base  text-[#373737] ">

                                        <?= date('H:i', strtotime($value['jam_masuk'] ?? "00:00")) ?>
                                    </div>
                                    <div class="inline-flex items-center text-base  text-[#373737] ">
                                        -
                                    </div>
                                    <div class="inline-flex items-center text-base  text-[#373737] ">
                                        <?php
                                        if (empty($value['jam_pulang'])) {
                                            echo "Not Set";
                                        } else {
                                            echo date('H:i', strtotime($value['jam_pulang']));
                                        }
                                        ?>


                                    </div>

                                    <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white" id="accordion-collapse-heading-<?= $key ?>">
                                        <button data-accordion-target="#accordion-collapse-body-<?= $key ?>" aria-expanded="false" aria-controls="accordion-collapse-body-<?= $key ?>">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="black" d="M12 5.83L15.17 9l1.41-1.41L12 3L7.41 7.59L8.83 9zm0 12.34L8.83 15l-1.41 1.41L12 21l4.59-4.59L15.17 15z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div id="accordion-collapse-body-<?= $key ?>" class="hidden" aria-labelledby="accordion-collapse-heading-<?= $key ?>">
                                    <p class="text-sm font-normal text-gray-500 mt-2 px-8">
                                        <?= $value->keterangan  ?? 'Tidak Ada Keterangan' ?>
                                    </p>
                                </div>
                            </li>
                        </ul>
                    <?php endforeach ?>
                <?php else : ?>
                    <p class="text-center">Tidak ada data</p>
                <?php endif ?>
            </div>

        </div>
    </section>




</div>
<div class="">

    <?= $this->render('@backend/views/components/_footer'); ?>
</div>