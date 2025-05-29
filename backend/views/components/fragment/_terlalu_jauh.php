<?php

use yii\widgets\ActiveForm;
?>


<div data-modal-target="popup-modal-terlalujauh" data-modal-toggle="popup-modal-terlalujauh" class="grid hidden mt-5 place-items-center " id="alasanterlalujauh">
    <button class="block text-white  font-medium rounded-lg text-sm px-5 py-2.5 text-center " type="button">
        <div class="grid place-items-center">
            <div class="w-[60px] h-[60px] bg-orange-50 border border-gray rounded-full grid place-items-center">
                <div class="flex flex-col items-start justify-center p-1 space-y-1 font-black text-center text-white bg-blue-500 rounded-md ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                        <path fill="white" d="m16.37 16.1l-4.62-4.63l-.11-.11L3.27 3L2 4.27l3.18 3.18C5.06 7.95 5 8.46 5 9c0 5.25 7 13 7 13s1.67-1.85 3.37-4.35L18.73 21L20 19.72M12 6.5A2.5 2.5 0 0 1 14.5 9c0 .73-.33 1.39-.83 1.85l3.63 3.65c.98-1.88 1.7-3.82 1.7-5.5a7 7 0 0 0-7-7c-2 0-3.76.82-5.04 2.14l3.19 3.19c.46-.51 1.11-.83 1.85-.83" />
                    </svg>
                </div>
            </div>
            <p class='mt-2 text-center text-black capitalize' style="font-weight:600; font-size:16px;">Alasan Terlalu Jauh</p>
        </div>
    </button>
</div>


<div id="popup-modal-terlalujauh" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full" style="z-index: 9999 !important; ">
    <div class="relative w-full max-w-md max-h-full p-4 ">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center " data-modal-hide="popup-modal-terlalujauh">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="py-2" style="padding:10px 4px;">
                <?php
                $formAbsen = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',  'action' => ['home/absen-terlalujauh']]); ?>
                <?= $formAbsen->field($model, 'latitude')->hiddenInput(['class' => 'lati'])->label(false) ?>
                <?= $formAbsen->field($model, 'longitude')->hiddenInput(['class' => 'longi'])->label(false) ?>
                <?= $formAbsen->field($model, 'alasan_terlalu_jauh')->textarea(['class' => 'py-1 w-full border border-gray-200 rounded-md', 'required' => true, 'rows' => 7, 'placeholder' => 'Alasan Anda Terlalu Jauh Dari Lokasi Penempatan Kerja'])->label(false) ?>

                <?php if ($dataJam['karyawan']['is_shift'] && $manual_shift == 0): ?>
                    <?= $formAbsen->field($model, 'id_shift')->radioList([
                        5 => 'Shift 1 (06:00 - 14:00)',
                        6 => 'Shift 2 (14:00 - 22:00)',
                        7 => 'Shift 3 (22:00 - 06:00)'
                    ], [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return "<div class='flex items-center mb-4'>
            <input id='shift-terlalujauh-{$value}' type='radio' name='{$name}' value='{$value}' class='w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 shift-radio focus:ring-blue-500'>
            <label for='shift-terlalujauh-{$value}' class='text-sm font-medium text-gray-900 ms-2'>{$label}</label>
        </div>";
                        }
                    ])->label(false) ?>
                <?php endif; ?>


                <div class="flex items-center justify-end space-x-4">

                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Submit
                    </button>

                    <button data-modal-hide="popup-modal-terlalujauh" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ketika modal terlalu jauh akan ditampilkan
        document.querySelector('[data-modal-toggle="popup-modal-terlalujauh"]').addEventListener('click', function() {
            // Cari radio button yang dipilih di form utama
            const selectedShift = document.querySelector('#my-form .shift-radio:checked');

            if (selectedShift) {
                const shiftValue = selectedShift.value;
                // Set nilai yang sama di form terlalu jauh
                document.querySelector(`#my-form-terlalujauh input.shift-radio[value="${shiftValue}"]`).checked = true;
            }
        });
    });
</script>