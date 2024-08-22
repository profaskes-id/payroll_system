<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin(); ?>

<div class="">

    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Tanggal</label>
        <?= $form->field($model, 'tanggal')->textInput(['type' => 'time', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>

    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">jam mulai</label>
        <?= $form->field($model, 'jam_mulai')->textInput(['type' => 'time', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>
    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">jam selesai</label>
        <?= $form->field($model, 'jam_selesai')->textInput(['type' => 'time', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>
    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Pekerjaan</label>
        <?= $form->field($model, 'pekerjaan')->textarea(['rows' => '8', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>
    <div class="col-span-12">
        <div class="">
            <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>