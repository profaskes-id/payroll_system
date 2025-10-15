<?php

use backend\models\helpers\KaryawanHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\MasterKode;

/** @var yii\web\View $this */
/** @var backend\models\IzinPulangCepat $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="relative p-4 bg-white rounded-lg shadow-md">
    <?php $form = ActiveForm::begin(); ?>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Pilih Karyawan -->
        <div>
            <?= $form->field($model, 'id_karyawan')->dropDownList(
                ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama'),
                [
                    'prompt' => 'Pilih Karyawan...',
                    'disabled' => !$model->isNewRecord,
                    'class' => 'form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200'
                ]
            )->label('Karyawan') ?>
        </div>

        <!-- Tanggal -->
        <div>
            <?= $form->field($model, 'tanggal')->input('date', [
                'class' => 'form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200'
            ]) ?>
        </div>

        <!-- Alasan -->
        <div class="md:col-span-2">
            <?= $form->field($model, 'alasan')->textarea([
                'rows' => 3,
                'class' => 'form-textarea block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200'
            ]) ?>
        </div>

        <!-- Status + Catatan Admin (Hanya saat Update) -->
        <?php if (!$model->isNewRecord): ?>
            <div class="md:col-span-2">
                <?php
                $statusOptions = [
                    0 => 'Pending',
                    1 => 'Disetujui',
                    2 => 'Ditolak',
                ];
                echo $form->field($model, 'status')->radioList($statusOptions, [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        return Html::radio($name, $checked, [
                            'value' => $value,
                            'label' => $label,
                            'labelOptions' => ['class' => 'mr-4'],
                        ]);
                    }
                ]);
                ?>
            </div>

            <div class="md:col-span-2">
                <?= $form->field($model, 'catatan_admin')->textarea([
                    'rows' => 2,
                    'class' => 'form-textarea block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200'
                ]) ?>
            </div>
        <?php endif; ?>

        <!-- Tombol Submit -->
        <div class="md:col-span-2">
            <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                <?= $model->isNewRecord ? 'Ajukan' : 'Simpan' ?>
            </button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>