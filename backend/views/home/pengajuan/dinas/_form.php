<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'Pengajuan Dinas Luar';

$form = ActiveForm::begin(); ?>

<div class="relative min-h-[85dvh]">

    <div class="mb-5">
        <label for="tanggal-dinas" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Tanggal Dinas</label>
        <?= $form->field($dinasDetail, 'tanggal')->textInput([
            'id' => 'tanggal-dinas',
            'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5',
        ])->label(false) ?>
        <p class="m-0 -mt-1 text-xs text-gray-700">Anda dapat memilih beberapa tanggal sekaligus.</p>
    </div>

    <div class="mb-5">
        <label for="jumlah-hari-dinas" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Jumlah Hari</label>
        <input type="text" disabled class="disabled:bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" id="jumlah-hari-dinas">
    </div>

    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Estimasi Biaya</label>
        <?= $form->field($model, 'estimasi_biaya')->textInput(['required' => true, 'type' => 'number', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>

    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Keterangan Perjalanan</label>
        <?= $form->field($model, 'keterangan_perjalanan')->textarea(['required' => true, 'rows' => '10',  'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>

    <!-- Hidden field untuk menyimpan data detail dinas -->
    <input type="hidden" name="tanggal_dinas" id="tanggal-dinas-hidden">

    <div class="absolute bottom-0 left-0 right-0 ">
        <div class="">
            <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script>
    $(document).ready(function() {
        // Inisialisasi flatpickr untuk multiple date selection
        flatpickr("#tanggal-dinas", {
            mode: "multiple",
            dateFormat: "Y-m-d",
            maxDate: new Date().fp_incr(365), // 1 tahun ke depan
            onChange: function(selectedDates, dateStr, instance) {
                // Update jumlah hari
                let datesArray = dateStr ? dateStr.split(',') : [];
                let jumlahHari = datesArray.length;
                $('#jumlah-hari-dinas').val(jumlahHari + ' Hari');

                // Simpan tanggal ke hidden field untuk diproses di controller
                $('#tanggal-dinas-hidden').val(dateStr);
            }
        });

        // Hitung jumlah hari saat form load
        let initialDates = $('#tanggal-dinas').val();
        if (initialDates) {
            let datesArray = initialDates.split(',');
            let jumlahHari = datesArray.length;
            $('#jumlah-hari-dinas').val(jumlahHari + ' Hari');
        } else {
            $('#jumlah-hari-dinas').val('0 Hari');
        }
    });
</script>