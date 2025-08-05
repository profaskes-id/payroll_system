<?php

use backend\models\MasterKode;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */

$this->title = 'Izin Tidak Hadir ';
$this->params['breadcrumbs'][] = ['label' => 'Absensi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="w-full px-5 mx-auto lg:px-8 ">



    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/home/absen-masuk', 'title' => 'Izin Tidak Absen']); ?>
    <section class="grid justify-center grid-cols-12 mt-2 gap-y-10">



        <div class="col-span-12">

            <?php $form = ActiveForm::begin([
                'id' => 'form-absensi',
            ]); ?>

            <?php if ($model->kode_status_hadir == 'H'): ?>
                <p class="bg-green-100 text-green-800  font-medium me-2 px-2.5 py-0.5 rounded "><?= $model->statusHadir->nama_kode ?></p>
            <?php elseif ($model->kode_status_hadir == 'I'): ?>
                <p class="bg-green-100 text-yellow-800  font-medium me-2 px-2.5 py-0.5 rounded "><?= $model->statusHadir->nama_kode ?></p>
            <?php else: ?>
                <p class="bg-green-100 text-rose-800  font-medium me-2 px-2.5 py-0.5 rounded "><?= $model->statusHadir->nama_kode ?></p>
            <?php endif; ?>

            <p class="my-2"><?= $model->keterangan ?></p>




            <?php if (!$model->kode_status_hadir == 'H'): ?>

                <div class="w-full col-span-12 p-2 overflow-hidden rounded-md">
                    <?= Html::img('@root' . '/panel/' . $model->lampiran, ["alt" => 'users', 'class' => 'w-[100%] ']) ?>
                </div>
            <?php endif; ?>

            <?php ActiveForm::end(); ?>

        </div>
    </section>



</div>