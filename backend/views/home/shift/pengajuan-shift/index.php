<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<div class="relative z-50 max-w-md p-6 mx-auto mt-10 bg-white rounded-lg shadow-md">
    <h2 class="mb-4 text-xl font-semibold text-indigo-600">Ajukan Perubahan Shift Kerja</h2>


    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="mb-4 text-green-600">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="mb-4 text-red-600">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>




    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => Url::to(['pengajuan-shift']),
        'options' => ['class' => 'space-y-4'],
    ]); ?>


    <?php
    $pendidikan = \yii\helpers\ArrayHelper::map($allDataShift, 'id_shift_kerja', 'nama_shift');

    ?>

    <div class="row">

        <div class="col-span-12">

            <?= $form->field($model, 'id_shift_kerja')->dropDownList($pendidikan, ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', 'prompt' => 'Select shift kerja'])->label('Shift Kerja') ?>
        </div>



        <?= Html::submitButton('Simpan Perubahan', [
            'class' => 'w-full px-4 py-2 mt-2 text-white bg-indigo-600 rounded hover:bg-indigo-700'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>