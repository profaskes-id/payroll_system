<?php
use yii\widgets\ActiveForm;
?>

<div data-modal-target="popup-modal-terlambat" data-modal-toggle="popup-modal-terlambat" class="grid hidden mt-5 place-items-center " id="alasanTerlambat">
  <button class="block text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
    <div class="grid place-items-center">
      <div class="w-[60px] h-[60px] bg-orange-50 border border-gray rounded-full grid place-items-center">
        <div class="font-black text-white w-8 h-8 text-center flex justify-center p-0.5 items-start flex-col space-y-1 rounded-sm bg-blue-500">
          <img src="<?= Yii::getAlias("@root") ?>/images/icons/hourglass.svg" width="30px" alt="jamPasir">
        </div>
      </div>
      <p class='mt-2 text-center text-black capitalize' style="font-wight:600; font-size:16px;">Alasan Terlambat</p>
    </div>
  </button>
</div>

<div id="popup-modal-terlambat" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full" style="z-index: 9999 !important;">
  <div class="relative w-full max-w-md max-h-full p-4">
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
      <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="popup-modal-terlambat">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
        <span class="sr-only">Close modal</span>
      </button>
      <div class="py-2" style="padding:10px 4px;">
        <?php
        $formAbsen = ActiveForm::begin(['method' => 'post', 'id' => 'my-form-terlambat', 'action' => ['home/absen-terlambat']]); ?>
        <?= $formAbsen->field($model, 'latitude')->hiddenInput(['class' => 'coordinate lat'])->label(false) ?>
        <?= $formAbsen->field($model, 'longitude')->hiddenInput(['class' => 'coordinate lon'])->label(false) ?>
        <?= $formAbsen->field($model, 'foto_masuk')->hiddenInput(['class' => 'foto_fr'])->label(false) ?>
        
        <?= $formAbsen->field($model, 'alasan_terlambat')->textarea([
            'class' => 'py-1 w-full border border-gray-200 rounded-md', 
            'required' => true, 
            'rows' => 7, 
            'placeholder' => 'Alasan Anda Terlambat'
        ])->label(false) ?>

        <div class="flex items-center justify-end space-x-4">
          <button type="submit" class="text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
            Submit
          </button>
          <button data-modal-hide="popup-modal-terlambat" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
            Batal
          </button>
        </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pastikan variabel global dapat diakses
    if (typeof currentLat === 'undefined' || typeof currentLon === 'undefined') {
        currentLat = 0;
        currentLon = 0;
        wajah_fr = ''; // Inisialisasi wajah_fr jika belum ada
    }

    document.querySelector('[data-modal-toggle="popup-modal-terlambat"]')?.addEventListener('click', function() {
        // Update koordinat saat modal dibuka
        document.querySelectorAll('.coordinate.lat').forEach(el => el.value = currentLat);
        document.querySelectorAll('.coordinate.lon').forEach(el => el.value = currentLon);
        
        // Update foto wajah jika ada
        const fotoWajah = document.querySelector('#faceData-popup-modal')?.value;
        if (fotoWajah) {
            document.querySelectorAll('.foto_fr').forEach(el => el.value = fotoWajah);
        }
    });
});
</script>