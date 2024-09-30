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
<?php
// Fungsi untuk memotong deskripsi dan menambahkan link
function getShortDescription($description, $wordLimit = 5)
{
    $words = explode(' ', $description);
    if (count($words) > $wordLimit) {
        $shortDescription = implode(' ', array_slice($words, 0, $wordLimit)) . '...';
    } else {
        $shortDescription = $description;
    }

    return $shortDescription;
}

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


<section class="overflow-x-hidden min-h-[80dvh]">
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

                                <p class="uppercase text-xl lg:text-3xl font-bold "><?= $karyawan->nama ?></p>
                                <p class="text-gray-500 text-sm  uppercase"><?= $karyawan->kode_karyawan ?></p>
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


                    <section class="col-span-12 lg:col-span-6">
                        <div class="  relative  justify-center gap-5 content-start p-5 lg:p-0">
                            <div class="swiper mySwiper border rounded-xl overflow-hidden">
                                <div class="swiper-wrapper">
                                    <?php foreach ($pengumuman as $key => $value) : ?>
                                        <div class="swiper-slide ">
                                            <a href="/panel/home/pengumuman?id_pengumuman=<?= $value['id_pengumuman'] ?>">
                                                <div class="grid grid-cols-12  w-full h-full">
                                                    <div class="col-span-4 h-full ">
                                                        <img src="<?= Yii::getAlias('@root') . "/images/icons/toa.jpg" ?>" class="object-cover xl:object-left-bottom scale-100 xl:scale-75 xl:max-h-[400px] xl:-translate-y-10" alt="toa">

                                                    </div>
                                                    <div class="col-span-8 relative   w-full flex flex-col justify-start items-start h-full p-3">
                                                        <h1 class="text-lg font-semibold text-black"><?= getShortDescription($value['judul']); ?></h1>
                                                        <div class="w-full mt-4">
                                                            <p class="pb-2 text-sm text-gray-500"><?= getShortDescription($value['deskripsi']); ?></p>
                                                            <p class=" text-gray-500 text-sm text-end absolute right-2 bottom-2.5 lg:top-40 lg:left-6"><?= date('d-M-Y', strtotime($value['dibuat_pada'])) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>


                        </div>
                    </section>
                </div>

            </div>
        </div>
    </div>


    <div class="w-[70px] h-[5px] bg-gray-300 rounded-full -mt-20 lg:-mt-20 mx-auto"></div>


    <div class="grid grid-cols-12 pb-20  gap-2 w-full mt-5 min-w-screen   px-5">

        <?php if ($absensi) : ?>
            <?php if ($absensi->jam_pulang != null): ?>
                <a href="/panel/home/view?id_user=<?= Yii::$app->user->identity->id ?>" class="  col-span-6   ">
                    <div class=" flex justify-center items-center flex-col bg-gray-800 py-5 max-h-[200px] rounded-[50px] ">
                        <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                            <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/touch.png" alt="touch" width="40px" height="40px">
                        </div>
                        <div class="flex flex-col justify-center items-center py-2.5 space-y-3">
                            <p class="text-white font-semibold text-sm"><span class="text-gray-300 text-sm">Jam Masuk : </span><?php echo date('H:i', strtotime($absensi->jam_masuk)) ?></p>
                            <p class="text-white font-semibold text-sm"><span class="text-gray-300 text-sm">Jam Pulang : </span><?php echo date('H:i', strtotime($absensi->jam_pulang)) ?></p>
                        </div>
                    </div>
                </a>
            <?php else : ?>
                <a href="/panel/home/absen-masuk" class="  col-span-6   ">
                    <div class=" flex justify-center items-center flex-col bg-[#ff7c7c] py-5 max-h-[200px] rounded-[50px] ">
                        <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                            <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/touch.png" alt="touch" width="40px" height="40px">
                        </div>
                        <div class="flex flex-col justify-center items-center py-2.5 space-y-3">
                            <p class="text-white font-semibold text-sm"><span class="text-gray-100 text-sm">Jam Masuk : </span><strong><?php echo date('H:i', strtotime($absensi->jam_masuk)) ?></strong></p>
                            <p class="text-white font-semibold text-sm"><span class="text-gray-100 text-sm">Jam Pulang : </span><strong>-</strong></p>
                        </div>
                    </div>
                </a>
            <?php endif; ?>
        <?php else : ?>
            <a href="/panel/home/absen-masuk" class="  col-span-6   ">
                <div class=" flex justify-center text-white items-center flex-col bg-[#fc6a03]/80 py-5 max-h-[200px] rounded-[50px] ">
                    <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                        <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/touch.png" alt="touch" width="40px" height="40px">
                    </div>
                    <div class="flex flex-col justify-center items-center py-1">
                        <p class="text-white font-semibold text-center py-2 text-[15px]">Segera isi Abensi anda Sekarang</p>
                    </div>
                </div>
            </a>
        <?php endif; ?>

        <a href="/panel/pengajuan/cuti" class="  col-span-6   ">
            <div class=" flex justify-center items-center flex-col bg-[#f2fee8] py-5 max-h-[200px] rounded-[50px] ">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/calendar.png" alt="calendar" width="40px" height="40px">
                </div>
                <p class=" font-semibold text-black/80 mt-5 mb-1 flex flex-col justify-center items-center"><span>Pengajuan</span> <span>Cuti</span></p>
            </div>
        </a>
        <a href="/panel/pengajuan/lembur" class="  col-span-6  ">
            <div class=" flex-col col-span-6 bg-[#e3f9ff] py-5 max-h-[200px] rounded-[50px] flex justify-center items-center">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/alarm.png" alt="calendar" width="30px" height="30px">
                </div>
                <p class="font-semibold text-black/80  mt-5 mb-1 flex flex-col justify-center items-center"><span>Pengajuan</span> <span>Lembur</span></p>
            </div>
        </a>
        <a href="/panel/pengajuan/dinas" class="  col-span-6  ">
            <div class=" flex-col col-span-6 bg-[#fdf6eb] py-5 max-h-[200px] rounded-[50px] flex justify-center items-center">
                <div class="w-[50px] h-[50px] bg-white rounded-full p-1.5 grid place-items-center">
                    <img src="<?php echo Yii::getAlias('@root') ?>/images/icons/building.png" alt="building" width="30px" height="30px">
                </div>
                <p class="font-semibold text-black/80  mt-5 mb-1 flex flex-col justify-center items-center" style="font-size: 15px !important;"><span>Pengajuan</span> <span>Dinas Luar</span></p>
            </div>
        </a>
    </div>
</section>






<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

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