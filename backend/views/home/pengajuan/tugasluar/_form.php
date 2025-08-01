<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin(); ?>

<div class="relative min-h-[85dvh]">
    <div class="mb-4">
        <h2 class="text-lg font-bold text-gray-800">Pengajuan Tugas Luar</h2>
    </div>

    <!-- Form utama (pengajuan) -->
    <?= $form->field($model, 'id_karyawan')->hiddenInput()->label(false) ?>

    <!-- Detail tugas luar -->
    <div class="mb-4">
        <label class="block mb-2 text-sm font-medium text-gray-900 capitalize">Lokasi Tugas</label>
        <div id="lokasi-container">
            <?php foreach ($details as $i => $detail): ?>
                <div class="p-4 mb-4 border border-gray-200 rounded-lg lokasi-item">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Lokasi #<?= $i + 1 ?></span>
                        <?php if ($i > 0): ?>
                            <button type="button" class="text-red-500 remove-lokasi hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z" />
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>

                    <?= $form->field($detail, "[$i]keterangan", [
                        'options' => ['class' => 'mb-3'],
                        'inputOptions' => ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5']
                    ])->textInput()->label('Keterangan (Contoh: Ke Jakarta untuk meeting)') ?>

                    <?= $form->field($detail, "[$i]jam_diajukan", [
                        'options' => ['class' => 'mb-3'],
                        'inputOptions' => ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5']
                    ])->input('time')->label('Jam Diajukan') ?>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" id="add-lokasi" class="flex items-center mt-2 text-sm font-medium text-blue-600 hover:text-blue-800">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" class="mr-1">
                <path fill="currentColor" d="M11 13H5v-2h6V5h2v6h6v2h-6v6h-2z" />
            </svg>
            Tambah Lokasi
        </button>
    </div>

    <div class="h-[80px] w-full"></div>
    <div class="absolute bottom-0 left-0 right-0">
        <div class="p-4 bg-white border-t border-gray-200">
            <?= Html::submitButton('Submit Pengajuan', [
                'class' => 'w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg'
            ]) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const container = document.getElementById('lokasi-container');
        const addButton = document.getElementById('add-lokasi');
        let lokasiCount = <?= count($details) ?>;

        // Template untuk lokasi baru
        const lokasiTemplate = `
        <div class="p-4 mb-4 border border-gray-200 rounded-lg lokasi-item">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Lokasi #${lokasiCount + 1}</span>
                <button type="button" class="text-red-500 remove-lokasi hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z"/>
                    </svg>
                </button>
            </div>
            
            <div class="mb-3">
                <label class="block mb-1 text-sm font-medium text-gray-900">Keterangan (Contoh: Ke Jakarta untuk meeting)</label>
                <input type="text" name="DetailTugasLuar[${lokasiCount}][keterangan]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
            </div>
            
            <div class="mb-3">
                <label class="block mb-1 text-sm font-medium text-gray-900">Jam Diajukan</label>
                <input type="time" name="DetailTugasLuar[${lokasiCount}][jam_diajukan]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
            </div>
        </div>
    `;

        // Tambah lokasi baru
        addButton.addEventListener('click', function() {
            const newLokasi = document.createElement('div');
            newLokasi.innerHTML = lokasiTemplate.replace(/\[\d+\]/g, `[${lokasiCount}]`);
            container.appendChild(newLokasi);
            lokasiCount++;

            // Tambahkan event listener untuk tombol hapus
            newLokasi.querySelector('.remove-lokasi').addEventListener('click', function() {
                newLokasi.remove();
                updateLokasiNumbers();
            });
        });

        // Hapus lokasi
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-lokasi') || e.target.closest('.remove-lokasi')) {
                const lokasiItem = e.target.closest('.lokasi-item');
                if (lokasiItem && document.querySelectorAll('.lokasi-item').length > 1) {
                    lokasiItem.remove();
                    updateLokasiNumbers();
                }
            }
        });

        // Update nomor urutan lokasi
        function updateLokasiNumbers() {
            document.querySelectorAll('.lokasi-item').forEach((item, index) => {
                item.querySelector('span').textContent = `Lokasi #${index + 1}`;
                // Update juga nama input fields
                const inputs = item.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                });
            });
            lokasiCount = document.querySelectorAll('.lokasi-item').length;
        }
    });
</script>