<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'Pengajuan Dinas Luar';

$form = ActiveForm::begin(); ?>

<div class="relative min-h-[85dvh]">


    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">tanggal mulai</label>
        <?= $form->field($model, 'tanggal_mulai')->textInput(['required' => true, 'type' => 'date', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>
    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">tanggal selesai</label>
        <?= $form->field($model, 'tanggal_selesai')->textInput(['required' => true, 'type' => 'date', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>

    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Estimasi Biaya</label>
        <?= $form->field($model, 'estimasi_biaya')->textInput(['required' => true, 'type' => 'number', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>
    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Keterangan Perjalanan</label>
        <?= $form->field($model, 'keterangan_perjalanan')->textarea(['required' => true, 'rows' => '10',  'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>
    <div class="absolute bottom-0 left-0 right-0 ">
        <div class="">
            <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
        </div>
    </div>


</div>

<?php ActiveForm::end(); ?>