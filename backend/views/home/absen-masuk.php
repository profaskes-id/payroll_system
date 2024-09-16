<?php

use backend\assets\AppAsset;
use backend\models\Absensi;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */


$this->title = 'Absensis';

$this->params['breadcrumbs'][] = $this->title;

?>



<div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Melakukan Aksi Ini ?</h3>
                <button id="submitButton" data-modal-hide="popup-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Ya, Yakin
                </button>
                <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batalkan </button>
            </div>
        </div>
    </div>
</div>


<section class="grid grid-cols-10  relative overflow-x-hidden min-h-[90dvh]">


    <div class="fixed w-1/2 bottom-0 left-1/2 -translate-x-1/2 z-40 hidden lg:block  ">
        <?= $this->render('@backend/views/components/_footer'); ?>
    </div>


    <!-- content -->
    <div class="col-span-12 w-full  px-5 pt-5  ">


        <div class=" grid grid-cols-12 place-items-center">
            <div class="col-span-12  w-full">

                <?= $this->render('@backend/views/components/fragment/_time'); ?>


                <p class=" text-gray-500 text-center mt-2 -mb-3 capitalize">Lokasi Anda</p>
                <a href="/panel/home/your-location">
                    <div class="capitalize flex justify-around items-center bg-sky-500/10 p-1 text-[13px] w-[80%] mx-auto mt-3 rounded-full">
                        <p>lat : <span id="latitude"></span></p>
                        <p>long : <span id="longitude"></span></p>
                    </div>
                </a>
                <div class="mt-10 grid place-items-center">




                    <?php if (count($absensiToday) > 0) : ?>
                        <?php if (empty($absensiToday[0]->jam_pulang)) : ?>
                            <?php $formAbsen = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',  'action' => ['home/absen-pulang']]); ?>
                            <div class="grid place-items-center border border-[#D51405]/10 p-3 rounded-full">
                                <button class="all-none border border-[#D51405]/50 p-3 rounded-full" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button">
                                    <div class=" flex w-[225px] h-[225px] bg-gradient-to-r from-[#CE1705] to-[#EF0802] shadow-2xl shadow-[#D51405] rounded-full  ">
                                        <?= Html::img('@root/images/icons/click.svg', ['alt' => 'absen', 'class' => ' w-full  h-full object-cover scale-[0.7] ']) ?>
                                        <!-- <p class="font-bold text-white m-0">Absen Keluar</p> -->
                                    </div>
                                </button>
                            </div>
                            <?php ActiveForm::end(); ?>
                        <?php else : ?>
                            <button class="all-none" type="button" disabled>
                                <div class="flex w-[225px] h-[225px] bg-gradient-to-r from-[#686161] to-[#2b2b2b] shadow-2xl shadow-[#9b9b9b] rounded-full  ">
                                    <?= Html::img('@root/images/icons/click.svg', ['alt' => 'absen', 'class' => ' w-full  h-full object-cover scale-[0.7] ']) ?>
                                    <!-- <p class="font-bold text-white m-0">Selsai</p> -->
                                </div>
                            </button>
                        <?php endif ?>


                    <?php else : ?>
                        <?php $formAbsen = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',  'action' => ['home/absen-masuk']]); ?>
                        <?= $formAbsen->field($model, 'latitude')->hiddenInput(['class' => 'latitude'])->label(false) ?>
                        <?= $formAbsen->field($model, 'longitude')->hiddenInput(['class' => 'longitude'])->label(false) ?>

                        <div class="grid place-items-center border border-[#EB5A2B]/10 p-4 rounded-full">
                            <button class="all-none border border-[#EB5A2B]/50 p-4 rounded-full" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button">
                                <div class=" flex w-[225px] h-[225px] bg-gradient-to-r from-[#EB5A2B] to-[#EA792B] shadow-2xl shadow-[#EB5A2B] rounded-full  ">
                                    <?= Html::img('@root/images/icons/click.svg', ['alt' => 'absen', 'class' => ' w-full  h-full object-cover scale-[0.7] ']) ?>
                                    <!-- <p class=" font-bold text-white m-0">Absen Masuk</p> -->
                                </div>
                            </button>
                        </div>


                        <?php ActiveForm::end(); ?>
                    <?php endif ?>

                </div>
            </div>
        </div>

        <!-- ? mobile izin -->
        <div class="flex justify-between  items-center pb-32">
            <?php if (count($absensiToday) > 0) : ?>
                <?php if (empty($absensiToday[0]->jam_pulang)) : ?>
                    <div class="grid place-items-center mt-5">
                        <a href="/panel/home/create">
                            <div class="grid place-items-center">
                                <div class="w-[60px] h-[60px]  border bg-red-50 border-gray rounded-full grid place-items-center">
                                    <div class="font-black text-white w-8 h-8 text-center grid place-items-center rounded-full bg-rose-500">
                                        <span class="w-1 h-5 bg-white rounded-xl"></span>
                                    </div>
                                </div>
                                <p class="mt-2 font-medium capitalize">Izin Pulang Cepat </p>
                            </div>
                        </a>
                    </div>
                <?php else : ?>

                    <div class="grid place-items-center mt-5">
                        <a href="">

                            <div class="grid place-items-center">
                                <div class="w-[60px] h-[60px]  border bg-red-50 border-gray rounded-full grid place-items-center">
                                    <div class="font-black text-white w-8 h-8 text-center grid place-items-center rounded-full bg-stone-500">
                                        <span class="w-5 h-1 bg-white rounded-xl"></span>
                                    </div>
                                </div>
                                <p class="mt-2 font-medium capitalize">Izin Tidak hadir</p>
                            </div>
                        </a>
                    </div>
                <?php endif ?>

            <?php else : ?>
                <div class="grid place-items-center mt-5">
                    <a href="/panel/home/create">

                        <div class="grid place-items-center">
                            <div class="w-[60px] h-[60px]  border bg-red-50 border-gray rounded-full grid place-items-center">
                                <div class="font-black text-white w-8 h-8 text-center grid place-items-center rounded-full bg-rose-500">
                                    <span class="w-5 h-1 bg-white rounded-xl"></span>
                                </div>
                            </div>
                            <p class="mt-2 font-medium capitalize">Izin Tidak hadir</p>
                        </div>
                    </a>
                </div>
            <?php endif ?>

            <?php if ($isTerlambatActive): ?>
                <?= $this->render('@backend/views/components/fragment/_terlambat', ['model' => $model]); ?>
            <?php endif ?>

            <div class="grid place-items-center mt-5">

                <?= Html::a('
              <div class="grid place-items-center">
              <div class="w-[60px] h-[60px] bg-orange-50 border border-gray rounded-full grid place-items-center">
              <div class="font-black text-white w-8 h-8 text-center flex justify-center ps-1.5 items-start flex-col space-y-1 rounded-sm bg-orange-500">
              <span class="w-5 h-1 bg-white rounded-xl"></span>
              <span class="w-5 h-1 bg-white rounded-xl"></span>
              <span class="w-2 h-1 bg-white rounded-xl"></span>
              </div>
              </div>
              <p class="mt-2 font-medium capitalize">Lihat History</p>
              </div>
              ', ['/home/view', 'id_user' => Yii::$app->user->identity->id]) ?>
            </div>
        </div>
    </div>


</section>

<div class="lg:hidden fixed bottom-0 left-0 right-0 z-50">

    <?= $this->render('@backend/views/components/_footer'); ?>
</div>




<?php
$redirectUrl = Yii::getAlias('@web'); // Atau ganti dengan alias yang sesuai
?>
<script>
    function checkLocationAccess() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {},
                function(error) {
                    alert('Izinkan Browser Untuk Mengakses Lokasi Anda');
                }
            );
        } else {
            alert('Izinkan Browser Untuk Mengakses Lokasi Anda');
        }
    }

    checkLocationAccess();
</script>


<?php
$dataToday = ArrayHelper::toArray($dataJam);
$dataTodayJson = json_encode($dataToday, JSON_PRETTY_PRINT);
?>
<script>
    let todayJson = <?= $dataTodayJson ?>;

    jam_masuk = todayJson?.today?.jam_masuk;
    max_telat = todayJson?.karyawan?.max_terlambat;
    let form = document.getElementById('my-form');
    let submitButton = document.getElementById('submitButton');

    submitButton.addEventListener('click', function() {

        function getWaktuSaatIni() {
            const sekarang = new Date();
            return {
                jam: sekarang.getHours(),
                menit: sekarang.getMinutes(),
                detik: sekarang.getSeconds()
            };
        }

        const {
            jam,
            menit,
            detik
        } = getWaktuSaatIni();


        const [batasJam, batasMenit, batasDetik] = jam_masuk.split(':').map(Number);
        const [maximalTelatJam, maximalTelatbatasMenit, maximalTelatbatasDetik] = max_telat.split(':').map(Number);

        function isSebelumBatas(jam, menit, detik) {
            if (jam < batasJam) return true;
            if (jam === batasJam && menit < batasMenit) return true;
            if (jam === batasJam && menit === batasMenit && detik < batasDetik) return true;
            return false;
        }

        function isTerlambat(jam, menit, detik) {
            console.info(jam, batasJam, menit, maximalTelatbatasMenit)
            if (jam > batasJam) return true;
            if (jam == batasJam && menit < maximalTelatbatasMenit) return true;
            // if (jam === batasJam && menit === maximalTelatMenit && detik < maximalTelatDetik) return true;
            return false;
        }

        const alasanTerlambat = document.querySelector('#alasanTerlambat');
        if (isSebelumBatas(jam, menit, detik)) {
            form.submit();
            alert('Terkirim jam 08:00:00, sekarang ' + jam + ':' + menit + ':' + detik);
        } else if (isTerlambat(jam, menit, detik)) {
            if (jam === batasJam && menit <= maximalTelatbatasMenit) {
                alert('Kenapa terlambat? Sekarang ' + jam + ':' + menit + ':' + detik);
                form.submit();
                // alasanTerlambat.classList.toggle('hidden');

            } else {
                alert('Tidak masuk. Sekarang ' + jam + ':' + menit + ':' + detik);
                form.submit();
                // alasanTerlambat.classList.toggle('hidden');
            }
        } else {
            alert('Anda Terlambat. Sekarang ' + jam + ':' + menit + ':' + detik);
            // alasanTerlambat.classList.toggle('hidden');
        }
    });


    window.addEventListener('load', function() {
        navigator.geolocation.watchPosition(function(position) {
                document.getElementById('latitude').textContent = position.coords.latitude.toFixed(10);
                document.getElementById('longitude').textContent = position.coords.longitude.toFixed(10);
                document.querySelector('.latitude').value = position.coords.latitude.toFixed(10);
                document.querySelector('.longitude').value = position.coords.longitude.toFixed(10);
            },
            function(error) {
                console.log("Error: " + error.message);
            }, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            });


    });
</script>