<?php

use backend\models\MasterKode;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'id' => 'absensi-form',
    'enableAjaxValidation' => true,
]); ?>

<div class="relative min-h-[85dvh]">
    <div class="mb-5">
        <label for="tanggal_absen" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Tanggal Absen</label>
        <?= $form->field($model, 'tanggal_absen')->textInput([
            'type' => 'date',
            'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'
        ])->label(false) ?>
    </div>

    <div class="mb-5">
        <label for="jam_masuk" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Jam Masuk</label>
        <?= $form->field($model, 'jam_masuk')->textInput([
            'type' => 'time',
            'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'
        ])->label(false) ?>
    </div>

    <div class="mb-5">
        <label for="jam_keluar" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Jam Keluar</label>
        <?= $form->field($model, 'jam_keluar')->textInput([
            'type' => 'time',
            'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'
        ])->label(false) ?>
    </div>

    <div class="mb-5">
        <label for="alasan_pengajuan" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Alasan Pengajuan</label>
        <?= $form->field($model, 'alasan_pengajuan')->textarea([
            'rows' => 3,
            'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'
        ])->label(false) ?>
    </div>

    <div class="overflow-x-auto col-md-6 col-12">
        <div class="form-group">
            <?= Html::activeLabel($model, 'kode_status_hadir', [
                'class' => 'block mb-2 font-medium text-gray-700'
            ]) ?>



            <?php
            // Data manual
            $data = [
                'H'   => "Hadir  <small> (Maximal mengajukan {$batas_hari} hari sebelum hari ini )</small>",
                'DL'  => 'Dinas Luar  <small> (Telah Mengajukan Dinas)</small>',
                'WFH' => 'WFH  <small> (Telah Mengajukan WFH)</small>',
            ];
            ?>

            <div class="flex items-center py-1 space-x-8">
                <?= $form->field($model, 'kode_status_hadir')->radioList(
                    $data,
                    [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return '
                        <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:text-gray-900">
                            <input 
                                type="radio"
                                name="' . $name . '"
                                value="' . $value . '"
                                ' . ($checked ? 'checked' : '') . '
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                            >
                            <span class="select-none">' . $label . '</span>
                        </label>';
                        }
                    ]
                )->label(false) ?>
            </div>
        </div>
    </div>


    <div class="h-[80px] w-full"></div>
    <div class="absolute bottom-0 left-0 right-0">
        <div class="">
            <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>