<?php

use backend\assets\AppAsset;
use backend\models\Absensi;
use backend\models\Karyawan;
use backend\models\SettinganUmum;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */


$this->title = 'Absensis';

$this->params['breadcrumbs'][] = $this->title;

?>


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


<section class="overflow-x-hidden ">
    <div class="">
        <div class="h-[400px]  md:h-[200px] lg:h-[300px]">
            <div class="bg-[#faf9f9] lg:bg-white h-[200px] lg:px-5">

                <div class="grid grid-cols-12 pt-5">

                    <section class="col-span-12 ">
                        <div class="flex justify-between px-5 lg:items-start lg:flex-col ">
                            <div class="hidden w-full lg:block">
                                <div class="flex items-center justify-between space-x-16 ">
                                    <div class="flex justify-between w-full align-center">
                                        <h1 id="clock" class="flex items-end justify-end text-6xl font-bold md:text-8xl">
                                            <span id="hours">00</span>:<span id="minutes">00</span><span class="text-[22px] lg:text-[50px]">:</span><span class="text-[22px] lg:text-[50px]" id="seconds">00</span>
                                        </h1>
                                    </div>
                                    <p id="date" class="mt-2 font-medium text-gray-500 text-md"></p>
                                </div>

                            </div>
                            <div class="mt-3">

                                <p class="text-xl font-bold uppercase lg:text-3xl ">
                                    <?= htmlspecialchars($karyawan->nama, ENT_QUOTES, 'UTF-8') ?>
                                </p>
                                <p class="text-sm text-gray-500 uppercase">
                                    <?= htmlspecialchars($karyawan->kode_karyawan, ENT_QUOTES, 'UTF-8') ?>
                                </p>

                            </div>
                            <div class="relative flex items-center gap-3">
                                <?= Html::a('
                                <div class="relative z-50 grid border border-gray-300 rounded-full lg:hidden w-11 h-11 place-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                                <path fill="#9e9e9e" d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.25 2.52l.77-1.28l-3.52-2.09V8z" />
                                </svg>
                                </div>
              
                                 ', ['/home/view', 'id_user' => Yii::$app->user->identity->id]) ?>


                                <?php if ($is_ada_notif > 0): ?>
                                    <div class="absolute right-0 z-10 w-4 h-4 bg-red-500 rounded-full md:hidden bottom-3 ">

                                    </div>
                                    <?= Html::a('
                                <div class="relative z-50 grid border border-gray-300 rounded-full lg:hidden w-11 h-11 place-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="#9e9e9e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.268 21a2 2 0 0 0 3.464 0m-10.47-5.674A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/></svg>
                                </div>
                                
                                ', ['/home/inbox', 'id_user' => Yii::$app->user->identity->id]) ?>
                                <?php else: ?>
                                    <?= Html::a('
                                <div class="relative z-50 grid border border-gray-300 rounded-full lg:hidden w-11 h-11 place-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="#9e9e9e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.268 21a2 2 0 0 0 3.464 0m-10.47-5.674A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/></svg>
                                </div>
                                
                                ', ['/home/inbox', 'id_user' => Yii::$app->user->identity->id]) ?>
                                <?php endif ?>

                            </div>
                        </div>

                    </section>


                    <section class="col-span-12 my-10">
                        <div class="relative content-start justify-center gap-5 p-5 lg:p-0">
                            <div class="w-full h-full bg-white  overflow-hidden rounded-[20px]">
                                <div>
                                    <div class="">
                                        <a href="/panel/home/pengumuman">
                                            <div class="grid w-full h-full md:h-[150px] grid-cols-12">
                                                <div class="h-full col-span-4 ">
                                                    <img src="<?= Yii::getAlias('@root') . "/images/icons/toa.jpg" ?>" class="object-cover md:h-[150px]" alt="toa">

                                                </div>
                                                <div class="relative flex flex-col items-start justify-start w-full h-full col-span-8 p-3">
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

    <div data-aos="fade-up" data-aos-duration="800" class="w-[70px] h-[5px] bg-slate-900 rounded-xl   -mt-24 md:mt-10 mx-auto"></div>



    <?php if ($jamKerjaToday && $jamKerjaToday['is_shift'] != 0): ?>

        <?php
        $setting = SettinganUmum::find()
            ->where(['kode_setting' => Yii::$app->params['change_shift']])
            ->select('nilai_setting')
            ->scalar(); // ambil langsung nilai_setting-nya
        ?>

        <?php if ($dataShift === null): ?>
            <div class="mt-10 font-semibold text-center text-red-600">
                Data shift Anda belum ada untuk hari ini.
            </div>
        <?php else: ?>
            <?php if ($manual_shift == 1): ?>
                <div class="flex justify-end w-[90%] mx-auto rounded-full overflow-hidden mt-10 relative">
                    <a class="block w-full p-3 overflow-hidden text-center text-white bg-slate-900"
                        href="/panel/home/<?= $setting == 1 ? 'change-shift' : 'pengajuan-shift' ?>?id_karyawan=<?= Yii::$app->user->identity->id_karyawan ?>">
                        <div class="flex flex-row items-center justify-around overflow-hidden">
                            <p class="font-semibold"><?= $dataShift['nama_shift'] ?? '-' ?></p>
                            <p class="font-semibold"><?= $dataShift['jam_masuk'] ?? '-' ?></p>
                            <p class="font-semibold"><?= $dataShift['jam_keluar'] ?? '-' ?></p>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($manual_shift == 1): ?>
            <p class="relative pt-2 text-center">
                <a href="/panel/home/lihat-shift?id_karyawan=<?= Yii::$app->user->identity->id_karyawan ?>" class="mt-4 underline capitalize text-primary underline-offset-2">Show More</a>
            </p>
        <?php endif; ?>
    <?php endif; ?>



    <br>


    <div class="relative grid w-full grid-cols-12 gap-2 px-5 mt-5 mb-20 min-w-screen">

        <div class="w-[120px]   aspect-square bg-slate-900 rounded-full absolute left-1/2 top-1/3 -translate-x-1/2 -translate-y-1/2  overflow-hidden    block md:hidden xl:block  "></div>
        <div class="w-[120px]   aspect-square bg-slate-900 rounded-full absolute left-1/2 top-2/3 -translate-x-1/2 -translate-y-1/2  overflow-hidden    block md:hidden xl:block "></div>

        <a href="/panel/pengajuan/lembur" class="col-span-6 " data-aos-duration="1000" data-aos="fade-up-right">
            <div class=" flex-col col-span-6 bg-blue-500 py-5 w-[45vw] md:w-[38dvw] h-[22vh] relative z-50 rounded-[50px] flex justify-center items-center">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/alarm.png" alt="calendar" width="30px" height="30px">
                </div>
                <p class="flex flex-col items-center justify-center mt-5 mb-1 font-semibold text-white"><span>Pengajuan</span> <span>Lembur</span></p>
            </div>
        </a>
        <a href="/panel/pengajuan/dinas" class="col-span-6 " data-aos-duration="1000" data-aos="fade-up-left">
            <div class=" flex-col col-span-6 bg-slate-900 py-5 w-[45vw] md:w-[38dvw] h-[22vh] relative z-50 rounded-[50px] flex justify-center items-center">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/building.png" alt="building" width="30px" height="30px">
                </div>
                <p class="flex flex-col items-center justify-center mt-5 mb-1 font-semibold text-white" style="font-size: 15px !important;"><span>Pengajuan</span> <span>Dinas Luar</span></p>
            </div>
        </a>


        <a href="/panel/pengajuan/wfh" class="col-span-6 " data-aos-duration="1000" data-aos="fade-down-right">
            <div class=" flex justify-center items-center flex-col bg-slate-900 py-5 w-[45vw] md:w-[38dvw] h-[22vh] relative z-50 rounded-[50px] ">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/wfh.svg" alt="calendar" width="40px" height="40px">
                </div>
                <p class="flex flex-col items-center justify-center mt-5 mb-1 font-semibold text-white"><span>Pengajuan</span> <span>WFH</span></p>
            </div>
        </a>
        <a href="/panel/pengajuan/cuti" class="col-span-6 " data-aos-duration="1000" data-aos="fade-down-left">
            <div class=" flex justify-center items-center flex-col bg-blue-500 py-5 w-[45vw] md:w-[38dvw] h-[22vh] relative z-50 rounded-[50px] ">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/calendar.png" alt="calendar" width="40px" height="40px">
                </div>
                <p class="flex flex-col items-center justify-center mt-5 mb-1 font-semibold text-white"><span>Pengajuan</span> <span>Cuti</span></p>
            </div>
        </a>

        <a href="/panel/absensi-tertinggal" class="col-span-6 " data-aos-duration="1000" data-aos="fade-down-left">
            <div class=" flex justify-center items-center flex-col bg-blue-500 py-5 w-[40vw] md:w-[38dvw]  h-[22vh] relative z-50 rounded-[50px] ">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/timelate.png" alt="calendar" width="40px" height="40px">
                </div>
                <p class="flex flex-col items-center justify-center mt-5 mb-1 font-semibold text-white"><span>Pengajuan</span> <span>Absensi</span></p>
            </div>
        </a>
        <a href="/panel/pengajuan/tugas-luar" class="col-span-6 " data-aos-duration="1000" data-aos="fade-down-right">
            <div class=" flex justify-center items-center flex-col bg-slate-900 py-5 w-[40vw] md:w-[38dvw] h-[23vh] relative z-50 rounded-[50px] ">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/todo.png" alt="calendar" width="38px" height="38px">
                </div>
                <p class="flex flex-col items-center justify-center mt-5 mb-1 font-semibold text-white"><span>Pengajuan</span> <span>Tugas Luar</span></p>
            </div>
        </a>
    </div>

    <?php
    $karyawan =  Karyawan::find()->select('is_atasan')->where(['id_karyawan' => Yii::$app->user->identity->id_karyawan])->asArray()->one();

    if ($karyawan['is_atasan'] == 1) : ?>



        <h1 class="font-bold ms-5">Pengajuan Karyawan</h1>
        <div class="relative grid w-full grid-cols-12 gap-2 px-5 mt-5 mb-20 min-w-screen">

            <div class="w-[120px]  aspect-square bg-[#fff] rounded-full absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2  overflow-hidden     ">

                <div class=" flex justify-center items-center flex-col bg-[#ffc27c] w-full h-full rounded-[50px] rotate-180" data-aos="zoom-in" data-aos-delay="1000" style="z-index: -99999; position: relative;" data-aos-duration="500">
                </div>

            </div>


            <a href="/panel/tanggapan/wfh" class="col-span-6 " data-aos-duration="1000" data-aos="fade-down-right">
                <div class=" flex justify-center items-center flex-col bg-[#ede8fe] py-5 w-[45vw] md:w-[38dvw] h-[22vh] relative z-50 rounded-[50px] ">
                    <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                        <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/wfh.svg" alt="calendar" width="40px" height="40px">
                    </div>
                    <p class="flex flex-col items-center justify-center mt-5 mb-1 font-semibold text-black/80"><span>Tanggapan</span> <span>WFH</span></p>
                </div>
            </a>
            <a href="/panel/tanggapan/cuti" class="col-span-6 " data-aos-duration="1000" data-aos="fade-down-left">
                <div class=" flex justify-center items-center flex-col bg-[#f2fee8] py-5 w-[45vw] md:w-[38dvw] h-[22vh] relative z-50 rounded-[50px] ">
                    <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                        <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/calendar.png" alt="calendar" width="40px" height="40px">
                    </div>
                    <p class="flex flex-col items-center justify-center mt-5 mb-1 font-semibold text-black/80"><span>Tanggapan</span> <span>Cuti</span></p>
                </div>
            </a>
            <a href="/panel/tanggapan/lembur" class="col-span-6 " data-aos-duration="1000" data-aos="fade-up-right">
                <div class=" flex-col col-span-6 bg-[#ffe3e3] py-5 w-[45vw] md:w-[38dvw] h-[22vh] relative z-50 rounded-[50px] flex justify-center items-center">
                    <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                        <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/alarm.png" alt="calendar" width="30px" height="30px">
                    </div>
                    <p class="flex flex-col items-center justify-center mt-5 mb-1 font-semibold text-black/80"><span>Tanggapan</span> <span>Lembur</span></p>
                </div>
            </a>
            <a href="/panel/tanggapan/dinas" class="col-span-6 " data-aos-duration="1000" data-aos="fade-up-left">
                <div class=" flex-col col-span-6 bg-[#ebeefd] py-5 w-[45vw] md:w-[38dvw] h-[22vh] relative z-50 rounded-[50px] flex justify-center items-center">
                    <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                        <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/building.png" alt="building" width="30px" height="30px">
                    </div>
                    <p class="flex flex-col items-center justify-center mt-5 mb-1 font-semibold text-black/80" style="font-size: 15px !important;"><span>Tanggapan</span> <span>Dinas </span></p>
                </div>
            </a>
        </div>
    <?php endif ?>

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