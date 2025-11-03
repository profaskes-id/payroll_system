<?php

use yii\bootstrap5\ActiveForm;

$this->title = 'Pengajuan Kasbon';

$form = ActiveForm::begin(); ?>

<div class="relative min-h-[85dvh]">

    <!-- Jumlah Kasbon -->
    <div class="mb-5">
        <label class="block mb-2 text-sm font-medium text-gray-900 capitalize">
            Jumlah Kasbon
        </label>
        <?= $form->field($model, 'jumlah_kasbon')->textInput([
            'required' => true,
            'type' => 'number',
            'min' => 0,
            'step' => 1,
            'value' => $model->jumlah_kasbon ?: ($gajiPokok * 3),
            'placeholder' => 'Masukkan jumlah kasbon yang diajukan (contoh: 9000000)',
            'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 number-input'
        ])->label(false) ?>
        <p id="terbilang-jumlah_kasbon" class="mt-1 text-xs text-gray-500"></p>
    </div>

    <!-- Lama Cicilan -->
    <div class="mb-5">
        <label class="block mb-2 text-sm font-medium text-gray-900 capitalize">Lama Cicilan (bulan)</label>
        <?= $form->field($model, 'lama_cicilan')->textInput([
            'required' => true,
            'type' => 'number',
            'min' => 1,
            'step' => 1,
            'placeholder' => 'Masukkan lama cicilan dalam bulan (contoh: 12)',
            'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 number-input'
        ])->label(false) ?>

    </div>

    <!-- Angsuran Per Bulan -->
    <div class="mb-5">
        <label class="block mb-2 text-sm font-medium text-gray-900 capitalize">Angsuran Per Bulan</label>
        <?= $form->field($model, 'angsuran_perbulan')->textInput([
            'required' => true,
            'type' => 'number',
            'min' => 0,
            'step' => 1,
            'placeholder' => 'Masukkan nominal angsuran per bulan (contoh: 1000000)',
            'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 number-input'
        ])->label(false) ?>
        <p id="terbilang-angsuran_perbulan" class="mt-1 text-xs text-gray-500"></p>
    </div>

    <!-- Keterangan -->
    <div class="mb-5">
        <label class="block mb-2 text-sm font-medium text-gray-900 capitalize">Keterangan</label>
        <?= $form->field($model, 'keterangan')->textarea([
            'rows' => 4,
            'placeholder' => 'Tuliskan keterangan atau alasan pengajuan kasbon (opsional)',
            'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'
        ])->label(false) ?>
    </div>

    <!-- Tombol Submit -->
    <div class="absolute bottom-0 left-0 right-0">
        <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Ajukan Kasbon']); ?>
    </div>

</div>

<?php ActiveForm::end(); ?>


<!-- CDN angka-terbilang -->
<script src="https://unpkg.com/@develoka/angka-terbilang-js/index.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.number-input').forEach(function(input) {
            const fieldKey = input.id.split('-').pop(); // contoh: jumlah_kasbon
            const output = document.getElementById('terbilang-' + fieldKey);
            if (!output) return;

            // fungsi update teks
            const updateText = () => {
                const val = input.value.trim();
                if (val && parseInt(val) > 0) {
                    output.textContent = angkaTerbilang(val) + ' rupiah';
                } else {
                    output.textContent = '';
                }
            };

            // jalankan saat halaman pertama kali load
            updateText();

            // jalankan setiap ada input
            input.addEventListener('input', updateText);
        });
    });
</script>