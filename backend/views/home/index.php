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

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

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
        background: #fff;
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


<section class="overflow-x-hidden min-h-[80dvh] ">
    <div class=" ">
        <div class="h-[400px] lg:h-[350px]">
            <div class="bg-[#faf9f9] lg:bg-white h-[200px] lg:px-5">

                <div class="grid grid-cols-12 pt-5">

                    <section class="col-span-12 lg:col-span-6  ">
                        <div class="px-5 justify-between lg:items-start flex lg:flex-col ">
                            <div class="hidden lg:block ">
                                <div class=" flex justify-between items-center space-x-16">
                                    <div class=" flex justify-start align-center ">
                                        <h1 id="clock" class="text-6xl md:text-8xl font-bold flex justify-end items-end">
                                            <span id="hours">00</span>:<span id="minutes">00</span><span class="text-[22px] lg:text-[50px]">:</span><span class="text-[22px] lg:text-[50px]" id="seconds">00</span>
                                        </h1>
                                    </div>
                                    <p id="date" class="font-medium text-md  text-gray-500 mt-2"></p>
                                </div>

                            </div>
                            <div class="mt-3">

                                <p class="uppercase text-xl lg:text-3xl font-bold " data-aos="fade-down" data-aos-duration="1000"><?= $karyawan->nama ?></p>
                                <p class="text-gray-500 text-sm  uppercase" data-aos="fade-down" data-aos-duration="1000"><?= $karyawan->kode_karyawan ?></p>
                            </div>
                            <div class="flex gap-3 items-center">
                                <?= Html::a('
                                <div class="lg:hidden w-11 h-11 rounded-full border grid place-items-center   border-gray-300 relative z-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                                <path fill="#9e9e9e" d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.25 2.52l.77-1.28l-3.52-2.09V8z" />
                                </svg>
                                </div>
              
                                 ', ['/home/view', 'id_user' => Yii::$app->user->identity->id]) ?>
                            </div>
                        </div>

                    </section>


                    <section class="col-span-12 lg:col-span-6" data-aos="fade-down" data-aos-duration="1000">
                        <div class="  relative  justify-center gap-5 content-start p-5 lg:p-0">
                            <div class="w-full h-full bg-white  overflow-hidden rounded-[20px]">
                                <div>
                                    <div class="">
                                        <a href="/panel/home/pengumuman">
                                            <div class="grid grid-cols-12  w-full h-full">
                                                <div class="col-span-4 h-full ">
                                                    <img src="<?= Yii::getAlias('@root') . "/images/icons/toa.jpg" ?>" class="object-cover xl:object-left-bottom scale-100 xl:scale-75 xl:max-h-[400px] xl:-translate-y-10" alt="toa">

                                                </div>
                                                <div class="col-span-8 relative   w-full flex flex-col justify-start items-start h-full p-3">
                                                    <h1 class="text-xl font-bold text-black"> Informasi Pengumuman</h1>
                                                    <div class="w-full mt-4">
                                                        <p class="pb-2 text-sm text-gray-500">Lihat informasi pengumuman terbaru dari admin</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </section>
                </div>

            </div>
        </div>
    </div>



    <div data-aos="fade-up" data-aos-duration="800" class="w-[70px] h-[5px] bg-[#ede8fe]     -mt-24 lg:-mt-10 mx-auto"></div>


    <div class="grid grid-cols-12  mb-20  gap-2 w-full mt-5 min-w-screen   px-5 relative">

        <div class="w-[120px]  aspect-square bg-[#fff] rounded-full absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2  overflow-hidden     ">

            <div class=" flex justify-center items-center flex-col bg-[#ffc27c] w-full h-full rounded-[50px] rotate-180" data-aos="zoom-in" data-aos-delay="1000" style="z-index: -99999; position: relative;" data-aos-duration="500">
            </div>

        </div>


        <a href="/panel/pengajuan/wfh" class="  col-span-6   " data-aos-duration="1000" data-aos="fade-down-right">
            <div class=" flex justify-center items-center flex-col bg-[#ede8fe] py-5 max-h-[200px] relative z-50 rounded-[50px] ">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/wfh.svg" alt="calendar" width="40px" height="40px">
                </div>
                <p class=" font-semibold text-black/80 mt-5 mb-1 flex flex-col justify-center items-center"><span>Pengajuan</span> <span>WFH</span></p>
            </div>
        </a>
        <a href="/panel/pengajuan/cuti" class="  col-span-6   " data-aos-duration="1000" data-aos="fade-down-left">
            <div class=" flex justify-center items-center flex-col bg-[#f2fee8] py-5 max-h-[200px] relative z-50 rounded-[50px] ">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/calendar.png" alt="calendar" width="40px" height="40px">
                </div>
                <p class=" font-semibold text-black/80 mt-5 mb-1 flex flex-col justify-center items-center"><span>Pengajuan</span> <span>Cuti</span></p>
            </div>
        </a>
        <a href="/panel/pengajuan/lembur" class="  col-span-6  " data-aos-duration="1000" data-aos="fade-up-right">
            <div class=" flex-col col-span-6 bg-[#ffe3e3] py-5 max-h-[200px] relative z-50 rounded-[50px] flex justify-center items-center">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/alarm.png" alt="calendar" width="30px" height="30px">
                </div>
                <p class="font-semibold text-black/80  mt-5 mb-1 flex flex-col justify-center items-center"><span>Pengajuan</span> <span>Lembur</span></p>
            </div>
        </a>
        <a href="/panel/pengajuan/dinas" class="  col-span-6  " data-aos-duration="1000" data-aos="fade-up-left">
            <div class=" flex-col col-span-6 bg-[#ebeefd] py-5 max-h-[200px] relative z-50 rounded-[50px] flex justify-center items-center">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/building.png" alt="building" width="30px" height="30px">
                </div>
                <p class="font-semibold text-black/80  mt-5 mb-1 flex flex-col justify-center items-center" style="font-size: 15px !important;"><span>Pengajuan</span> <span>Dinas Luar</span></p>
            </div>
        </a>
    </div>
</section>






<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
</script>

<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        document.getElementById('hours').textContent = hours;
        document.getElementById('minutes').textContent = minutes;
        document.getElementById('seconds').textContent = seconds;
    }

    setInterval(updateClock, 1000);
    updateClock(); // Call it once to avoid delay


    function updateDate() {
        const now = new Date();
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const dayName = days[now.getDay()];
        const date = now.getDate();
        const monthName = months[now.getMonth()];
        const year = now.getFullYear();

        document.getElementById('date').textContent = `${dayName}, ${date} ${monthName} ${year}`;
    }
    updateDate();
</script>

<!-- Initialize Swiper -->