<?php

use backend\models\AtasanKaryawan;
use yii\helpers\Html;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="relative z-40 min-h-screen bg-gray-50">
    <!-- Header Section -->
    <header class="relative z-50 text-black ">
        <div class="container px-4 py-4 mx-auto md:px-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <!-- User Profile Section -->
                <div class="flex items-center justify-between md:justify-start">
                    <div class="flex items-center">


                        <!-- User Info -->
                        <div class="ml-4">
                            <h1 class="text-xl font-bold md:text-2xl"><?= Html::encode($karyawan->nama) ?></h1>
                            <p class="text-sm text-black/60"><?= Html::encode($karyawan->kode_karyawan) ?></p>
                        </div>
                    </div>

                </div>

                <!-- Right Side Controls -->
                <div class="flex items-center justify-between mt-4 md:mt-0">
                    <!-- Time Display -->
                    <div class="flex items-center px-4 py-2 rounded-lg ">
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
                                '<div class="p-2 transition-colors duration-200 rounded-full hover:bg-white">' .
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">' .
                                    '<g fill="none">' .
                                    '<path fill="url(#SVG6va8FeeJ)" d="M15 18a3 3 0 1 1-6 0a3 3 0 0 1 6 0"/>' .
                                    '<path fill="url(#SVG739fbesO)" d="M12 2.004a7.5 7.5 0 0 1 7.5 7.5v3.998l1.418 3.16a.95.95 0 0 1-.866 1.34h-16.1a.95.95 0 0 1-.867-1.339l1.415-3.16V9.49l.005-.25A7.5 7.5 0 0 1 12 2.004"/>' .
                                    '<defs>' .
                                    '<linearGradient id="SVG6va8FeeJ" x1="12" x2="12.019" y1="17.5" y2="20.999" gradientUnits="userSpaceOnUse">' .
                                    '<stop stop-color="#eb4824"/>' .
                                    '<stop offset="1" stop-color="#ffcd0f" stop-opacity="0.988"/>' .
                                    '</linearGradient>' .
                                    '<linearGradient id="SVG739fbesO" x1="21.027" x2="5.578" y1="17.995" y2="3.776" gradientUnits="userSpaceOnUse">' .
                                    '<stop stop-color="#ff6f47"/>' .
                                    '<stop offset="1" stop-color="#ffcd0f"/>' .
                                    '</linearGradient>' .
                                    '</defs>' .
                                    '</g>' .
                                    '</svg>' .
                                    ($is_ada_notif > 0 ? '<span class="absolute w-2 h-2 bg-red-500 rounded-full top-2 right-2"></span>' : '') .
                                    '</div>',
                                ['/home/inbox', 'id_user' => Yii::$app->user->identity->id]
                            ) ?>
                        </div>

                        <!-- Refresh Button -->
                        <?= Html::a(
                            '<div class="p-2 transition-colors duration-200 rounded-full hover:bg-white">' .
                                '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 20 20" fill="none">' .
                                '<g>' .
                                '<path fill="url(#SVGhPVQMdww)" d="M10 5.5a1 1 0 0 1 1 1V9h1.5a1 1 0 1 1 0 2H10a1 1 0 0 1-1-1V6.5a1 1 0 0 1 1-1"/>' .
                                '<path fill="url(#SVGoKFQYQSV)" d="M6.031 5.5A6 6 0 1 1 4 10a1 1 0 0 0-2 0a8 8 0 1 0 2.5-5.81V3a1 1 0 0 0-2 0v3A1.5 1.5 0 0 0 4 7.5h3a1 1 0 0 0 0-2z"/>' .
                                '<defs>' .
                                '<linearGradient id="SVGhPVQMdww" x1="8.156" x2="20.094" y1="16.45" y2="11.414" gradientUnits="userSpaceOnUse">' .
                                '<stop stop-color="#d373fc"/>' .
                                '<stop offset="1" stop-color="#6d37cd"/>' .
                                '</linearGradient>' .
                                '<linearGradient id="SVGoKFQYQSV" x1="2" x2="6.295" y1="2.941" y2="20.923" gradientUnits="userSpaceOnUse">' .
                                '<stop stop-color="#0fafff"/>' .
                                '<stop offset="1" stop-color="#0067bf"/>' .
                                '</linearGradient>' .
                                '</defs>' .
                                '</g>' .
                                '</svg>' .
                                '</div>',
                            ['/home/view', 'id_user' => Yii::$app->user->identity->id]
                        ) ?>


                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative Bottom Border -->
    </header>

    <!-- Enhanced Announcement Banner -->
    <div class="container relative z-50 px-1 mx-auto mt-2 md:px-6 ">
        <div class="relative overflow-hidden bg-blue-500 rounded-lg shadow-lg ">

            <a href="/panel/home/pengumuman" class="relative z-50 flex flex-col md:flex-row ">
                <div class="p-4">
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 20 20">
                            <g fill="none">
                                <path fill="url(#SVG5pbk5biH)" fill-rule="evenodd" d="M6 15.5a3.5 3.5 0 1 1 7 0a3.5 3.5 0 0 1-7 0m3.5-2a2 2 0 1 0 0 4a2 2 0 0 0 0-4" clip-rule="evenodd" />
                                <path fill="url(#SVGaO6vgcbc)" fill-opacity="0.8" fill-rule="evenodd" d="M6 15.5a3.5 3.5 0 1 1 7 0a3.5 3.5 0 0 1-7 0m3.5-2a2 2 0 1 0 0 4a2 2 0 0 0 0-4" clip-rule="evenodd" />
                                <path fill="url(#SVGoFT8beef)" d="M7.607 3.145a2 2 0 0 1 3.261-.514l6.587 6.98a2 2 0 0 1-.648 3.203L5.325 17.872a1.5 1.5 0 0 1-1.661-.307l-1.222-1.211a1.5 1.5 0 0 1-.299-1.71z" />
                                <path fill="url(#SVGlda54dKu)" fill-opacity="0.8" d="M7.607 3.145a2 2 0 0 1 3.261-.514l6.587 6.98a2 2 0 0 1-.648 3.203L5.325 17.872a1.5 1.5 0 0 1-1.661-.307l-1.222-1.211a1.5 1.5 0 0 1-.299-1.71z" />
                                <path fill="url(#SVGWjXjrBKh)" d="M14.712 1.737a.75.75 0 0 0-1.423-.474l-.5 1.5a.75.75 0 1 0 1.423.474z" />
                                <path fill="url(#SVGWjXjrBKh)" d="M18.03 3.03a.75.75 0 0 0-1.06-1.06l-2 2a.75.75 0 0 0 1.06 1.06z" />
                                <path fill="url(#SVGWjXjrBKh)" d="M17 5.75a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5z" />
                                <defs>
                                    <linearGradient id="SVG5pbk5biH" x1="14.5" x2="11.487" y1="23.5" y2="16.098" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#43e5ca" />
                                        <stop offset="1" stop-color="#0c74a1" />
                                    </linearGradient>
                                    <linearGradient id="SVGaO6vgcbc" x1="8" x2="11.003" y1="13" y2="20.499" gradientUnits="userSpaceOnUse">
                                        <stop offset=".08" stop-color="#e362f8" />
                                        <stop offset=".656" stop-color="#96f" stop-opacity="0" />
                                    </linearGradient>
                                    <linearGradient id="SVGoFT8beef" x1="2.57" x2="13.609" y1="5.003" y2="16.477" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#43e5ca" />
                                        <stop offset="1" stop-color="#1384b1" />
                                    </linearGradient>
                                    <linearGradient id="SVGlda54dKu" x1="10" x2="17" y1="11" y2="22.5" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#96f" stop-opacity="0" />
                                        <stop offset=".63" stop-color="#e362f8" />
                                    </linearGradient>
                                    <radialGradient id="SVGWjXjrBKh" cx="0" cy="0" r="1" gradientTransform="matrix(14 -14 14.00977 14.00977 6 14)" gradientUnits="userSpaceOnUse">
                                        <stop offset=".623" stop-color="#fb5937" />
                                        <stop offset=".935" stop-color="#ffa43d" />
                                    </radialGradient>
                                </defs>
                            </g>
                        </svg>
                        <h2 class="pl-5 text-xl font-bold text-white">Informasi Pengumuman</h2>
                    </div>

                    <p class="mb-4 text-sm text-white">Update terbaru dari manajemen untuk seluruh karyawan</p>

                </div>
            </a>

            <!-- Animated corner accent -->
            <div class="absolute top-0 left-0 w-16 h-16 overflow-hidden">
                <div class="absolute w-32 h-8 transform rotate-180 bg-white -left-8 top-4"></div>
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



    <div class="container mx-auto md:px-6">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-lg font-bold text-gray-800">Pengajuan</h2>
        </div>

        <!-- Grid Ikon Kecil -->
        <div class="grid grid-cols-4 gap-3">
            <!-- Lembur -->
            <a href="/panel/pengajuan/lembur" class="flex flex-col items-center p-3 transition-all duration-200 bg-white shadow-sm rounded-xl hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 mb-2 rounded-full bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 20 20">
                        <g fill="none">
                            <path fill="url(#SVGy8IE7TVy)" d="M10 2a8 8 0 1 1 0 16a8 8 0 0 1 0-16" />
                            <path fill="url(#SVGLYEppcwr)" d="M9.5 5a.5.5 0 0 1 .492.41L10 5.5V10h2.5a.5.5 0 0 1 .09.992L12.5 11h-3a.5.5 0 0 1-.492-.41L9 10.5v-5a.5.5 0 0 1 .5-.5" />
                            <defs>
                                <linearGradient id="SVGy8IE7TVy" x1="4.667" x2="12.667" y1="1.111" y2="18.889" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#1ec8b0" />
                                    <stop offset="1" stop-color="#2764e7" />
                                </linearGradient>
                                <linearGradient id="SVGLYEppcwr" x1="9.35" x2="7.65" y1="5.918" y2="10.578" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#fdfdfd" />
                                    <stop offset="1" stop-color="#d1d1ff" />
                                </linearGradient>
                            </defs>
                        </g>
                    </svg>
                </div>
                <span class="text-xs font-medium leading-tight text-center text-gray-700">Lembur</span>
            </a>

            <!-- Dinas Luar -->
            <a href="/panel/pengajuan/dinas" class="flex flex-col items-center p-3 transition-all duration-200 bg-white shadow-sm rounded-xl hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 mb-2 rounded-full bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 28 28">
                        <g fill="none">
                            <path fill="url(#SVGVUYNtcSt)" fill-rule="evenodd" d="M16.746 2.5a2.25 2.25 0 0 1 2.25 2.25V7H19l-5 3l-5-3l-.004-.002V4.75a2.25 2.25 0 0 1 2.25-2.25zm-5.5 1.5a.75.75 0 0 0-.75.75V7h7V4.75a.75.75 0 0 0-.75-.75z" clip-rule="evenodd" />
                            <path fill="url(#SVGFKpk7bdQ)" d="M3 13h22v7.25A3.75 3.75 0 0 1 21.25 24H6.75A3.75 3.75 0 0 1 3 20.25z" />
                            <path fill="url(#SVGLIzhtbdh)" d="M3 13h22v7.25A3.75 3.75 0 0 1 21.25 24H6.75A3.75 3.75 0 0 1 3 20.25z" />
                            <path fill="url(#SVGpbVsBd9l)" d="M3 10.75A3.75 3.75 0 0 1 6.75 7h14.5A3.75 3.75 0 0 1 25 10.75v4A2.25 2.25 0 0 1 22.75 17H5.25A2.25 2.25 0 0 1 3 14.75z" />
                            <path fill="url(#SVGVIiPIbzQ)" d="M15.246 13.516h-2.508a1.25 1.25 0 0 0-1.254 1.246v2.492a1.25 1.25 0 0 0 1.254 1.246h2.508a1.25 1.25 0 0 0 1.254-1.246v-2.492a1.25 1.25 0 0 0-1.254-1.246" />
                            <defs>
                                <linearGradient id="SVGVUYNtcSt" x1="8.542" x2="11.184" y1="3.25" y2="11.204" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#0094f0" />
                                    <stop offset="1" stop-color="#163697" />
                                </linearGradient>
                                <linearGradient id="SVGFKpk7bdQ" x1="3.786" x2="9.728" y1="15.063" y2="35.325" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#0fafff" />
                                    <stop offset="1" stop-color="#cc23d1" />
                                </linearGradient>
                                <linearGradient id="SVGpbVsBd9l" x1="5.2" x2="16.258" y1="7.415" y2="19.722" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#80f1e6" />
                                    <stop offset=".552" stop-color="#40c4f5" />
                                    <stop offset="1" stop-color="#00a2fa" />
                                </linearGradient>
                                <linearGradient id="SVGVIiPIbzQ" x1="13.992" x2="13.992" y1="13.516" y2="18.5" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#b8f5ff" />
                                    <stop offset=".844" stop-color="#7cecff" />
                                </linearGradient>
                                <radialGradient id="SVGLIzhtbdh" cx="0" cy="0" r="1" gradientTransform="matrix(0 11 -24.5223 0 14 13)" gradientUnits="userSpaceOnUse">
                                    <stop offset=".337" stop-color="#194694" />
                                    <stop offset=".747" stop-color="#367af2" stop-opacity="0" />
                                </radialGradient>
                            </defs>
                        </g>
                    </svg>
                </div>
                <span class="text-xs font-medium leading-tight text-center text-gray-700">Dinas Luar</span>
            </a>

            <!-- WFH -->
            <a href="/panel/pengajuan/wfh" class="flex flex-col items-center p-3 transition-all duration-200 bg-white shadow-sm rounded-xl hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 mb-2 rounded-full bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 32 32">
                        <g fill="none">
                            <path fill="url(#SVGjZzzecUj)" d="M27 30a1 1 0 0 0 1-1V16.25A3.25 3.25 0 0 0 24.75 13H22V5.25A3.25 3.25 0 0 0 18.75 2H7a3 3 0 0 0-3 3v24a1 1 0 0 0 1 1z" />
                            <path fill="url(#SVGhF2yjerc)" d="M27 30a1 1 0 0 0 1-1V16.25A3.25 3.25 0 0 0 24.75 13H22V5.25A3.25 3.25 0 0 0 18.75 2H7a3 3 0 0 0-3 3v24a1 1 0 0 0 1 1z" />
                            <path fill="url(#SVGklOfuc5J)" d="M27 30a1 1 0 0 0 1-1V16.25A3.25 3.25 0 0 0 24.75 13H22V5.25A3.25 3.25 0 0 0 18.75 2H7a3 3 0 0 0-3 3v24a1 1 0 0 0 1 1z" />
                            <path fill="url(#SVGoZGTCYVN)" d="M10.5 10a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3m0 5a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3m1.5 3.5a1.5 1.5 0 1 1-3 0a1.5 1.5 0 0 1 3 0m3.5-8.5a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3m1.5 3.5a1.5 1.5 0 1 1-3 0a1.5 1.5 0 0 1 3 0" />
                            <path fill="url(#SVGLh73CcMl)" d="M21 25h5v6h-5z" />
                            <path fill="url(#SVG7iQlScPv)" d="M22.448 17.888a1.625 1.625 0 0 1 2.105 0l4.875 4.144c.363.309.573.761.573 1.238v6.48c0 .69-.56 1.25-1.25 1.25h-3.25v-4.5a1 1 0 0 0-.996-1h-2.009a1 1 0 0 0-.996 1V31h-3.25c-.69 0-1.25-.56-1.25-1.25v-6.48c0-.477.209-.929.573-1.238z" />
                            <path fill="url(#SVGUsD2veXK)" fill-rule="evenodd" d="M22.687 16.05a1.25 1.25 0 0 1 1.627 0l7 6a1.25 1.25 0 1 1-1.628 1.9L23.5 18.645l-6.186 5.303a1.25 1.25 0 0 1-1.627-1.898z" clip-rule="evenodd" />
                            <defs>
                                <linearGradient id="SVGjZzzecUj" x1="4" x2="30.607" y1="2.875" y2="32.072" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#29c3ff" />
                                    <stop offset="1" stop-color="#2764e7" />
                                </linearGradient>
                                <linearGradient id="SVGoZGTCYVN" x1="12.9" x2="17.649" y1="5.556" y2="22.653" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#fdfdfd" />
                                    <stop offset="1" stop-color="#b3e0ff" />
                                </linearGradient>
                                <linearGradient id="SVGLh73CcMl" x1="23.5" x2="19.738" y1="25" y2="31.969" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#944600" />
                                    <stop offset="1" stop-color="#cd8e02" />
                                </linearGradient>
                                <linearGradient id="SVG7iQlScPv" x1="5.577" x2="14.981" y1="18.998" y2="37.035" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#ffd394" />
                                    <stop offset="1" stop-color="#ffb357" />
                                </linearGradient>
                                <linearGradient id="SVGUsD2veXK" x1="24.286" x2="22.86" y1="13.334" y2="23.508" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#ff921f" />
                                    <stop offset="1" stop-color="#eb4824" />
                                </linearGradient>
                                <radialGradient id="SVGhF2yjerc" cx="0" cy="0" r="1" gradientTransform="matrix(-5.50004 4.49996 -1.81836 -2.22248 20 22)" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#4a43cb" />
                                    <stop offset=".914" stop-color="#4a43cb" stop-opacity="0" />
                                </radialGradient>
                                <radialGradient id="SVGklOfuc5J" cx="0" cy="0" r="1" gradientTransform="matrix(0 10 -7.25 0 22 28)" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#4a43cb" />
                                    <stop offset=".914" stop-color="#4a43cb" stop-opacity="0" />
                                </radialGradient>
                            </defs>
                        </g>
                    </svg>
                </div>
                <span class="text-xs font-medium leading-tight text-center text-gray-700">WFH</span>
            </a>

            <!-- Cuti -->
            <a href="/panel/pengajuan/cuti" class="flex flex-col items-center p-3 transition-all duration-200 bg-white shadow-sm rounded-xl hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 mb-2 rounded-full bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 16 16">
                        <g fill="none">
                            <path fill="url(#SVG31HzRcTh)" d="m10.119 5.545l-.87-.495l-2.188 3.87l.869.495z" />
                            <path fill="url(#SVGxrLjmeqQ)" d="M3.88 12.508L6 13.55l1.71-1.196L9.5 13.55l2-.696h.8a4.52 4.52 0 0 0-2.279-3.866a4.5 4.5 0 0 0-6.718 4.073l.578-.762z" />
                            <path fill="url(#SVGDo2AebfB)" d="M4.12 11.75a.5.5 0 0 1 .495.343c.464 1.401 2.268 1.377 2.72.06a.5.5 0 0 1 .946 0c.453 1.317 2.257 1.341 2.722-.06a.5.5 0 0 1 .95.005c.235.733.948 1.201 1.664 1.201a.5.5 0 1 1 0 1a2.8 2.8 0 0 1-2.142-1.022c-.95 1.14-2.715 1.154-3.666.041c-.935 1.093-2.654 1.1-3.615.02a2.2 2.2 0 0 1-.636.584c-.492.297-1.054.377-1.558.377a.5.5 0 0 1 0-1c.413 0 .768-.068 1.04-.233c.256-.154.49-.423.614-.935a.5.5 0 0 1 .466-.38" />
                            <path fill="url(#SVGXWItoehT)" d="M12.66 7.793a.5.5 0 0 0 .688-.188l.095-.17c1.141-2.068.479-4.668-1.54-5.85c-2.02-1.18-4.59-.459-5.731 1.608l-.105.187a.5.5 0 0 0 .185.676z" />
                            <path fill="url(#SVGfk55ZcWt)" d="M12.013 1.653c.22.99.057 3.266-1.038 5.15L8.25 5.215c1.008-1.933 2.82-3.248 3.76-3.564l.002-.002v.002h.002z" />
                            <defs>
                                <linearGradient id="SVG31HzRcTh" x1="7.685" x2="10.429" y1="8.264" y2="4.899" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#cd3e1d" />
                                    <stop offset="1" stop-color="#592a00" />
                                </linearGradient>
                                <linearGradient id="SVGxrLjmeqQ" x1="7.267" x2="7.267" y1="12.75" y2="8.73" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#ffc7a3" />
                                    <stop offset="1" stop-color="#ffa43d" />
                                </linearGradient>
                                <linearGradient id="SVGDo2AebfB" x1="7.817" x2="7.817" y1="13.875" y2="11.755" gradientUnits="userSpaceOnUse">
                                    <stop offset=".061" stop-color="#0fafff" />
                                    <stop offset="1" stop-color="#0078d4" />
                                </linearGradient>
                                <linearGradient id="SVGXWItoehT" x1="13.164" x2="6.966" y1="9.198" y2="1.158" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#aa1d2d" />
                                    <stop offset="1" stop-color="#fb6f7b" />
                                </linearGradient>
                                <linearGradient id="SVGfk55ZcWt" x1="11.933" x2="8.541" y1="4.782" y2="3.684" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#fecbe6" />
                                    <stop offset="1" stop-color="#fdafd9" />
                                </linearGradient>
                            </defs>
                        </g>
                    </svg>
                </div>
                <span class="text-xs font-medium leading-tight text-center text-gray-700">Cuti</span>
            </a>

            <!-- Deviasi Absensi (Kondisional) -->
            <?php if ($deviasiAbsensi && $deviasiAbsensi['nilai_setting'] == 1) : ?>
                <a href="/panel/absensi-tertinggal" class="flex flex-col items-center p-3 transition-all duration-200 bg-white shadow-sm rounded-xl hover:shadow-md hover:-translate-y-1">
                    <div class="flex items-center justify-center w-12 h-12 mb-2 rounded-full bg-blue-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 16 16">
                            <g fill="none">
                                <path fill="url(#SVGdDovv0Vh)" d="M14 11.5v-6l-6-1l-6 1v6A2.5 2.5 0 0 0 4.5 14h7a2.5 2.5 0 0 0 2.5-2.5" />
                                <path fill="url(#SVGNDdPseIb)" d="M14 11.5v-6l-6-1l-6 1v6A2.5 2.5 0 0 0 4.5 14h7a2.5 2.5 0 0 0 2.5-2.5" />
                                <path fill="url(#SVG5Oql7dko)" fill-opacity="0.3" d="M14 11.5v-6l-6-1l-6 1v6A2.5 2.5 0 0 0 4.5 14h7a2.5 2.5 0 0 0 2.5-2.5" />
                                <path fill="url(#SVGz0QaCcwg)" d="M14 4.5A2.5 2.5 0 0 0 11.5 2h-7A2.5 2.5 0 0 0 2 4.5V6h12z" />
                                <path fill="url(#SVGCl7ljW8O)" d="M16 11.5a4.5 4.5 0 1 1-9 0a4.5 4.5 0 0 1 9 0" />
                                <path fill="url(#SVGHfvqMc8a)" fill-rule="evenodd" d="M9.646 9.646a.5.5 0 0 1 .708 0l1.146 1.147l1.146-1.147a.5.5 0 0 1 .708.708L12.207 11.5l1.147 1.146a.5.5 0 0 1-.708.708L11.5 12.207l-1.146 1.147a.5.5 0 0 1-.708-.708l1.147-1.146l-1.147-1.146a.5.5 0 0 1 0-.708" clip-rule="evenodd" />
                                <defs>
                                    <linearGradient id="SVGdDovv0Vh" x1="6.286" x2="9.327" y1="4.5" y2="13.987" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#b3e0ff" />
                                        <stop offset="1" stop-color="#8cd0ff" />
                                    </linearGradient>
                                    <linearGradient id="SVGNDdPseIb" x1="9.286" x2="11.025" y1="8.386" y2="16.154" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#dcf8ff" stop-opacity="0" />
                                        <stop offset="1" stop-color="#ff6ce8" stop-opacity="0.7" />
                                    </linearGradient>
                                    <linearGradient id="SVGz0QaCcwg" x1="2.482" x2="4.026" y1="2" y2="8.725" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#0094f0" />
                                        <stop offset="1" stop-color="#2764e7" />
                                    </linearGradient>
                                    <linearGradient id="SVGCl7ljW8O" x1="8.406" x2="14.313" y1="7.563" y2="16.281" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#f83f54" />
                                        <stop offset="1" stop-color="#ca2134" />
                                    </linearGradient>
                                    <linearGradient id="SVGHfvqMc8a" x1="9.977" x2="11.771" y1="11.652" y2="13.518" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#fdfdfd" />
                                        <stop offset="1" stop-color="#fecbe6" />
                                    </linearGradient>
                                    <radialGradient id="SVG5Oql7dko" cx="0" cy="0" r="1" gradientTransform="matrix(.14285 6.79546 -6.61306 .13902 11.857 12.704)" gradientUnits="userSpaceOnUse">
                                        <stop offset=".497" stop-color="#4a43cb" />
                                        <stop offset="1" stop-color="#4a43cb" stop-opacity="0" />
                                    </radialGradient>
                                </defs>
                            </g>
                        </svg>
                    </div>
                    <span class="text-xs font-medium leading-tight text-center text-gray-700">Deviasi Absensi</span>
                </a>
            <?php endif; ?>

            <!-- Tugas Luar -->
            <a href="/panel/pengajuan/tugas-luar" class="flex flex-col items-center p-3 transition-all duration-200 bg-white shadow-sm rounded-xl hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 mb-2 rounded-full bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 32 32">
                        <g fill="none">
                            <path fill="url(#SVG1qpqVdBJ)" d="M15 6a1 1 0 1 0 0 2h14a1 1 0 1 0 0-2z" />
                            <path fill="url(#SVG1qpqVdBJ)" d="M15 19a1 1 0 1 0 0 2h14a1 1 0 1 0 0-2z" />
                            <path fill="url(#SVG1qpqVdBJ)" d="M14 11a1 1 0 0 1 1-1h9a1 1 0 1 1 0 2h-9a1 1 0 0 1-1-1" />
                            <path fill="url(#SVG1qpqVdBJ)" d="M15 23a1 1 0 1 0 0 2h9a1 1 0 1 0 0-2z" />
                            <path fill="url(#SVG0gOLOycZ)" d="M2 8a3 3 0 0 1 3-3h3a3 3 0 0 1 3 3v3a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3z" />
                            <path fill="url(#SVG0gOLOycZ)" d="M5 18a3 3 0 0 0-3 3v3a3 3 0 0 0 3 3h3a3 3 0 0 0 3-3v-3a3 3 0 0 0-3-3z" />
                            <defs>
                                <linearGradient id="SVG1qpqVdBJ" x1="11.6" x2="26.904" y1="3.286" y2="26.008" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#36dff1" />
                                    <stop offset="1" stop-color="#0094f0" />
                                </linearGradient>
                                <linearGradient id="SVG0gOLOycZ" x1="4.14" x2="9.334" y1="7.925" y2="25.65" gradientUnits="userSpaceOnUse">
                                    <stop offset=".125" stop-color="#9c6cfe" />
                                    <stop offset="1" stop-color="#7a41dc" />
                                </linearGradient>
                            </defs>
                        </g>
                    </svg>
                </div>
                <span class="text-xs font-medium leading-tight text-center text-gray-700">Tugas Luar</span>
            </a>
            <!-- Kasbon -->
            <a href="/panel/pengajuan/kasbon" class="flex flex-col items-center p-3 transition-all duration-200 bg-white shadow-sm rounded-xl hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 mb-2 rounded-full bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                        <g fill="none">
                            <path fill="url(#SVGwgPMGcHm)" d="M21.071 8.86c.604.998.929 2.203.929 3.64c0 2.206-.833 3.75-1.68 4.738A6.7 6.7 0 0 1 19 18.421v1.83A1.75 1.75 0 0 1 17.25 22H16a1.5 1.5 0 0 1-1.5-1.5h-2A1.5 1.5 0 0 1 11 22H9.75A1.75 1.75 0 0 1 8 20.25v-.684a7 7 0 0 1-.464-.175A6.5 6.5 0 0 1 5.47 18.03a10 10 0 0 1-1.605-2.13a.49.49 0 0 0-.329-.259A1.84 1.84 0 0 1 2 13.828v-1.753c0-.843.61-1.562 1.44-1.7c.087-.015.216-.101.277-.284c.192-.576.565-1.434 1.253-2.121a8 8 0 0 1 1.658-1.246q.206-.117.372-.201V3.67c0-.938 1.13-1.323 1.74-.716c.33.329.81.768 1.341 1.134s1.033.66 1.454.66c4.248 0 7.684 1.051 9.536 4.11" />
                            <path fill="url(#SVGxokZwc7p)" d="M9 10a1 1 0 1 1-2 0a1 1 0 0 1 2 0" />
                            <path fill="#9f1459" d="M10.55 6.503a1.2 1.2 0 0 1 1.568-.652l7.376 3.045a1.201 1.201 0 1 1-.917 2.221l-7.376-3.045a1.2 1.2 0 0 1-.652-1.57" />
                            <path fill="url(#SVG8fCfGeML)" d="M17.545 10.69a4.001 4.001 0 1 0-5.244-2.164z" />
                            <path fill="url(#SVGUuvdnbWj)" fill-opacity="0.8" d="M17.545 10.69a4.001 4.001 0 1 0-5.244-2.164z" />
                            <defs>
                                <radialGradient id="SVGwgPMGcHm" cx="0" cy="0" r="1" gradientTransform="matrix(5.1875 18.18747 -18.81061 5.36523 8.125 4.563)" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#f08af4" />
                                    <stop offset=".581" stop-color="#e869ce" />
                                    <stop offset="1" stop-color="#d7257d" />
                                </radialGradient>
                                <radialGradient id="SVGxokZwc7p" cx="0" cy="0" r="1" gradientTransform="rotate(59.532 -4.541 11.412)scale(1.69458)" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#b91d6b" />
                                    <stop offset="1" stop-color="#670938" />
                                </radialGradient>
                                <linearGradient id="SVG8fCfGeML" x1="18.745" x2="12.651" y1="9.82" y2="4.629" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#ff8a69" />
                                    <stop offset="1" stop-color="#ffcd0f" />
                                </linearGradient>
                                <linearGradient id="SVGUuvdnbWj" x1="17.188" x2="14.563" y1="3.438" y2="9.75" gradientUnits="userSpaceOnUse">
                                    <stop offset=".67" stop-color="#fb5937" stop-opacity="0" />
                                    <stop offset="1" stop-color="#cd3e1d" />
                                </linearGradient>
                            </defs>
                        </g>
                    </svg>
                </div>
                <span class="text-xs font-medium leading-tight text-center text-gray-700">Kasbon</span>
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