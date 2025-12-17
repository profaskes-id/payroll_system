<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\MasterKode;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<script src="https://unpkg.com/@develoka/angka-terbilang-js/index.min.js"></script>

<div class="px-6 bg-white rounded-lg shadow-sm pengajuan-dinas-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'space-y-6']
    ]); ?>

    <!-- Nama Karyawan -->
    <div class="grid grid-cols-1 col-span-2 gap-6 ">
        <p class="block mb-2 text-sm font-medium text-gray-900 capitalize ">Karyawan</p>
        <?php
        $data = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
        echo $form->field($model, 'id_karyawan')->dropDownList(
            $data,
            [
                'disabled' => true,
                'prompt' => 'Pilih Karyawan ...',
                'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '
            ]
        )->label(false);
        ?>
    </div>

    <!-- Detail Dinas Section -->
    <div class="pt-6 border-t border-gray-200">
        <h5 class="mb-4 text-lg font-semibold text-gray-900">Detail Dinas</h5>

        <!-- Detail Container -->
        <div id="detail-container" class="space-y-4">
            <?php if (!empty($detailModels)): ?>
                <?php foreach ($detailModels as $index => $detailModel): ?>
                    <div class="p-4 bg-white border border-gray-200 rounded-lg detail-item" data-index="<?= $index ?>">
                        <div class="flex items-end gap-4">
                            <!-- Tanggal -->
                            <div class="flex-1">
                                <?= $form->field($detailModel, "[$index]tanggal")->input('date', [
                                    'class' => 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500'
                                ])->label('Tanggal', ['class' => 'block text-sm font-medium text-gray-700 mb-1']) ?>
                            </div>

                            <!-- Tombol Delete -->
                            <div class="flex-none">
                                <button type="button"
                                    class="inline-flex items-center justify-center w-10 h-10 text-red-600 transition-colors duration-200 rounded-lg bg-red-50 hover:bg-red-100 remove-detail-btn"
                                    data-index="<?= $index ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Container untuk detail yang akan di-generate dari multiple dates -->
                <div id="auto-detail-container" class="space-y-4">
                    <!-- Detail akan di-generasi otomatis oleh JavaScript -->
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Button Tambah Detail Manual -->
    <div class="flex justify-start">
        <button type="button" id="add-detail" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Detail Dinas Manual
        </button>
    </div>

    <!-- Biaya Section -->
    <div class="grid grid-cols-1 gap-6 mt-8 md:grid-cols-2">
        <div>
            <?= $form->field($model, 'biaya_yang_disetujui')->textInput([
                'id' => 'biaya-yang-disetujui',
                'maxlength' => true,
                "type" => "number",
                "step" => "0.01",
                "min" => "0",
                "placeholder" => "0.00",
                'value' => $model->estimasi_biaya,
                'class' => 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500'
            ])->label('Biaya Yang Disetujui', ['class' => 'block text-sm font-medium text-gray-700 mb-1']) ?>

            <!-- Terbilang untuk Biaya Yang Disetujui -->
            <p id="terbilang-biaya-disetujui" class="mt-1 text-sm text-gray-500 italic min-h-[20px]"></p>
        </div>
        <div>
            <?= $form->field($model, 'estimasi_biaya')->textInput([
                'id' => 'estimasi-biaya',
                'maxlength' => true,
                "type" => "number",
                "step" => "0.01",
                "min" => "0",
                "placeholder" => "0.00",
                'class' => 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500'
            ])->label('Estimasi Biaya', ['class' => 'block text-sm font-medium text-gray-700 mb-1']) ?>

            <!-- Terbilang untuk Estimasi Biaya -->
            <p id="terbilang-estimasi-biaya" class="mt-1 text-sm text-gray-500 italic min-h-[20px]"></p>
        </div>
    </div>

    <!-- Keterangan Perjalanan -->
    <div>
        <?= $form->field($model, 'keterangan_perjalanan')->textarea([
            'rows' => 3,
            'class' => 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500'
        ])->label('Keterangan Perjalanan', ['class' => 'block text-sm font-medium text-gray-700 mb-1']) ?>
    </div>



    <div class="col-12">

        <?php
        $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');


        echo $form->field($model, 'status')->radioList($data, [
            'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                // Tentukan apakah radio button untuk value 1 harus checked

                $isChecked = $value == 1 ? true : $checked;

                return Html::radio($name, $isChecked, [
                    'value' => $value,
                    'label' => $label,
                    'labelOptions' => ['class' => 'radio-label mr-4'],
                ]);
            },
        ])->label('Status Pengajuan');
        ?>


        <!-- Catatan Admin Field (hanya jika bukan record baru) -->
        <?php if (!$model->isNewRecord): ?>
            <div class="mt-4">
                <?= $form->field($model, 'catatan_admin')->textarea([
                    'rows' => 2,
                    'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
                ]) ?>
            </div>
        <?php endif; ?>

        <!-- STATUS ABSENSI -->
        <div class="pt-6 border-t border-gray-200">
            <label class="block mb-2 text-sm font-medium text-gray-700">
                Apakah absensi akan diisikan?
            </label>

            <div class="flex gap-6">
                <label class="inline-flex items-center gap-2">
                    <input type="radio"
                        name="PengajuanDinas[isNewAbsen]"
                        value="1"
                        <?= $model->isNewRecord || $model->isNewAbsen == 1 ? 'checked' : '' ?>
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Iya</span>
                </label>

                <label class="inline-flex items-center gap-2">
                    <input type="radio"
                        name="PengajuanDinas[isNewAbsen]"
                        value="0"
                        <?= !$model->isNewRecord && $model->isNewAbsen == 0 ? 'checked' : '' ?>
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Tidak</span>
                </label>
            </div>

            <p class="mt-1 text-xs italic text-gray-500">
                Jika <b>Iya</b>, sistem akan otomatis membuat absensi berdasarkan tanggal dinas
            </p>
        </div>



        <!-- Submit Button -->
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end pt-6 border-t border-gray-200">
        <button type="submit" class="inline-flex items-center px-6 py-3 text-base font-medium text-white transition-colors duration-200 bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <span>Submit</span>
        </button>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<!-- Include Flatpickr CSS dan JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Tambahkan FontAwesome jika diperlukan -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ==============================
        // TOGGLE ABSENSI
        // ==============================
        function isAbsenAktif() {
            let checked = document.querySelector('input[name="PengajuanDinas[isNewAbsen]"]:checked');
            return checked && checked.value === '1';
        }

        // ==============================
        // MONITOR PERUBAHAN STATUS ABSEN
        // ==============================
        document.querySelectorAll('input[name="PengajuanDinas[isNewAbsen]"]').forEach(radio => {
            radio.addEventListener('change', function() {

                if (!isAbsenAktif()) {
                    console.log('Absensi dimatikan');
                } else {
                    console.log('Absensi diaktifkan');
                }
            });
        });

        // ==============================
        // OVERRIDE GENERATE DETAIL
        // ==============================
        let originalGenerate = window.generateDetailFromDates;

        window.generateDetailFromDates = function(datesArray) {

            if (!isAbsenAktif()) {
                alert('Absensi tidak diaktifkan. Aktifkan absensi untuk generate otomatis.');
                return;
            }

            if (typeof originalGenerate === 'function') {
                originalGenerate(datesArray);
            }
        };

    });
</script>

<script>
    // ==============================
    // INISIALISASI JAVASCRIPT
    // ==============================
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM fully loaded');

        // ==============================
        // TAMBAH DETAIL MANUAL
        // ==============================
        document.getElementById('add-detail').addEventListener('click', function() {
            console.log('Tombol tambah diklik');

            let detailItems = document.querySelectorAll('.detail-item');
            let index = detailItems.length;

            // Tentukan target container
            let autoContainer = document.getElementById('auto-detail-container');
            let targetContainer = autoContainer ? autoContainer : document.getElementById('detail-container');

            let html = `
        <div class="p-4 bg-white border border-gray-200 rounded-lg detail-item" data-index="${index}">
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date"
                        name="DetailDinas[${index}][tanggal]"
                        id="detaildinas-${index}-tanggal"
                        class="block w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                </div>

                <div class="flex-none">
                    <button type="button"
                        class="inline-flex items-center justify-center w-10 h-10 text-red-600 transition-colors duration-200 rounded-lg bg-red-50 hover:bg-red-100 remove-detail-btn"
                        data-index="${index}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7
                                m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>`;

            targetContainer.insertAdjacentHTML('beforeend', html);
            reindexDetail();
        });

        // ==============================
        // HAPUS DETAIL (DELEGASI EVENT)
        // ==============================
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-detail-btn')) {
                e.preventDefault();
                let btn = e.target.closest('.remove-detail-btn');

                if (confirm('Yakin ingin menghapus tanggal ini?')) {
                    let detailItem = btn.closest('.detail-item');
                    if (detailItem) {
                        detailItem.remove();
                        reindexDetail();
                    }
                }
            }
        });

        // ==============================
        // KONVERSI ANGKA KE TERBILANG
        // ==============================
        function konversiKeTerbilang(angka) {
            try {
                if (typeof angkaTerbilang === 'function') {
                    return angkaTerbilang(parseFloat(angka));
                } else {
                    console.warn('Library angkaTerbilang tidak tersedia');
                    return '';
                }
            } catch (e) {
                console.error('Error converting number to text:', e);
                return '';
            }
        }

        // ==============================
        // TERBILANG UNTUK ESTIMASI BIAYA
        // ==============================
        let estimasiBiayaInput = document.getElementById('estimasi-biaya');
        if (estimasiBiayaInput) {
            estimasiBiayaInput.addEventListener('input', function() {
                let val = this.value;
                let terbilangElement = document.getElementById('terbilang-estimasi-biaya');

                if (!val || val == 0) {
                    if (terbilangElement) {
                        terbilangElement.textContent = 'Nol Rupiah';
                    }
                    return;
                }

                if (terbilangElement) {
                    let terbilang = konversiKeTerbilang(val);
                    if (terbilang) {
                        terbilangElement.textContent = terbilang + ' Rupiah';
                    }
                }
            });

            // Trigger input event untuk menampilkan terbilang awal jika ada nilai
            if (estimasiBiayaInput.value) {
                estimasiBiayaInput.dispatchEvent(new Event('input'));
            }
        }


        // ==============================
        // TERBILANG UNTUK BIAYA YANG DISETUJUI
        // ==============================
        let biayaDisetujuiInput = document.getElementById('biaya-yang-disetujui');
        if (biayaDisetujuiInput) {
            biayaDisetujuiInput.addEventListener('input', function() {
                let val = this.value;
                let terbilangElement = document.getElementById('terbilang-biaya-disetujui');

                if (!val || val == 0) {
                    if (terbilangElement) {
                        terbilangElement.textContent = 'Nol Rupiah';
                    }
                    return;
                }

                if (terbilangElement) {
                    let terbilang = konversiKeTerbilang(val);
                    if (terbilang) {
                        terbilangElement.textContent = terbilang + ' Rupiah';
                    }
                }
            });

            // Trigger input event untuk menampilkan terbilang awal jika ada nilai
            if (biayaDisetujuiInput.value) {
                biayaDisetujuiInput.dispatchEvent(new Event('input'));
            }
        }


        // ==============================
        // COPY ESTIMASI BIAYA KE BIAYA DISETUJUI
        // ==============================
        if (estimasiBiayaInput && biayaDisetujuiInput) {
            estimasiBiayaInput.addEventListener('blur', function() {
                let estimasiBiaya = this.value;

                if (estimasiBiaya && parseFloat(estimasiBiaya) > 0) {
                    biayaDisetujuiInput.value = estimasiBiaya;
                    // Trigger event untuk update terbilang
                    biayaDisetujuiInput.dispatchEvent(new Event('input'));
                }
            });
        }

        // ==============================
        // INIT FLATPICKR JIKA ADA
        // ==============================
        if (document.getElementById('tanggal-dinas-multiple')) {
            flatpickr("#tanggal-dinas-multiple", {
                mode: "multiple",
                dateFormat: "Y-m-d",
                maxDate: new Date().fp_incr(365),
                onChange: function(selectedDates, dateStr) {
                    let datesArray = dateStr ? dateStr.split(', ') : [];
                    generateDetailFromDates(datesArray);
                }
            });

            let initialDates = document.getElementById('tanggal-dinas-multiple').value;
            if (initialDates) {
                let datesArray = initialDates.split(', ');
                generateDetailFromDates(datesArray);
            }
        }

        console.log('Detail Dinas JS Initialized');
    });

    // ==============================
    // FUNGSI REINDEX DETAIL
    // ==============================
    function reindexDetail() {
        console.log('Reindexing detail items...');

        let allDetailItems = document.querySelectorAll('#detail-container .detail-item, #auto-detail-container .detail-item');

        allDetailItems.forEach(function(item, i) {
            item.setAttribute('data-index', i);

            // Update input fields
            let inputs = item.querySelectorAll('input');
            inputs.forEach(function(input) {
                let name = input.getAttribute('name');
                let id = input.getAttribute('id');

                if (name) {
                    input.setAttribute('name', name.replace(/DetailDinas\[\d+\]/, 'DetailDinas[' + i + ']'));
                }
                if (id) {
                    input.setAttribute('id', id.replace(/detaildinas-\d+-/, 'detaildinas-' + i + '-'));
                }
            });

            // Update tombol delete
            let deleteBtn = item.querySelector('.remove-detail-btn');
            if (deleteBtn) {
                deleteBtn.setAttribute('data-index', i);
            }
        });

        // Update jumlah hari
        updateJumlahHari();
    }

    // ==============================
    // FUNGSI UPDATE JUMLAH HARI
    // ==============================
    function updateJumlahHari() {
        let totalDays = document.querySelectorAll('#detail-container .detail-item, #auto-detail-container .detail-item').length;
        let jumlahHariElement = document.getElementById('jumlah-hari-dinas');

        if (jumlahHariElement) {
            jumlahHariElement.textContent = totalDays + ' Hari';
        }
    }

    // ==============================
    // GENERATE DETAIL DARI MULTIPLE DATE
    // ==============================
    function generateDetailFromDates(datesArray) {
        console.log('Generating details from dates:', datesArray);

        let container = document.getElementById('auto-detail-container');
        if (!container) {
            console.error('Container auto-detail-container tidak ditemukan');
            return;
        }

        container.innerHTML = '';

        datesArray.forEach(function(date, index) {
            let html = `
        <div class="p-4 bg-white border border-gray-200 rounded-lg detail-item" data-index="${index}">
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date"
                        name="DetailDinas[${index}][tanggal]"
                        id="detaildinas-${index}-tanggal"
                        value="${date}"
                        readonly
                        class="block w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                </div>

                <div class="flex-none">
                    <button type="button"
                        class="inline-flex items-center justify-center w-10 h-10 text-red-600 transition-colors duration-200 rounded-lg bg-red-50 hover:bg-red-100 remove-detail-btn"
                        data-index="${index}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7
                                m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>`;
            container.insertAdjacentHTML('beforeend', html);
        });

        reindexDetail();
    }
</script>