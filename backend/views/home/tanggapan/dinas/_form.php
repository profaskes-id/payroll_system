<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="p-6 bg-white rounded-lg shadow-sm pengajuan-dinas-form">

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

                            <!-- Keterangan -->
                            <div class="flex-1">
                                <?= $form->field($detailModel, "[$index]keterangan")->textInput([
                                    'class' => 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500',
                                    'placeholder' => 'Keterangan (optional)'
                                ])->label('Keterangan', ['class' => 'block text-sm font-medium text-gray-700 mb-1']) ?>
                            </div>

                            <!-- Status -->
                            <div class="flex-1">
                                <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                                <div class="flex space-x-4">
                                    <div class="flex items-center">
                                        <?= Html::radio("DetailDinas[$index][status]", $detailModel->status == 0, [
                                            'value' => 0,
                                            'class' => 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300',
                                            'id' => "status_pending_$index"
                                        ]) ?>
                                        <label for="status_pending_<?= $index ?>" class="ml-2 text-sm text-gray-700">Pending</label>
                                    </div>
                                    <div class="flex items-center">
                                        <?= Html::radio("DetailDinas[$index][status]", $detailModel->status == 1, [
                                            'value' => 1,
                                            'class' => 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300',
                                            'id' => "status_approved_$index"
                                        ]) ?>
                                        <label for="status_approved_<?= $index ?>" class="ml-2 text-sm text-gray-700">Disetujui</label>
                                    </div>
                                    <div class="flex items-center">
                                        <?= Html::radio("DetailDinas[$index][status]", $detailModel->status == 2, [
                                            'value' => 2,
                                            'class' => 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300',
                                            'id' => "status_rejected_$index"
                                        ]) ?>
                                        <label for="status_rejected_<?= $index ?>" class="ml-2 text-sm text-gray-700">Ditolak</label>
                                    </div>
                                </div>
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
                'maxlength' => true,
                "type" => "number",
                "step" => "0.01",
                "min" => "0",
                "placeholder" => "0.00",
                'value' => $model->estimasi_biaya,
                'class' => 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500'
            ])->label('Biaya Yang Disetujui', ['class' => 'block text-sm font-medium text-gray-700 mb-1']) ?>
        </div>
        <div>
            <?= $form->field($model, 'estimasi_biaya')->textInput([
                'maxlength' => true,
                "type" => "number",
                "step" => "0.01",
                "min" => "0",
                "placeholder" => "0.00",
                'class' => 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500'
            ])->label('Estimasi Biaya', ['class' => 'block text-sm font-medium text-gray-700 mb-1']) ?>
        </div>
    </div>

    <!-- Keterangan Perjalanan -->
    <div>
        <?= $form->field($model, 'keterangan_perjalanan')->textarea([
            'rows' => 3,
            'class' => 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500'
        ])->label('Keterangan Perjalanan', ['class' => 'block text-sm font-medium text-gray-700 mb-1']) ?>
    </div>

    <!-- Catatan Admin (hanya untuk edit) -->
    <?php if (!$model->isNewRecord) : ?>
        <div>
            <?= $form->field($model, 'catatan_admin')->textarea([
                'rows' => 3,
                'class' => 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500'
            ])->label('Catatan Admin', ['class' => 'block text-sm font-medium text-gray-700 mb-1']) ?>
        </div>
    <?php endif; ?>

    <!-- Status Pengajuan -->
    <div class="p-4 rounded-lg bg-gray-50">
        <label class="block mb-3 text-sm font-medium text-gray-700">Status Pengajuan</label>
        <?php
        $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');

        echo $form->field($model, 'status')->radioList($data, [
            'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                if ($model->isNewRecord) {
                    $isChecked = $value == 1 ? true : $checked;
                } else {
                    $isChecked = $checked;
                }

                return Html::radio($name, $isChecked, [
                    'value' => $value,
                    'class' => 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300',
                    'label' => '<span class="ml-2 text-sm text-gray-700">' . $label . '</span>',
                    'labelOptions' => ['class' => 'flex items-center'],
                ]);
            },
            'class' => 'space-y-2'
        ])->label(false);
        ?>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end pt-6 border-t border-gray-200">
        <button type="submit" class="inline-flex items-center px-6 py-3 text-base font-medium text-white transition-colors duration-200 bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <span>Submit Pengajuan</span>
        </button>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
// Inisialisasi flatpickr untuk multiple date selection
flatpickr("#tanggal-dinas-multiple", {
    mode: "multiple",
    dateFormat: "Y-m-d",
    maxDate: new Date().fp_incr(365), // 1 tahun ke depan
    onChange: function(selectedDates, dateStr, instance) {
        // Update jumlah hari
        let datesArray = dateStr ? dateStr.split(', ') : [];
        let jumlahHari = datesArray.length;
        $('#jumlah-hari-dinas').text(jumlahHari + ' Hari');
        
        // Generate detail items berdasarkan tanggal yang dipilih
        generateDetailFromDates(datesArray);
    }
});

// Fungsi untuk generate detail dari multiple dates
function generateDetailFromDates(datesArray) {
    let container = $('#auto-detail-container');
    container.empty();
    
    datesArray.forEach(function(date, index) {
        let html = `
        <div class="p-4 bg-white border border-gray-200 rounded-lg detail-item" data-index="\${index}">
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" id="detaildinas-\${index}-tanggal" name="DetailDinas[\${index}][tanggal]" class="block w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" value="\${date}" readonly />
                </div>
                <div class="flex-1">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Keterangan (optional)</label>
                    <input type="text" id="detaildinas-\${index}-keterangan" name="DetailDinas[\${index}][keterangan]" class="block w-full px-3 py-2 text-sm placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Keterangan" />
                </div>
                <div class="flex-1">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                    <div class="flex space-x-4">
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" type="radio" name="DetailDinas[\${index}][status]" id="status_pending_\${index}" value="0" checked>
                            <label class="ml-2 text-sm text-gray-700" for="status_pending_\${index}">Pending</label>
                        </div>
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" type="radio" name="DetailDinas[\${index}][status]" id="status_approved_\${index}" value="1">
                            <label class="ml-2 text-sm text-gray-700" for="status_approved_\${index}">Disetujui</label>
                        </div>
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" type="radio" name="DetailDinas[\${index}][status]" id="status_rejected_\${index}" value="2">
                            <label class="ml-2 text-sm text-gray-700" for="status_rejected_\${index}">Ditolak</label>
                        </div>
                    </div>
                </div>
                <div class="flex-none">
                    <button type="button" class="inline-flex items-center justify-center w-10 h-10 text-red-600 transition-colors duration-200 rounded-lg bg-red-50 hover:bg-red-100 remove-detail-btn" data-index="\${index}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        `;
        container.append(html);
    });
    
    // Update total index counter
    updateIndexCounter();
}

// Fungsi untuk menambah detail manual
$('#add-detail').click(function() {
    let index = $('.detail-item').length;
    let html = `
    <div class="p-4 bg-white border border-gray-200 rounded-lg detail-item" data-index="\${index}">
        <div class="flex items-end gap-4">
            <div class="flex-1">
                <label class="block mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" id="detaildinas-\${index}-tanggal" name="DetailDinas[\${index}][tanggal]" class="block w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
            </div>
            <div class="flex-1">
                <label class="block mb-1 text-sm font-medium text-gray-700">Keterangan (optional)</label>
                <input type="text" id="detaildinas-\${index}-keterangan" name="DetailDinas[\${index}][keterangan]" class="block w-full px-3 py-2 text-sm placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Keterangan" />
            </div>
            <div class="flex-1">
                <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                <div class="flex space-x-4">
                    <div class="flex items-center">
                        <input class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" type="radio" name="DetailDinas[\${index}][status]" id="status_pending_\${index}" value="0" checked>
                        <label class="ml-2 text-sm text-gray-700" for="status_pending_\${index}">Pending</label>
                    </div>
                    <div class="flex items-center">
                        <input class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" type="radio" name="DetailDinas[\${index}][status]" id="status_approved_\${index}" value="1">
                        <label class="ml-2 text-sm text-gray-700" for="status_approved_\${index}">Disetujui</label>
                    </div>
                    <div class="flex items-center">
                        <input class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" type="radio" name="DetailDinas[\${index}][status]" id="status_rejected_\${index}" value="2">
                        <label class="ml-2 text-sm text-gray-700" for="status_rejected_\${index}">Ditolak</label>
                    </div>
                </div>
            </div>
            <div class="flex-none">
                <button type="button" class="inline-flex items-center justify-center w-10 h-10 text-red-600 transition-colors duration-200 rounded-lg bg-red-50 hover:bg-red-100 remove-detail-btn" data-index="\${index}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    `;
    $('#detail-container').append(html);
    updateIndexCounter();
});

// Fungsi untuk menghapus detail - PERBAIKAN UTAMA
$(document).on('click', '.remove-detail-btn', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    let index = $(this).data('index');
    let detailItem = $(this).closest('.detail-item');
    
    if(confirm('Yakin ingin menghapus detail ini?')) {
        detailItem.remove();
        updateIndexCounter();
    }
});

// Fungsi untuk update index counter setelah penambahan/penghapusan
function updateIndexCounter() {
    $('.detail-item').each(function(i) {
        $(this).attr('data-index', i);
        $(this).find('input, label, button').each(function() {
            let attrFor = $(this).attr('for');
            let name = $(this).attr('name');
            let dataIndex = $(this).attr('data-index');
            
            if(attrFor) {
                let newFor = attrFor.replace(/\d+/, i);
                $(this).attr('for', newFor);
            }
            if(name) {
                let newName = name.replace(/\[\d+\]/, '[' + i + ']');
                $(this).attr('name', newName);
            }
            if($(this).attr('id')) {
                let newId = $(this).attr('id').replace(/\d+/, i);
                $(this).attr('id', newId);
            }
            if(dataIndex !== undefined) {
                $(this).attr('data-index', i);
            }
        });
    });
}

// Hitung jumlah hari saat form load
let initialDates = $('#tanggal-dinas-multiple').val();
if (initialDates) {
    let datesArray = initialDates.split(', ');
    let jumlahHari = datesArray.length;
    $('#jumlah-hari-dinas').text(jumlahHari + ' Hari');
} else {
    $('#jumlah-hari-dinas').text('0 Hari');
}

// Pastikan fungsi berjalan setelah DOM siap
$(document).ready(function() {
    console.log('Detail dinas script loaded');
});
JS;
$this->registerJs($js);
?>

<!-- Include Flatpickr CSS dan JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Tambahkan FontAwesome jika diperlukan -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">