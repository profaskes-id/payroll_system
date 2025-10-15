<?php

use backend\models\AtasanKaryawan;
use yii\helpers\Html;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="min-h-screen bg-gray-50 ">
    <!-- Header Section -->
    <header class="relative z-50 text-white shadow-lg bg-gradient-to-r from-blue-600 to-blue-800">
        <div class="container px-4 py-4 mx-auto md:px-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <!-- User Profile Section -->
                <div class="flex items-center justify-between md:justify-start">
                    <div class="flex items-center">
                        <!-- User Avatar -->
                        <div class="relative w-12 h-12 overflow-hidden bg-white rounded-full shadow-md">
                            <div class="absolute inset-0 flex items-center justify-center bg-blue-100">
                                <span class="text-xl font-bold text-blue-600 uppercase">
                                    <?= substr($karyawan->nama, 0, 1) ?>
                                </span>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="ml-4">
                            <h1 class="text-xl font-bold md:text-2xl"><?= Html::encode($karyawan->nama) ?></h1>
                            <p class="text-sm text-blue-100"><?= Html::encode($karyawan->kode_karyawan) ?></p>
                        </div>
                    </div>

                </div>

                <!-- Right Side Controls -->
                <div class="flex items-center justify-between mt-4 md:mt-0">
                    <!-- Time Display -->
                    <div class="flex items-center px-4 py-2 bg-blue-700 rounded-lg shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-center">
                            <div class="flex items-baseline">
                                <span id="hours" class="text-xl font-bold md:text-2xl">00</span>
                                <span class="text-xl font-bold md:text-2xl">:</span>
                                <span id="minutes" class="text-xl font-bold md:text-2xl">00</span>
                            </div>
                            <p id="date" class="text-xs text-blue-100"></p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center ml-4 space-x-2">
                        <!-- Notification Button -->
                        <div class="relative">
                            <?= Html::a(
                                '<div class="p-2 transition-colors duration-200 rounded-full hover:bg-blue-700">' .
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">' .
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />' .
                                    '</svg>' .
                                    ($is_ada_notif > 0 ? '<span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full"></span>' : '') .
                                    '</div>',
                                ['/home/inbox', 'id_user' => Yii::$app->user->identity->id]
                            ) ?>
                        </div>

                        <!-- Refresh Button -->
                        <?= Html::a(
                            '<div class="p-2 transition-colors duration-200 rounded-full hover:bg-blue-700">' .
                                '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">' .
                                '<path d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.25 2.52.77-1.28-3.52-2.09V8z" />' .
                                '</svg>' .
                                '</div>',
                            ['/home/view', 'id_user' => Yii::$app->user->identity->id]
                        ) ?>


                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative Bottom Border -->
        <div class="absolute bottom-0 left-0 right-0 h-1 opacity-50 bg-gradient-to-r from-blue-400 to-blue-600"></div>
    </header>

    <!-- Enhanced Announcement Banner -->
    <div class="container relative z-50 px-4 mx-auto mt-6 md:px-6 ">
        <div class="relative overflow-hidden transition-all duration-300 bg-white shadow-lg rounded-xl hover:shadow-xl group">
            <!-- Decorative gradient background -->
            <div class="absolute inset-0 z-0 opacity-10 bg-gradient-to-r from-blue-500 via-purple-500 to-red-500"></div>

            <!-- Animated wave pattern -->
            <div class="absolute bottom-0 left-0 w-full h-8 overflow-hidden transform translate-y-1">
                <svg class="absolute bottom-0 left-0 w-full text-white" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                        class="fill-current opacity-20"
                        style="color: #3b82f6;"></path>
                    <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                        class="opacity-50 fill-current"
                        style="color: #3b82f6;"></path>
                    <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                        class="fill-current"
                        style="color: #3b82f6;"></path>
                </svg>
            </div>

            <a href="/panel/home/pengumuman" class="relative z-50 flex flex-col md:flex-row ">
                <!-- Image with gradient overlay -->
                <div class="relative md:w-2/5">
                    <!-- Gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-l-xl"></div>

                    <!-- SVG Speaker/Announcement Icon -->
                    <div class="flex items-center justify-center w-full h-48 bg-gray-100 md:h-full rounded-l-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8a3 3 0 0 1 0 6M18 8a3 3 0 0 0 0-6H5.75A1.75 1.75 0 0 0 4 3.75v8.5c0 .966.784 1.75 1.75 1.75H18z" />
                            <path d="M8 15v2a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-2" />
                            <path d="M12 15V9" />
                            <path d="M9 12h6" />
                        </svg>
                    </div>

                    <!-- Decorative badge -->
                    <span class="absolute z-50 px-3 py-1 text-xs font-bold text-white uppercase transform rounded-full shadow-lg bg-gradient-to-r from-red-500 to-pink-500 -rotate-6 -top-0 -right-3">
                        New!
                    </span>

                    <!-- Decorative pulse animation -->
                    <div class="absolute top-0 left-0 flex items-center justify-center w-full h-full">
                        <div class="absolute w-16 h-16 bg-blue-400 rounded-full opacity-0 animate-ping-slow"></div>
                    </div>
                </div>
                <!-- Content with improved typography -->
                <div class="p-6 md:w-3/5">
                    <div class="flex items-center mb-2">
                        <h2 class="text-2xl font-bold text-gray-800">Informasi Pengumuman</h2>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-2 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    <p class="mb-4 text-gray-600">Update terbaru dari manajemen untuk seluruh karyawan</p>

                    <!-- Improved CTA button -->
                    <div class="inline-flex items-center px-4 py-2 mt-2 space-x-2 text-sm font-medium text-white transition-all duration-200 transform bg-blue-600 rounded-lg group-hover:bg-blue-700 group-hover:scale-105">
                        <span>Lihat selengkapnya</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- Decorative dots -->
                    <div class="absolute bottom-0 right-0 hidden mb-6 mr-6 space-x-1 md:flex">
                        <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                        <div class="w-2 h-2 bg-purple-400 rounded-full"></div>
                        <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                    </div>
                </div>
            </a>

            <!-- Animated corner accent -->
            <div class="absolute top-0 right-0 w-16 h-16 overflow-hidden">
                <div class="absolute w-32 h-8 transform rotate-45 bg-blue-500 -right-8 top-4"></div>
            </div>
        </div>
    </div>

    <!-- Shift Information -->
    <?php if ($jamKerjaToday && $jamKerjaToday['is_shift'] != 0 && $dataShift !== null): ?>
        <div class="container relative z-40 px-4 mx-auto mt-8 md:px-6">
            <?php if ($manual_shift == 1): ?>

                <div class="p-4 bg-blue-600 rounded-lg shadow-md">
                    <div class="flex flex-col items-center justify-between md:flex-row">
                        <div class="text-center text-white md:text-left">
                            <h3 class="text-lg font-semibold">Jadwal Shift Hari Ini</h3>
                            <p class="text-blue-100"><?= $dataShift['nama_shift'] ?? '-' ?></p>
                        </div>
                        <div class="flex items-center mt-4 space-x-4 md:mt-0">
                            <div class="text-center text-white">
                                <p class="text-sm text-blue-100">Jam Masuk</p>
                                <p class="font-bold"><?= $dataShift['jam_masuk'] ?? '-' ?></p>
                            </div>
                            <div class="text-center text-white">
                                <p class="text-sm text-blue-100">Jam Keluar</p>
                                <p class="font-bold"><?= $dataShift['jam_keluar'] ?? '-' ?></p>
                            </div>
                        </div>
                        <?php if ($change_shift == 0): ?>
                            <a href="/panel/home/pengajuan-shift?id_karyawan=<?= Yii::$app->user->identity->id_karyawan ?>"
                                class="px-4 py-2 mt-4 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50 md:mt-0">
                                Ubah Shift
                            </a>
                        <?php else: ?>
                            <a href="/panel/home/change-shift?id_karyawan=<?= Yii::$app->user->identity->id_karyawan ?>"
                                class="px-4 py-2 mt-4 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50 md:mt-0">
                                Ubah Shift
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($change_shift == 0): ?>
                    <div class="mt-2 text-center">
                        <a href="/panel/home/lihat-shift?id_karyawan=<?= Yii::$app->user->identity->id_karyawan ?>"
                            class="text-sm text-blue-600 hover:underline">Lihat Detail Shift</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Quick Actions Grid -->
    <div class="container relative z-40 px-4 py-8 mx-auto md:px-6">
        <h2 class="mb-6 text-xl font-bold text-gray-800">Pengajuan</h2>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
            <!-- Lembur -->
            <a href="/panel/pengajuan/lembur" class="transition transform hover:scale-105">
                <div class="flex flex-col items-center p-6 bg-white shadow-md rounded-xl hover:shadow-lg">
                    <div class="p-3 mb-4 bg-blue-100 rounded-full">
                        <!-- Icon Lembur -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-medium text-center text-gray-700">Lembur</h3>
                </div>
            </a>

            <!-- Dinas Luar -->
            <a href="/panel/pengajuan/dinas" class="transition transform hover:scale-105">
                <div class="flex flex-col items-center p-6 bg-white shadow-md rounded-xl hover:shadow-lg">
                    <div class="p-3 mb-4 bg-blue-100 rounded-full">
                        <!-- Icon Dinas -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18m-7-4h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-medium text-center text-gray-700">Dinas Luar</h3>
                </div>
            </a>

            <!-- WFH -->
            <a href="/panel/pengajuan/wfh" class="transition transform hover:scale-105">
                <div class="flex flex-col items-center p-6 bg-white shadow-md rounded-xl hover:shadow-lg">
                    <div class="p-3 mb-4 bg-blue-100 rounded-full">
                        <!-- Icon WFH -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                    </div>
                    <h3 class="font-medium text-center text-gray-700">WFH</h3>
                </div>
            </a>

            <!-- Cuti -->
            <a href="/panel/pengajuan/cuti" class="transition transform hover:scale-105">
                <div class="flex flex-col items-center p-6 bg-white shadow-md rounded-xl hover:shadow-lg">
                    <div class="p-3 mb-4 bg-blue-100 rounded-full">
                        <!-- Icon Cuti -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-medium text-center text-gray-700">Cuti</h3>
                </div>
            </a>

            <!-- Absensi Tertinggal -->
            <?php if ($deviasiAbsensi && $deviasiAbsensi['nilai_setting'] == 1) : ?>
                <a href="/panel/absensi-tertinggal" class="transition transform hover:scale-105">
                    <div class="flex flex-col items-center p-6 bg-white shadow-md rounded-xl hover:shadow-lg">
                        <div class="p-3 mb-4 bg-blue-100 rounded-full">
                            <!-- Icon Absensi -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="font-medium text-center text-gray-700">Deviasi Absensi</h3>
                    </div>
                </a>
            <?php endif; ?>

            <!-- Tugas Luar -->
            <a href="/panel/pengajuan/tugas-luar" class="transition transform hover:scale-105">
                <div class="flex flex-col items-center p-6 bg-white shadow-md rounded-xl hover:shadow-lg">
                    <div class="p-3 mb-4 bg-blue-100 rounded-full">
                        <!-- Icon Tugas -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="font-medium text-center text-gray-700">Tugas Luar</h3>
                </div>
            </a>
        </div>
    </div>


    <!-- For Supervisors -->
    <?php
    $atasan = AtasanKaryawan::find()->where(['id_atasan' => $karyawan['id_karyawan']])->one();
    ?>
    <?php if ($atasan): ?>
        <div class="container block max-w-3xl py-8 mx-auto md:hidden">
            <div class="overflow-hidden bg-white shadow-md rounded-xl">
                <div class="p-6 text-white bg-gradient-to-r from-blue-600 to-indigo-700">
                    <h1 class="flex items-center text-2xl font-bold">

                        Employee Approval
                    </h1>
                    <p class="mt-2 opacity-90">Terdapat pengajuan yang membutuhkan persetujuan Anda</p>
                </div>

                <div class="divide-y divide-gray-100">
                    <!-- WFH Approval -->
                    <a href="/panel/tanggapan/wfh" class="block transition-all duration-200 group hover:bg-blue-50">
                        <div class="flex items-center justify-between p-4">
                            <div class="flex items-center">
                                <div class="p-3 transition-colors duration-200 bg-purple-100 rounded-lg group-hover:bg-purple-200">
                                    <i class="text-xl text-purple-600 fas fa-cloud"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-800">Work From Home (WFH)</h3>
                                    <p class="text-sm text-gray-500">Pengajuan kerja dari rumah</p>
                                </div>
                            </div>
                            <!-- <div class="flex items-center">
                            <span class="mr-3 text-sm text-gray-500">12 pending</span>
                            <div class="px-2 py-1 text-xs font-bold text-purple-700 transition-colors duration-200 bg-purple-100 rounded-full group-hover:bg-purple-200">
                                12
                            </div>
                            <i class="ml-2 text-gray-400 transition-colors duration-200 fas fa-chevron-right group-hover:text-purple-600"></i>
                        </div> -->
                        </div>
                    </a>

                    <!-- Cuti Approval -->
                    <a href="/panel/tanggapan/cuti" class="block transition-all duration-200 group hover:bg-green-50">
                        <div class="flex items-center justify-between p-4">
                            <div class="flex items-center">
                                <div class="p-3 transition-colors duration-200 bg-green-100 rounded-lg group-hover:bg-green-200">
                                    <i class="text-xl text-green-600 fas fa-calendar-day"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-800">Cuti</h3>
                                    <p class="text-sm text-gray-500">Pengajuan waktu cuti</p>
                                </div>
                            </div>
                            <!-- <div class="flex items-center">
                            <span class="mr-3 text-sm text-gray-500">8 pending</span>
                            <div class="px-2 py-1 text-xs font-bold text-green-700 transition-colors duration-200 bg-green-100 rounded-full group-hover:bg-green-200">
                                8
                            </div>
                            <i class="ml-2 text-gray-400 transition-colors duration-200 fas fa-chevron-right group-hover:text-green-600"></i>
                        </div> -->
                        </div>
                    </a>

                    <!-- Lembur Approval -->
                    <a href="/panel/tanggapan/lembur" class="block transition-all duration-200 group hover:bg-red-50">
                        <div class="flex items-center justify-between p-4">
                            <div class="flex items-center">
                                <div class="p-3 transition-colors duration-200 bg-red-100 rounded-lg group-hover:bg-red-200">
                                    <i class="text-xl text-red-600 fas fa-clock"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-800">Lembur</h3>
                                    <p class="text-sm text-gray-500">Pengajuan kerja lembur</p>
                                </div>
                            </div>
                            <!-- <div class="flex items-center">
                            <span class="mr-3 text-sm text-gray-500">5 pending</span>
                            <div class="px-2 py-1 text-xs font-bold text-red-700 transition-colors duration-200 bg-red-100 rounded-full group-hover:bg-red-200">
                                5
                            </div>
                            <i class="ml-2 text-gray-400 transition-colors duration-200 fas fa-chevron-right group-hover:text-red-600"></i>
                        </div> -->
                        </div>
                    </a>

                    <!-- Dinas Approval -->
                    <a href="/panel/tanggapan/dinas" class="block transition-all duration-200 group hover:bg-blue-50">
                        <div class="flex items-center justify-between p-4">
                            <div class="flex items-center">
                                <div class="p-3 transition-colors duration-200 bg-blue-100 rounded-lg group-hover:bg-blue-200">
                                    <i class="text-xl text-blue-600 fas fa-briefcase"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-800">Dinas</h3>
                                    <p class="text-sm text-gray-500">Pengajuan perjalanan dinas</p>
                                </div>
                            </div>
                            <!-- <div class="flex items-center">
                                <span class="mr-3 text-sm text-gray-500">3 pending</span>
                                <div class="px-2 py-1 text-xs font-bold text-blue-700 transition-colors duration-200 bg-blue-100 rounded-full group-hover:bg-blue-200">
                                    3
                                </div>
                                <i class="ml-2 text-gray-400 transition-colors duration-200 fas fa-chevron-right group-hover:text-blue-600"></i>
                            </div> -->
                        </div>
                    </a>

                    <!-- Deviasi Absensi -->
                    <a href="/panel/tanggapan/absensi" class="block transition-all duration-200 group hover:bg-yellow-50">
                        <div class="flex items-center justify-between p-4">
                            <div class="flex items-center">
                                <div class="p-3 transition-colors duration-200 bg-yellow-100 rounded-lg group-hover:bg-yellow-200">
                                    <i class="text-xl text-yellow-600 fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-800">Deviasi Absensi</h3>
                                    <p class="text-sm text-gray-500">Pengajuan penyesuaian absensi</p>
                                </div>
                            </div>
                            <!-- <div class="flex items-center">
                                <span class="mr-3 text-sm text-gray-500">7 pending</span>
                                <div class="px-2 py-1 text-xs font-bold text-yellow-700 transition-colors duration-200 bg-yellow-100 rounded-full group-hover:bg-yellow-200">
                                    7
                                </div>
                                <i class="ml-2 text-gray-400 transition-colors duration-200 fas fa-chevron-right group-hover:text-yellow-600"></i> -->
                            <!-- </div> -->
                        </div>
                    </a>

                    <!-- Tugas Luar -->
                    <a href="/panel/tanggapan/tugas-luar" class="block transition-all duration-200 group hover:bg-indigo-50">
                        <div class="flex items-center justify-between p-4">
                            <div class="flex items-center">
                                <div class="p-3 transition-colors duration-200 bg-indigo-100 rounded-lg group-hover:bg-indigo-200">
                                    <i class="text-xl text-indigo-600 fas fa-file-alt"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-800">Tugas Luar</h3>
                                    <p class="text-sm text-gray-500">Pengajuan tugas luar kantor</p>
                                </div>
                            </div>
                            <!-- <div class="flex items-center">
                                <span class="mr-3 text-sm text-gray-500">4 pending</span>
                                <div class="px-2 py-1 text-xs font-bold text-indigo-700 transition-colors duration-200 bg-indigo-100 rounded-full group-hover:bg-indigo-200">
                                    4
                                </div>
                                <i class="ml-2 text-gray-400 transition-colors duration-200 fas fa-chevron-right group-hover:text-indigo-600"></i>
                            </div> -->
                        </div>
                    </a>
                </div>

                <!-- <div class="p-4 text-sm text-center text-gray-500 bg-gray-50">
                    Total 39 pengajuan yang membutuhkan persetujuan
                </div> -->
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    // Update clock
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
    updateClock();

    // Update date
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