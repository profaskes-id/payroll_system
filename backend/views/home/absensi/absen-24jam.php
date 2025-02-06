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


<!-- <div class="hidden lg:block pt-10"> -->
<!-- <p class="text-center">Absensi hanya dapat dilakukan melalui perangkat mobile atau tablet untuk menjaga keakuratan lokasi Anda.</p> -->
<!-- </div> -->


<section class="">

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



    <section class="grid grid-cols-10  relative overflow-x-hidden min-h-[90dvh]">


        <div class="fixed w-1/2 bottom-0 left-1/2 -translate-x-1/2 z-40 hidden lg:block  ">
            <?= $this->render('@backend/views/components/_footer'); ?>
        </div>


        <!-- content -->
        <div class="col-span-12 w-full  px-5 pt-5  ">


            <div class=" grid grid-cols-12 place-items-center">
                <div class="col-span-12  w-full">

                    <?= $this->render('@backend/views/components/fragment/_time'); ?>

                    <style>
                        .moving-text {
                            display: flex;
                            animation: move 10s linear infinite;
                        }

                        @keyframes move {
                            0% {
                                transform: translateX(100%);
                            }

                            100% {
                                transform: translateX(-100%);
                            }
                        }
                    </style>
                    <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 justify-center items-center">
                        <div class="w-full ">
                            <p class="  text-xs text-center mt-2 -mb-3 capitalize">Lokasi Anda</p>
                            <div class="bg-sky-500/10 rounded-full p-1 overflow-hidden max-w-[80dvw]  mt-3 mx-auto">
                                <a href="/panel/home/your-location">
                                    <div class="">
                                        <div class="moving-text capitalize flex justify-around items-center text-[12px] ">
                                            <p id="alamat" style="text-wrap: nowrap !important;"></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="w-full ">
                            <p class="text-xs text-center mt-2 -mb-3">Jam Kerja </p>
                            <div class="capitalize flex justify-around items-center bg-orange-500/10 p-1 text-[13px] w-[80%] mx-auto mt-3 rounded-full">
                                <p>Absensi Di Set Untuk 24 Jam</p>
                            </div>
                        </div>
                    </div>





                    <?php
                    // jika absensi terakhir belum ada dan sudah masuk, suruh pulang
                    if ($absensiTerakhir != null && $absensiTerakhir['jam_pulang'] == null && $absensiTerakhir['jam_masuk'] != null) : ?>
                        <?php $formAbsen = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',  'action' => ['home/absen-24jam-pulang']]); ?>

                        <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 justify-center items-center">

                            <div class="grid place-items-center border border-[#D51405]/10 p-3 rounded-full">
                                <button class="all-none border border-[#D51405]/50 p-3 rounded-full " data-modal-target="popup-modal-keluar" data-modal-toggle="popup-modal-keluar" type="button" id="pulang-button">
                                    <div class=" flex relative  w-[225px] h-[225px] bg-gradient-to-r from-[#CE1705] to-[#EF0802] shadow-2xl shadow-[#D51405] rounded-full  ">
                                        <?= Html::img('@root/images/icons/click.svg', ['alt' => 'absen', 'class' => ' w-full  h-full -mt-3 object-cover scale-[0.7] ']) ?>
                                        <p class=" font-normal text-white absolute m-0 bottom-5 left-1/2 -translate-x-1/2">Absen Pulang</p>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <p class="text-xs my-5 text-gray-500  text-center" id="message">Absen Pulang Dapat Dilakukan Kapan Saja Jika anda munyudahi pekerjaan</p>
                        <?php ActiveForm::end(); ?>

                        <!-- // jika absensi terakhir sudah ada dan masuk sudah ada buat absen baru -->
                    <?php else : ?>
                        <?php $formAbsen = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',  'action' => ['home/absen-24jam']]); ?>
                        <?= $formAbsen->field($model, 'latitude')->hiddenInput(['class' => 'latitude'])->label(false) ?>
                        <?= $formAbsen->field($model, 'longitude')->hiddenInput(['class' => 'longitude'])->label(false) ?>

                        <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 justify-center items-center">
                            <div class="grid place-items-center border border-[#EB5A2B]/10 p-4 rounded-full">
                                <button class="all-none border border-[#EB5A2B]/50 p-4 rounded-full" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button">
                                    <div class=" flex relative w-[225px] h-[225px] bg-gradient-to-r from-[#EB5A2B] to-[#EA792B] shadow-2xl shadow-[#EB5A2B] rounded-full  ">
                                        <?= Html::img('@root/images/icons/click.svg', ['alt' => 'absen', 'class' => ' w-full  h-full -mt-3 object-cover scale-[0.7] ']) ?>
                                        <p class=" font-normal text-white absolute m-0 bottom-5 left-1/2 -translate-x-1/2">Absen Masuk</p>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    <?php endif; ?>




                </div>
            </div>

            <!-- ? mobile izin -->
            <div class="flex justify-center  items-center pb-32">


                <?= $this->render('@backend/views/components/fragment/_terlambat', ['model' => $model]); ?>
                <?= $this->render('@backend/views/components/fragment/_terlalu_jauh', ['model' => $model]);
                ?>
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

    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-50">

        <?= $this->render('@backend/views/components/_footer'); ?>
    </div>


</section>






<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<?php
$redirectUrl = Yii::getAlias('@web');
?>
<script>
    window.addEventListener('load', function() {

        function checkLocationAccess() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {},
                    function(error) {
                        Swal.fire({
                            confirmButtonColor: "#3085d6",
                            text: "Izinkan Browser Untuk Mengakses Lokasi Anda!"
                        });
                    }
                );
            } else {
                Swal.fire({
                    confirmButtonColor: "#3085d6",
                    text: "Izinkan Browser Untuk Mengakses Lokasi Anda!"
                });
            }
        }

        checkLocationAccess();

        let latitudeBig = document.querySelector('.latitude');
        let longitudeBig = document.querySelector('.longitude');


        navigator.geolocation.watchPosition(function(position) {
            latitudeBig.value = position.coords.latitude.toFixed(10);
            longitudeBig.value = position.coords.longitude.toFixed(10);

            globatLat = position.coords.latitude.toFixed(10);
            globatLong = position.coords.longitude.toFixed(10);

            if (position) {
                dapatkanAlamat(globatLat, globatLong);
            }

        }, function(error) {
            // Menangani kesalahan dan menampilkan SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan: ' + error.message,
                confirmButtonColor: "#3085d6",
                footer: '<a href="">Mengapa ini terjadi?</a>'
            });
        }, {
            enableHighAccuracy: true,
            timeout: 10000, // Ubah timeout menjadi 10 detik
            maximumAge: 0
        });




        function dapatkanAlamat(lat, lon) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                .then(response => response.json())
                .then(data => {
                    var alamatLengkap = data.display_name;
                    document.getElementById('alamat').textContent = alamatLengkap;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('alamat').textContent = "Alamat tidak ditemukan, Refresh halaman";
                });
        }

        let form = document.getElementById('my-form');

        let submitButton = document.getElementById('submitButton');

        submitButton.addEventListener('click', function() {
            form.submit();
        });

        let submitButtonKeluar = document.getElementById('submitButtonKeluar');

        submitButtonKeluar.addEventListener('click', function() {
            form.submit();
        });
    });
</script>