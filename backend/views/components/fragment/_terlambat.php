<?php

use yii\widgets\ActiveForm;
?>


<div data-modal-target="popup-modal-terlambat" data-modal-toggle="popup-modal-terlambat" class="grid place-items-center mt-5" id="alasanTerlambat">
  <button class="block text-white  font-medium rounded-lg text-sm px-5 py-2.5 text-center " type="button">
    <div class="grid place-items-center">
      <div class="w-[60px] h-[60px] bg-orange-50 border border-gray rounded-full grid place-items-center">
        <div class="font-black text-white w-8 h-8 text-center flex justify-center p-0.5 items-start flex-col space-y-1 rounded-sm bg-blue-500">
          <img src="<?= Yii::getAlias("@root") ?>/images/icons/hourglass.svg" width="30px" alt="jamPasir">
        </div>
      </div>
      <p class='mt-2 font-medium capitalize text-black'>Alasan Terlambat</p>
    </div>
  </button>
</div>

<div id="popup-modal-terlambat" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="relative p-4 w-full max-w-md max-h-full">
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
      <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center " data-modal-hide="popup-modal-terlambat">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
        <span class="sr-only">Close modal</span>
      </button>
      <div class="p-4 md:p-5 text-center">
        <h1>Alasan Terlambat</h1>
        <?php
        $formAbsen = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',  'action' => ['home/absen-masuk']]); ?>

        <?= $formAbsen->field($model, 'id_karyawan')->textInput(['value' => $model->id_karyawan, 'class' => 'py-2 px-5 border border-gray-200 rounded-md'])->label(false) ?>
        <?= $formAbsen->field($model, 'tanggal')->textInput(['value' => $model->tanggal, 'class' => 'py-2 px-5 border border-gray-200 rounded-md'])->label(false) ?>
        <?= $formAbsen->field($model, 'alasan_terlambat')->textarea(['class' => 'py-2 px-5 border border-gray-200 rounded-md'])->label(false) ?>

        <div class="flex justify-center items-center space-x-4">

          <button data-modal-hide="popup-modal-terlambat" type="button" class="text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
            Submit
          </button>

          <button data-modal-hide="popup-modal-terlambat" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Tidak, cancel</button>
        </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>