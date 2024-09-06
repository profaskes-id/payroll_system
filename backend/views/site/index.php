<?php

use yii\helpers\Html;

$this->title = 'Hello, ' . Yii::$app->user->identity->username ?? 'admin';

?>
<link href="<?= Yii::getAlias('@root') . '/css/tailwind_output.css' ?>" rel="stylesheet">
<style>
    h2 {
        color: #252525;
        font-size: 30px;
        text-transform: capitalize;
        margin-bottom: 20px;
    }

    @media screen and (min-width: 768px) {
        h2 {
            font-size: 35px;
        }
    }
</style>


<div class="container-fluid">
    <div class="flex justify-between items-center mb-5">
        <p class="text-gray-500 font-medium ">Lihar Rekapan Absnensi Hari Ini</p>
        <div class="flex space-x-2 items-center -mt-10">

            <?php
            $hari = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];

            $bulan = [
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember'
            ];
            $tanggal = date('l, d F Y');
            $tanggal = str_replace(array_keys($hari), array_values($hari), $tanggal);
            $tanggal = str_replace(array_keys($bulan), array_values($bulan), $tanggal);
            echo "<p>$tanggal</p>";
            ?>


            <div class="grid place-items-center w-12 h-12 bg-gray-200 rounded-full ">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                    <g fill="none">
                        <path stroke="black" stroke-width="1.5" d="M2 12c0-3.771 0-5.657 1.172-6.828S6.229 4 10 4h4c3.771 0 5.657 0 6.828 1.172S22 8.229 22 12v2c0 3.771 0 5.657-1.172 6.828S17.771 22 14 22h-4c-3.771 0-5.657 0-6.828-1.172S2 17.771 2 14z" />
                        <path stroke="black" stroke-linecap="round" stroke-width="1.5" d="M7 4V2.5M17 4V2.5M2.5 9h19" opacity="0.5" />
                        <path fill="black" d="M18 17a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0" />
                    </g>
                </svg>
            </div>

        </div>
    </div>

    <div class='table-container'>
        <h1 class="font-bold text-2xl">Rekap Data</h1>
        <div class="grid grid-cols-3 plce-items-center   divide-x-2  gap-5 my-3 py-4 border-y-2 border-gray-200/70">
            <div class="flex space-x-5 items-center w-full  ">
                <div class="w-20 h-20 bg-sky-200 rounded-lg grid place-items-center">
                    <?= Html::img('@root/images/icons/users.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                </div>
                <div class="flex flex-col items-start justify-center">
                    <p class="font-bold text-2xl"><?= $TotalKaryawan ?> <span class="font-normal text-gray-500 text-base">Orang</span></p>
                    <p class="text-sm text-gray-500">Total Karyawan</p>
                </div>
            </div>
            <div class="flex space-x-5 items-center w-full pl-20 ">
                <div class="w-20 h-20 bg-green-200 rounded-lg grid place-items-center">
                    <?= Html::img('@root/images/icons/hadir.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                </div>
                <div class="flex flex-col items-start justify-center">
                    <p class="font-bold text-2xl"><?= $TotalData ?> <span class="font-normal text-gray-500 text-base">Orang</span></p>
                    <p class="text-sm text-gray-500">Hadir Hari Ini</p>
                </div>
            </div>
            <div class="flex space-x-5 items-center w-full pl-20 ">
                <div class="w-20 h-20 bg-rose-200 rounded-lg grid place-items-center">
                    <?= Html::img('@root/images/icons/tanpa-keterangan.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                </div>
                <div class="flex flex-col items-start justify-center">
                    <p class="font-bold text-2xl"><?= $TotalDataBelum ?> <span class="font-normal text-gray-500 text-base">Orang</span></p>
                    <p class="text-sm text-gray-500">Tanpa Keterangan</p>
                </div>
            </div>
            <div class="flex space-x-5 items-center w-full  ">
                <div class="w-20 h-20 bg-yellow-200 rounded-lg grid place-items-center">
                    <?= Html::img('@root/images/icons/izin.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                </div>
                <div class="flex flex-col items-start justify-center">
                    <p class="font-bold text-2xl"><?= $TotalIzin ?> <span class="font-normal text-gray-500 text-base">Orang</span></p>
                    <p class="text-sm text-gray-500">Izin Berhalangan Hadir</p>
                </div>
            </div>
            <div class="flex space-x-5 items-center w-full  pl-20 ">
                <div class="w-20 h-20 bg-orange-200 rounded-lg grid place-items-center">
                    <?= Html::img('@root/images/icons/toa.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                </div>
                <div class="flex flex-col items-start justify-center">
                    <p class="font-bold text-2xl"><?= $totalPengumuman ?> <span class="font-normal text-gray-500 text-base">Item</span></p>
                    <p class="text-sm text-gray-500">Total Pengumuman</p>
                </div>
            </div>
            <div class="flex space-x-5 items-center w-full  pl-20 ">
                <div class="w-20 h-20 bg-teal-200 rounded-lg grid place-items-center">
                    <?= Html::img('@root/images/icons/pengajuan.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                </div>
                <div class="flex flex-col items-start justify-center">
                    <p class="text-sm font-bold">Pengajuan</p>
                    <p class="text-sm ">Lembur : <?= $pengajuanLembur ?></p>
                    <p class="text-sm ">Cuti : <?= $pengajuanCuti ?></p>
                    <p class="text-sm ">Dinas : <?= $pengajuanDinas ?></p>
                </div>
            </div>

        </div>
    </div>

</div>