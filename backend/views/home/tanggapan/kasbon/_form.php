<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanKasbon $model */
/** @var yii\widgets\ActiveForm $form */

$defaultTanggalMulaiPotong = !empty($model->tanggal_mulai_potong)
    ? date('Y-m-d', strtotime('+1 month', strtotime($model->tanggal_mulai_potong)))
    : null;
?>

<div class="relative p-4 bg-white rounded-lg shadow-md md:p-6">
    <?php $form = ActiveForm::begin(); ?>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <!-- Pilih Karyawan -->

        <!-- Jumlah Kasbon -->
        <div>
            <?= $form->field($model, 'jumlah_kasbon')->textInput([
                'type' => 'number',
                'id' => 'jumlah-kasbon',
                'placeholder' => 'Masukkan jumlah kasbon (contoh: 1000000)',
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500'
            ])->label('Jumlah Kasbon (Rp)') ?>
            <p id="terbilang-jumlah-kasbon" class="mt-1 text-sm text-gray-500"></p>
        </div>

        <!-- Lama Cicilan -->
        <div>
            <?= $form->field($model, 'lama_cicilan')->textInput([
                'type' => 'number',
                'placeholder' => 'Masukkan lama cicilan (bulan)',
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500'
            ])->label('Lama Cicilan (Bulan)') ?>
        </div>

        <!-- Angsuran Perbulan -->
        <div>
            <?= $form->field($model, 'angsuran_perbulan')->textInput([
                'type' => 'number',
                'id' => 'angsuran-perbulan',
                'placeholder' => 'Masukkan angsuran per bulan (contoh: 500000)',
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500'
            ])->label('Angsuran Perbulan (Rp)') ?>
            <p id="terbilang-angsuran-perbulan" class="mt-1 text-sm text-gray-500"></p>
        </div>

        <!-- Tanggal Pengajuan -->
        <div>
            <?= $form->field($model, 'tanggal_pengajuan')->input('date', [
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500'
            ]) ?>
        </div>

        <!-- Tanggal Pencairan -->
        <?php if (!$model->isNewRecord): ?>
            <div>
                <?= $form->field($model, 'tanggal_pencairan')->input('date', [
                    'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500'
                ]) ?>
            </div>

            <!-- Tanggal Mulai Potong -->
            <div>
                <?= $form->field($model, 'tanggal_mulai_potong')->input('date', [
                    'value' => $model->tanggal_mulai_potong ?: $defaultTanggalMulaiPotong,
                    'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500'
                ]) ?>
            </div>
        <?php endif; ?>

        <!-- Keterangan -->
        <div class="md:col-span-2">
            <?= $form->field($model, 'keterangan')->textarea([
                'rows' => 3,
                'placeholder' => 'Tulis keterangan tambahan jika ada...',
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500'
            ]) ?>
        </div>

        <!-- Status dan Tipe Potongan -->
        <?php if (!$model->isNewRecord): ?>
            <div class="md:col-span-2">
                <h3 class="mb-2 font-semibold text-gray-700 text-md">Status Pengajuan</h3>
                <?php
                $data = \yii\helpers\ArrayHelper::map(
                    MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])
                        ->andWhere(['!=', 'status', 0])
                        ->orderBy(['urutan' => SORT_ASC])
                        ->all(),
                    'kode',
                    'nama_kode'
                );
                echo $form->field($model, 'status')->radioList($data, [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        return '<label class="inline-flex items-center mr-4">' .
                            Html::radio($name, $checked, ['value' => $value, 'class' => 'mr-2']) .
                            '<span>' . Html::encode($label) . '</span></label>';
                    },
                ])->label(false);
                ?>
            </div>

            <!-- Tipe Potongan -->
            <div class="md:col-span-2">
                <?= $form->field($model, 'tipe_potongan')->radioList([
                    0 => 'Off (manual input saat penggajian)',
                    1 => 'On (otomatis dipotong setiap bulan)',
                ], [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        return '<label class="block mb-2">' .
                            Html::radio($name, $checked, ['value' => $value, 'class' => 'mr-2']) .
                            '<span>' . Html::encode($label) . '</span></label>';
                    },
                ])->label('Tipe Potongan') ?>
            </div>
        <?php endif; ?>

        <!-- Tombol Submit -->
        <div class="md:col-span-2">
            <button type="submit" class="px-8 py-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                Simpan
            </button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<!-- Angka Terbilang -->
<script src="https://unpkg.com/@develoka/angka-terbilang-js/index.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fields = [{
                id: 'jumlah-kasbon',
                output: 'terbilang-jumlah-kasbon'
            },
            {
                id: 'angsuran-perbulan',
                output: 'terbilang-angsuran-perbulan'
            },
        ];

        function updateTerbilang(input, display) {
            const val = input.value.trim();
            if (!val || isNaN(val)) return display.textContent = '';
            const angka = parseInt(val, 10);
            const teks = angkaTerbilang(angka) + ' rupiah';
            display.textContent = teks.charAt(0).toUpperCase() + teks.slice(1);
        }

        fields.forEach(({
            id,
            output
        }) => {
            const input = document.getElementById(id);
            const display = document.getElementById(output);
            if (input && display) {
                input.addEventListener('input', () => updateTerbilang(input, display));
                if (input.value) updateTerbilang(input, display);
            }
        });
    });
</script>