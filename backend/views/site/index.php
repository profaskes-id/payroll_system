<?php

use backend\models\IzinPulangCepat;
use backend\models\PengajuanCuti;
use backend\models\PengajuanDinas;
use backend\models\PengajuanLembur;
use backend\models\Tanggal;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Dashboard';
//   <?php
$tanggal = new Tanggal();
$result = $tanggal->getIndonesiaFormatLong(date('l, d F Y'));

?>

<link href="<?= Yii::getAlias('@root') . '/css/tailwind_output.css' ?>" rel="stylesheet">




<section class="w-full ">
    <div class="w-full pb-6 bg-gray-50">
        <div class="container mx-auto">
            <!-- Enhanced Card Container -->
            <div class="relative p-4 overflow-hidden transition-all duration-300 bg-white shadow-lg rounded-xl hover:shadow-xl min-h-200">
                <!-- Background Image with Overlay -->
                <img src="<?= Yii::getAlias('@root') ?>/images/bg.jpg" alt="background" class="absolute inset-0 object-cover w-full h-full">
                <!-- <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/20"></div> -->

                <!-- Content -->
                <div class="relative z-10 flex flex-col h-full">
                    <!-- Header Section -->
                    <div class="flex items-start justify-between">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Sales Distribution</h2>
                            <p class="text-sm text-gray-600">Monthly revenue breakdown by category</p>
                        </div>
                        <div class="flex items-center px-2 py-1 ml-2 rounded-full">
                            <a href="/panel/admin-notification" class="relative">
                                <i class="text-[22px]  fa  fa-regular fa-bell"></i> <!-- Ukuran ikon diubah menjadi lebih kecil -->
                                <?php if ($is_ada_notif > 0): ?>
                                    <span class="absolute top-0 right-0 block w-2.5 h-2.5 bg-rose-500 rounded-full"></span>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>



                    <!-- Stats Grid -->
                    <div class="grid grid-cols-4 gap-5 ">
                        <!-- Stat 1 -->
                        <div class="p-5 transition-shadow bg-white/90 backdrop-blur-sm rounded-xl hover:shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-blue-600"><?= $TotalKaryawan ?> Orang</span>
                                <span class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">40%</span>
                            </div>
                            <p class="mt-2 text-sm font-medium text-gray-700">By Vivement</p>
                            <p class="text-xs text-gray-500">Total Karyawan</p>
                        </div>

                        <!-- Stat 2 -->
                        <div class="p-5 transition-shadow bg-white/90 backdrop-blur-sm rounded-xl hover:shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-green-600"><?= $TotalData ?> Orang</span>
                                <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">23%</span>
                            </div>
                            <p class="mt-2 text-sm font-medium text-gray-700">By Finland</p>
                            <p class="text-xs text-gray-500">Hadir Hari Ini</p>
                        </div>

                        <!-- Stat 3 -->
                        <div class="p-5 transition-shadow bg-white/90 backdrop-blur-sm rounded-xl hover:shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-yellow-600"><?= $TotalDataBelum ?> Orang</span>
                                <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">20%</span>
                            </div>
                            <p class="mt-2 text-sm font-medium text-gray-700">By Finland</p>
                            <p class="text-xs text-gray-500">Tanpa Keterangan</p>
                        </div>

                        <!-- Stat 4 -->
                        <div class="p-5 transition-shadow bg-white/90 backdrop-blur-sm rounded-xl hover:shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-red-600"><?= $TotalIzin ?> Orang</span>
                                <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">15%</span>
                            </div>
                            <p class="mt-2 text-sm font-medium text-gray-700">By Japan</p>
                            <p class="text-xs text-gray-500">Izin Berhalangan Hadir</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="w-full">
    <div class="w-full p-6 bg-white shadow-lg rounded-xl">
        <!-- Header with better hierarchy -->
        <div class="flex flex-col mb-6 space-y-2 md:flex-row md:items-center md:justify-between md:space-y-0">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Statistik  Dan Pengajuan Karyawan</h2>
                <p class="text-sm text-gray-500">Data Statistik Dan Ringkasan pengajuan bulan ini</p>
            </div>
                        <h3 class="text-sm font-semibold tracking-wider text-gray-700 uppercase">Detail Pengajuan</h3>

        </div>

        <!-- Two-column grid layout with optimized space -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- First Grid - Chart Area (2/3 width) -->
            <div class="lg:col-span-2">
                <!-- Placeholder for Chart -->
                <div class="flex items-center justify-center h-full p-8 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="mt-4 font-medium text-gray-600">Area chart akan ditampilkan di sini</p>
                    </div>
                </div>
            </div>

            <!-- Second Grid - Request List (1/3 width) -->
            <div class="space-y-3">
                <!-- List Header -->


                <!-- Request Items -->
                <div class="space-y-3">
                    <!-- WFH -->
                    <div class="p-3 transition-all bg-white border border-gray-100 rounded-xl hover:shadow-xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 rounded-full bg-blue-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-800">WFH</h3>
                                    <p class="text-xs text-gray-500">Kerja dari rumah</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-0.5 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full"><?= $pengajuanWFH ?></span>
                        </div>
                    </div>

                    <!-- Pulang Cepat -->
                    <div class="p-3 transition-all bg-white border border-gray-100 rounded-xl hover:shadow-xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 rounded-full bg-green-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-800">Pulang Cepat</h3>
                                    <p class="text-xs text-gray-500">Pulang lebih awal</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-0.5 text-xs font-semibold text-green-800 bg-green-100 rounded-full"><?= $pengajuanPulangCepat ?></span>
                        </div>
                    </div>

                    <!-- Cuti -->
                    <div class="p-3 transition-all bg-white border border-gray-100 rounded-xl hover:shadow-xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 rounded-full bg-yellow-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-800">Cuti</h3>
                                    <p class="text-xs text-gray-500">Hari libur</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-0.5 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full"><?= $pengajuanCuti ?></span>
                        </div>
                    </div>

                    <!-- Lembur -->
                    <div class="p-3 transition-all bg-white border border-gray-100 rounded-xl hover:shadow-xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 rounded-full bg-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-800">Lembur</h3>
                                    <p class="text-xs text-gray-500">Kerja extra</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-0.5 text-xs font-semibold text-red-800 bg-red-100 rounded-full"><?= $pengajuanLembur ?></span>
                        </div>
                    </div>

                    <!-- Dinas -->
                    <div class="p-3 transition-all bg-white border border-gray-100 rounded-xl hover:shadow-xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 rounded-full bg-purple-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-800">Dinas</h3>
                                    <p class="text-xs text-gray-500">Perjalanan kerja</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-0.5 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full"><?= $pengajuanDinas ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        const date = new Date();
        const month = date.getMonth();
        const year = date.getFullYear();
        const daysInMonth = getDaysInMonth(month, year);
        const firstDayOfWeek = getDayOfWeek(1, month, year);

        $('#month-year').text(`${getMonthName(month)} ${year}`);

        // Create thead section
        const $thead = $('<thead>');
        const $theadRow = $('<tr>');
        for (let i = 0; i < 7; i++) {
            const $th = $('<th>');
            $th.text(getDayName(i));
            $th.appendTo($theadRow);
        }
        $theadRow.appendTo($thead);
        $thead.appendTo($('#calendar'));

        // Create tbody section
        let dayCount = 0;
        let $row = $('<tr>'); // Create a new table row

        // Tambahkan sel kosong untuk hari-hari sebelum tanggal 1
        for (let i = 0; i < firstDayOfWeek; i++) {
            const $cell = $('<td>');
            $cell.appendTo($row);
            dayCount++;
        }

        for (let i = 1; i <= daysInMonth; i++) {
            const day = i;
            const isToday = day === date.getDate();

            const $cell = $('<td>');
            $cell.text(day);
            if (isToday) {
                $cell.addClass('today');
                $cell.html(`<span class="bg-[#f97316] rounded-full  flex px-2 -translate-x-1 p-1">${day}</span>`); // Add purple circle around the current day
            }

            $cell.appendTo($row); // Append cell to the current row
            dayCount++;

            if (dayCount === 7) {
                $row.appendTo($('#calendar-body')); // Append row to the table body
                $row = $('<tr>'); // Reset the row
                dayCount = 0;
            }
        }

        if (dayCount > 0) {
            $row.appendTo($('#calendar-body')); // Append the last row
        }

        function getDaysInMonth(month, year) {
            return new Date(year, month + 1, 0).getDate();
        }

        function getMonthName(month) {
            const months = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ];
            return months[month];
        }

        function getDayOfWeek(day, month, year) {
            const date = new Date(year, month, day);
            return date.getDay();
        }

        function getDayName(dayOfWeek) {
            const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            return days[dayOfWeek];
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    let dates = <?= $datesAsJson ?>;

    let tanggal = [];
    let totalData = [];
    for (const key in dates) {
        tanggal.push(key)
        totalData.push({
            x: key,
            y: dates[key]
        })
    }

    let finalData = [];

    totalData.forEach(element => {
        let data = {
            x: element.x,
            y: 0
        }
        if (element.y == null)
            data.y = 0;
        else {
            data.y = Object.keys(element.y).length
        }
        finalData.push(data)
    })



    console.info(finalData);

    var options = {
        chart: {
            type: 'bar'
        },
        xaxis: {
            categories: [...tanggal]
        },
        series: [{
            'name': 'Jumlah',
            data: [...finalData]

        }],

    }

    var chart = new ApexCharts(document.querySelector("#chart"), options);

    chart.render();
</script>

<footer>
    <p class="py-2 mt-5 text-center table-container">Copyright 2024 © PT Profaskes Softech Indonesia. All Rights Reserved</p>
</footer>