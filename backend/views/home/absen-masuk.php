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
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Anda Akan Melakukan Aksi Absen Masuk ?</h3>
                <button id="submitButton" data-modal-hide="popup-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Ya, Yakin
                </button>
                <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batalkan </button>
            </div>
        </div>
    </div>
</div>
<div id="popup-modal-keluar" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal-keluar">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Anda Akan Melakukan Aksi Absen Pulang ?</h3>
                <button id="submitButtonKeluar" data-modal-hide="popup-modal-keluar" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Ya, Yakin
                </button>
                <button data-modal-hide="popup-modal-keluar" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batalkan </button>
            </div>
        </div>
    </div>
</div>


<section class="flex justify-center items-center w-full h-screen overflow-x-hidden">
    <h1 class="w-[50%] text-xl text-center text-rose-900 capitalize">Mohon Maaf, pengisian absen hanya dapat dilakukan melalui perangkat mobile atau tablet Demi memastikan keakuratan lokasi Anda. Terima kasih atas pengertiannya.</h1>
</section>

<section class="lg:hidden grid grid-cols-10 z-10  relative overflow-x-hidden min-h-[90dvh]">


    <!-- content -->
    <div class="col-span-12 w-full  px-5 pt-5  ">


        <div class=" grid grid-cols-12 place-items-center">
            <div class="col-span-12  w-full">

                <?= $this->render('@backend/views/components/fragment/_time'); ?>

                <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 justify-center items-center">
                    <div class="w-full ">

                        <p class="  text-xs text-center mt-2 -mb-3 capitalize">Lokasi Anda</p>
                        <a href="/panel/home/your-location">
                            <div class="capitalize flex justify-around items-center bg-sky-500/10 p-1 text-[13px] w-[80%] mx-auto mt-3 rounded-full">
                                <p>lat : <span id="latitude"></span></p>
                                <p>long : <span id="longitude"></span></p>
                            </div>
                        </a>
                    </div>
                    <div class="w-full ">
                        <p class="text-xs text-center mt-2 -mb-3">Jam Kerja </p>
                        <div class="capitalize flex justify-around items-center bg-orange-500/10 p-1 text-[13px] w-[80%] mx-auto mt-3 rounded-full">
                            <p><?= date('g:i A', strtotime($jamKerjaToday->jam_masuk)) ?></p>
                            <p>S/D</p>
                            <p><?= date('g:i A', strtotime($jamKerjaToday->jam_keluar)) ?></p>
                        </div>
                    </div>

                </div>


                <div class="mt-10 grid place-items-center">

                    <?php if (count($absensiToday) > 0) : ?>
                        <?php if (empty($absensiToday[0]->jam_pulang)) : ?>
                            <?php $formAbsen = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',  'action' => ['home/absen-pulang']]); ?>

                            <div class="grid place-items-center border border-[#D51405]/10 p-3 rounded-full">
                                <button class="all-none border border-[#D51405]/50 p-3 rounded-full disabled:opacity-50" disabled data-modal-target="popup-modal-keluar" data-modal-toggle="popup-modal-keluar" type="button" id="pulang-button">
                                    <div class=" flex relative  w-[225px] h-[225px] bg-gradient-to-r from-[#CE1705] to-[#EF0802] shadow-2xl shadow-[#D51405] rounded-full  ">
                                        <?= Html::img('@root/images/icons/click.svg', ['alt' => 'absen', 'class' => ' w-full  h-full -mt-3 object-cover scale-[0.7] ']) ?>
                                        <p class=" font-normal text-white absolute m-0 bottom-5 left-1/2 -translate-x-1/2">Absen Pulang</p>
                                    </div>
                                </button>
                            </div>
                            <p class="text-xs my-5 text-gray-500 " id="message">Absen Pulang Aktif Hanya Saat Jam Pulang</p>
                            <?php ActiveForm::end(); ?>
                        <?php else : ?>
                            <button class="all-none" type="button" disabled>
                                <div class="flex relative  w-[225px] h-[225px] bg-gradient-to-r from-[#686161] to-[#2b2b2b] shadow-2xl shadow-[#9b9b9b] rounded-full  ">
                                    <?= Html::img('@root/images/icons/click.svg', ['alt' => 'absen', 'class' => ' w-full  h-full -mt-3 object-cover scale-[0.7] ']) ?>
                                    <p class=" font-normal text-white absolute m-0 bottom-5 left-1/2 -translate-x-1/2">Selesai</p>
                                </div>
                            </button>
                        <?php endif ?>


                    <?php else : ?>
                        <?php $formAbsen = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',  'action' => ['home/absen-masuk']]); ?>
                        <?= $formAbsen->field($model, 'latitude')->hiddenInput(['class' => 'latitude'])->label(false) ?>
                        <?= $formAbsen->field($model, 'longitude')->hiddenInput(['class' => 'longitude'])->label(false) ?>

                        <div class="grid place-items-center border border-[#EB5A2B]/10 p-4 rounded-full">
                            <button class="all-none border border-[#EB5A2B]/50 p-4 rounded-full" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button">
                                <div class=" flex relative w-[225px] h-[225px] bg-gradient-to-r from-[#EB5A2B] to-[#EA792B] shadow-2xl shadow-[#EB5A2B] rounded-full  ">
                                    <?= Html::img('@root/images/icons/click.svg', ['alt' => 'absen', 'class' => ' w-full  h-full -mt-3 object-cover scale-[0.7] ']) ?>
                                    <p class=" font-normal text-white absolute m-0 bottom-5 left-1/2 -translate-x-1/2">Absen Masuk</p>
                                </div>
                            </button>
                        </div>


                        <?php ActiveForm::end(); ?>
                    <?php endif ?>

                </div>
            </div>
        </div>

        <!-- ? mobile izin -->
        <div class="flex justify-between  relative z-20 items-center mb-32">
            <?php if (count($absensiToday) > 0) : ?>
                <?php if (empty($absensiToday[0]->jam_pulang)) : ?>
                    <div class="grid place-items-center mt-5">
                        <a href="/panel/home/pulang-cepat">
                            <div class="grid place-items-center">
                                <div class="w-[60px] h-[60px]  border bg-red-50 border-gray rounded-full grid place-items-center">
                                    <div class="font-black text-white w-8 h-8 text-center grid place-items-center rounded-full bg-rose-500">
                                        <span class="w-1 h-5 bg-white rounded-xl"></span>
                                    </div>
                                </div>
                                <p class="mt-2 font-medium capitalize text-center">Pulang Cepat </p>
                            </div>
                        </a>
                    </div>
                <?php else : ?>

                    <?php if ($isPulangCepat): ?>

                        <div class="grid place-items-center mt-5">
                            <a href="/panel/home/pulang-cepat">
                                <div class="grid place-items-center">
                                    <div class="w-[60px] h-[60px]  border bg-red-50 border-gray rounded-full grid place-items-center">
                                        <div class="font-black text-white w-8 h-8 text-center grid place-items-center rounded-full bg-yellow-400">
                                            <span class="w-1 h-5 bg-white rounded-xl"></span>
                                        </div>
                                    </div>
                                    <p class="mt-2 font-medium capitalize text-center">Pulang Cepat </p>
                                </div>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="grid place-items-center mt-5">
                            <a href="">
                                <div class="grid place-items-center">
                                    <div class="w-[60px] h-[60px]  border bg-red-50 border-gray rounded-full grid place-items-center">
                                        <div class="font-black text-white w-8 h-8 text-center grid place-items-center rounded-full bg-gray-400">
                                            <span class="w-1 h-5 bg-white rounded-xl"></span>
                                        </div>
                                    </div>
                                    <p class="mt-2 font-medium capitalize text-center">Pulang Cepat </p>
                                </div>
                            </a>
                        </div>
                    <?php endif ?>
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
                            <p class="mt-2 font-medium capitalize text-center">Izin Tidak hadir</p>
                        </div>
                    </a>
                </div>
            <?php endif ?>

            <?= $this->render('@backend/views/components/fragment/_terlambat', ['model' => $model]); ?>
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
              <p class="mt-2 font-medium capitalize text-center">Lihat History</p>
              </div>
              ', ['/home/view', 'id_user' => Yii::$app->user->identity->id]) ?>
            </div>
        </div>
    </div>


</section>






<?php
$redirectUrl = Yii::getAlias('@web');
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
    let submitButtonKeluar = document.getElementById('submitButtonKeluar');

    submitButtonKeluar.addEventListener('click', function() {
        form.submit();
    })

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
            if (jam > batasJam) return true;
            if (jam == batasJam && menit < maximalTelatbatasMenit) return true;
            // if (jam === batasJam && menit === maximalTelatMenit && detik < maximalTelatDetik) return true;
            return false;
        }

        const alasanTerlambat = document.querySelector('#alasanTerlambat');
        if (isSebelumBatas(jam, menit, detik)) {
            form.submit();
        } else if (isTerlambat(jam, menit, detik)) {
            if (jam === batasJam && menit <= maximalTelatbatasMenit) {
                //terlambat dalam masa tenggang
                form.submit();

            } else {
                //terlambat diluar masa tenggang
                alasanTerlambat.classList.toggle('hidden');
            }
        } else {
            //terlambat diluar masa tenggang
            alasanTerlambat.classList.toggle('hidden');
        }
    });


    window.addEventListener('load', function() {
        navigator.geolocation.watchPosition(function(position) {
                document.getElementById('latitude').textContent = position.coords.latitude.toFixed(10);
                document.getElementById('longitude').textContent = position.coords.longitude.toFixed(10);
                document.querySelector('.latitude').value = position.coords.latitude.toFixed(10);
                document.querySelector('.lati').value = position.coords.latitude.toFixed(10);
                document.querySelector('.longi').value = position.coords.longitude.toFixed(10);
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


<script>
    const pulang_button = document.querySelector('#pulang-button');
    jam_pulang = todayJson?.today?.jam_keluar;

    // Fungsi untuk mengaktifkan tombol jika waktu saat ini sudah 15 menit sebelum jam pulang
    function cekWaktu(currentTime) {
        // Jika currentTime tidak diberikan, gunakan waktu saat ini
        const sekarang = currentTime ? new Date(currentTime) : new Date();

        // Mengambil jam, menit, dan detik dari waktu jam pulang
        const [jam, menit, detik] = jam_pulang.split(':').map(Number);

        // Membuat objek Date untuk waktu jam pulang
        const waktuPulang = new Date(sekarang);
        waktuPulang.setHours(jam, menit, detik, 0);

        // Mengurangi 15 menit dari waktu jam pulang
        const toleransi = 1000; // 1 detik dalam milidetik

        // Jika waktu saat ini berada dalam rentang toleransi sekitar jam pulang
        if (Math.abs(sekarang - waktuPulang) <= toleransi) {
            const message = document.querySelector('#message');
            message.classList.add('hidden');
            pulang_button.disabled = false;


        }
    }

    // Mengecek waktu setiap detik
    // const testTime = '2024-09-18T17:00:00';
    // cekWaktu(testTime);
    setInterval(cekWaktu, 30000);
</script>