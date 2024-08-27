<?php

use backend\assets\AppAsset;
use backend\models\Absensi;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */


$this->title = 'Absensis';

$this->params['breadcrumbs'][] = $this->title;

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Demo styles -->
<style>
    html,
    body {
        position: relative;
        height: 100%;
    }



    .swiper {
        width: 100%;
        height: 200px;
    }

    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<?php // $this->render('@backend/views/components/_header'); 
?>


<section class="overflow-x-hidden min-h-[80dvh]">
    <div class=" ">
        <div class="h-[350px]">
            <div class="bg-[#faf9f9] h-[200px] w-full">

                <div class="py-3 px-5 flex justify-between items-center">
                    <div>
                        <p class="uppercase text-xl font-bold"><?= $karyawan->nama ?></p>
                        <p class="text-gray-500 text-sm uppercase"><?= $karyawan->kode_karyawan ?></p>
                    </div>
                    <div class="flex gap-3 items-center">


                        <?= Html::a('
              <div class="w-11 h-11 rounded-full border grid place-items-center   border-gray-300">
              <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
              <path fill="#9e9e9e" d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.25 2.52l.77-1.28l-3.52-2.09V8z" />
              </svg>
              </div>
              
              ', ['/home/view', 'id_user' => Yii::$app->user->identity->id]) ?>


                        <div class="w-8 h-8 bg-orange-500 rounded-full"></div>
                    </div>
                </div>

                <!-- swiper -->
                <div class="  relative  justify-center gap-5 content-start p-5">

                    <!-- Swiper -->
                    <div class="swiper mySwiper border rounded-xl overflow-hidden">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide ">
                                <div class="bg-blue-200 w-full h-screen">
                                    <div class="pattern-2 opacity-30">
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="bg-blue-200 w-full h-screen">
                                    <div class="bg-blue-500 w-full h-screen overflow-hidden ">
                                        <div class="pattern-1 opacity-30"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="bg-rose-500 w-full h-screen"></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="bg-blue-200 w-full h-screen"></div>
                            </div>
                        </div>
                        <!-- <div class=" lg:bloc/k swiper-button-next"></div> -->
                        <!-- <div class=" lg:block swiper-button-prev"></div> -->
                        <div class="swiper-pagination"></div>
                    </div>

                    <!-- Swiper JS -->


                    <!-- </section> -->

                </div>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-12 pb-10  gap-2 w-full -mt-10 min-w-screen   ">
        <div class="p-5 col-span-6 bg-gradient-to-r from-neutral-50 to-zinc-100 max-h-[200px] rounded-md ">
            <p class=" font-bold text-xl mb-3">Pengajuan</p>
            <p class="text-gray-600">Pengajuan Berhalangan Hadir </p>
        </div>

        <a href="/panel/pengajuan/cuti" class="  col-span-6   ">
            <div class=" flex justify-center items-center flex-col bg-gradient-to-r from-sky-500/50 to-sky-100 py-5 max-h-[200px] rounded-md ">
                <div class="w-[35px] h-[35px] bg-gray-400 rounded-full"></div>
                <p class="font-semibold text-black/80  mt-5 mb-1">Pengajuan</p>
                <p class="text-black font-normal">Cuti</p>
            </div>
        </a>
        <a href="/panel/pengajuan/lembur" class="  col-span-6  ">
            <div class=" flex-col col-span-6 bg-gradient-to-r from-rose-500/50 to-rose-100 py-5 max-h-[200px] rounded-md flex justify-center items-center">
                <div class="min-w-[35px] min-h-[35px] bg-gray-400 rounded-full"></div>
                <p class="font-semibold text-black/80  mt-5 mb-1">Pengajuan</p>
                <p class="text-black font-normal">Lembur</p>
            </div>
        </a>
        <a href="/panel/pengajuan/dinas" class="  col-span-6  ">
            <div class=" flex-col col-span-6 bg-gradient-to-r from-purple-500/50 to-purple-100 py-5 max-h-[200px] rounded-md flex justify-center items-center">
                <div class="min-w-[35px] min-h-[35px] bg-gray-400 rounded-full"></div>
                <p class="font-semibold text-black/80  mt-5 mb-1">Pengajuan</p>
                <p class="text-black font-normal">Dinas</p>
            </div>
        </a>
    </div>



    <!-- <div class="bg-[#fff] border shadow-current shadow-2xl w-full pb-[120px] rounded-ss-[30px] rounded-se-[30px] px-5"> -->
    <!-- <div class="w-[25%] mx-auto mt-3 mb-8 h-[10px] rounded-full bg-gray-300"></div>

    <p class="px-6 font-semibold">Pengajuan </p>
    <div class="w-full h-full grid grid-cols-12 gap-y-10  mt-4 justify-items-center ">
        <div class="col-span-4 grid place-items-center gap-y-2">
            <a href="/panel/pengajuan/lembur">

                <div class="w-[70px] rounded-md bg-blue-400 h-[70px] grid place-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="#fff" d="M10.75 8c-.41 0-.75.34-.75.75v4.69c0 .35.18.67.47.85l3.64 2.24a.713.713 0 1 0 .74-1.22L11.5 13.3V8.75c0-.41-.34-.75-.75-.75" />
                        <path fill="#fff" d="M17.92 12A6.957 6.957 0 0 1 11 20c-3.9 0-7-3.1-7-7s3.1-7 7-7c.7 0 1.37.1 2 .29V4.23c-.64-.15-1.31-.23-2-.23c-5 0-9 4-9 9s4 9 9 9a8.963 8.963 0 0 0 8.94-10z" />
                        <path fill="#fff" d="M22 5h-2V3c0-.55-.45-1-1-1s-1 .45-1 1v2h-2c-.55 0-1 .45-1 1s.45 1 1 1h2v2c0 .55.45 1 1 1s1-.45 1-1V7h2c.55 0 1-.45 1-1s-.45-1-1-1" />
                    </svg>
                </div>
                <p>Lembur</p>
            </a>
        </div>
        <div class="col-span-4">
            <a class=" grid place-items-center gap-y-2" href="/panel/pengajuan/cuti">
                <div class="w-[70px] rounded-md bg-blue-400 h-[70px] grid place-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                        <path fill="#fff" d="M4 19h16v2H4zM20 3H4v10c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-3h2a2 2 0 0 0 2-2V5c0-1.11-.89-2-2-2m-4 10c0 1.1-.9 2-2 2H8c-1.1 0-2-.9-2-2V5h10zm4-5h-2V5h2z" />
                    </svg>
                </div>
                <p>Cuti</p>
            </a>
        </div>
        <div class="col-span-4 ">
            <a href="/panel/pengajuan/dinas" class=" grid place-items-center gap-y-2">
                <div class="w-[70px] rounded-md bg-blue-400 h-[70px] grid place-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                        <path fill="#fff" d="M18.92 5.01C18.72 4.42 18.16 4 17.5 4h-11c-.66 0-1.21.42-1.42 1.01L3 11v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8zM6.85 6h10.29l1.04 3H5.81zM19 16H5v-4.66l.12-.34h13.77l.11.34z" />
                        <circle cx="7.5" cy="13.5" r="1.5" fill="#fff" />
                        <circle cx="16.5" cy="13.5" r="1.5" fill="#fff" />
                    </svg>
                </div>
                <p>Dinas</p>
            </a>
        </div> -->
    <!-- <div class="w-[70px] rounded-full bg-teal-100 h-[70px] col-span-4"></div> -->
    <!-- <div class="w-[70px] rounded-full bg-teal-100 h-[70px] col-span-4"></div> -->
    <!-- <div class="w-[70px] rounded-full bg-teal-100 h-[70px] col-span-4"></div> -->
    <!-- </div> -->
    <!-- </div> -->

</section>





<div class="fixed bottom-0 left-0 right-0 w-full">
    <?= $this->render('@backend/views/components/_footer'); ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper(".mySwiper", {
        spaceBetween: 30,
        loop: true,
        centeredSlides: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            // type: "fraction",
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
</script>