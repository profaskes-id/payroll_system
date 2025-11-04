<?php

use backend\models\AtasanKaryawan;
use yii\helpers\Html;

$result = AtasanKaryawan::findOne(['id_atasan' => Yii::$app->user->identity->id_karyawan]);
?>


<div class="flex flex-col justify-between w-64 h-screen overflow-y-auto border-r shadow-xl bg-gradient-to-b from-slate-800 to-slate-900 border-slate-700">
    <div class="px-4 py-6">

        <!-- Navigation Menu -->
        <!-- Footer Section -->
        <div class="px-4 py-4 border-t border-slate-700">
            <div class="flex items-center mb-3">
                <?php if (Yii::$app->user->identity->profile->full_name) : ?>
                    <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=<?= urlencode(Yii::$app->user->identity->profile->full_name) ?>&background=random" alt="User avatar">
                <?php else : ?>
                    <div class="w-8 h-8 bg-blue-400 rounded-full"></div>
                <?php endif ?>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white"><?= Yii::$app->user->identity->username ?></p>
                    <p class="text-xs text-slate-400"><?= Yii::$app->user->identity->email ?></p>
                </div>
            </div>

            <div class="flex space-x-2">
                <!-- Profile Button -->
                <a href="/panel/user/profile"
                    class="flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-white transition-all duration-200 rounded-lg hover:bg-slate-700 hover:shadow-md group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 text-slate-400 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                    </svg>
                    Profile
                </a>

                <!-- Logout Button -->
                <?= Html::beginForm(['/user/logout'], 'post', ['class' => 'flex-1']) ?>
                <button type="submit" class="flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-white transition-all duration-200 rounded-lg hover:bg-red-600 hover:shadow-md group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 text-slate-400 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                    </svg>
                    Logout
                </button>
                <?= Html::endForm() ?>
            </div>
        </div>
        <ul class="space-y-2 overflow-y-auto ">
            <li>
                <a href="/panel/home" class="flex items-center px-4 py-3 text-sm font-medium text-white transition-all duration-200 rounded-lg hover:bg-slate-700 hover:shadow-md group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="/panel/home/inbox" class="flex items-center px-4 py-3 text-sm font-medium text-white transition-all duration-200 rounded-lg hover:bg-slate-700 hover:shadow-md group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                    Inbox
                </a>
            </li>

            <li>
                <a href="/panel/home/expirience" class="flex items-center px-4 py-3 text-sm font-medium text-white transition-all duration-200 rounded-lg hover:bg-slate-700 hover:shadow-md group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    Personal Data
                </a>
            </li>

            <?php if ($result != null) : ?>
                <li>
                    <details class="group">
                        <summary class="flex items-center justify-between px-4 py-3 text-sm font-medium text-white list-none transition-all duration-200 rounded-lg cursor-pointer hover:bg-slate-700 hover:shadow-md">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                Employee Requests
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 text-slate-400 group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </summary>

                        <ul class="pl-4 mt-1 ml-5 space-y-1 border-l-2 border-slate-700">
                            <li>
                                <a href="/panel/tanggapan/wfh" class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 text-slate-500 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                                    </svg>
                                    WFH
                                </a>
                            </li>

                            <li>
                                <a href="/panel/tanggapan/lembur" class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 text-slate-500 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    Lembur
                                </a>
                            </li>

                            <li>
                                <a href="/panel/tanggapan/cuti" class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 text-slate-500 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    Cuti
                                </a>
                            </li>

                            <li>
                                <a href="/panel/tanggapan/dinas" class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 text-slate-500 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1v-1a1 1 0 011-1h2a1 1 0 011 1v1a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1V5a1 1 0 00-1-1H3z" />
                                    </svg>
                                    Dinas
                                </a>
                            </li>
                            <li>
                                <a href="/panel/tanggapan/absensi" class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Deviasi Absensi
                                </a>
                            </li>
                            <li>
                                <a href="/panel/tanggapan/tugas-luar" class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        <path d="M15.5 12a3.5 3.5 0 11-7 0 3.5 3.5 0 017 0z" />
                                    </svg>
                                    Tugas Luar
                                </a>
                            </li>
                            <li>
                                <a href="/panel/tanggapan/pulang-cepat" class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M13.5 5.5c1.09 0 2-.92 2-2a2 2 0 0 0-2-2c-1.11 0-2 .88-2 2c0 1.08.89 2 2 2M9.89 19.38l1-4.38L13 17v6h2v-7.5l-2.11-2l.61-3A7.3 7.3 0 0 0 19 13v-2c-1.91 0-3.5-1-4.31-2.42l-1-1.58c-.4-.62-1-1-1.69-1c-.31 0-.5.08-.81.08L6 8.28V13h2V9.58l1.79-.7L8.19 17l-4.9-1l-.4 2z" />
                                    </svg>
                                    Pulang Cepat
                                </a>
                            </li>
                            <li>
                                <a href="/panel/tanggapan/kasbon" class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M8.4 21q-2.275 0-3.838-1.562T3 15.6q0-.95.325-1.85t.925-1.625L7.8 7.85L5.375 3h13.25L16.2 7.85l3.55 4.275q.6.725.925 1.625T21 15.6q0 2.275-1.575 3.838T15.6 21zm3.6-5q-.825 0-1.412-.587T10 14t.588-1.412T12 12t1.413.588T14 14t-.587 1.413T12 16M9.625 7h4.75l1-2h-6.75zM8.4 19h7.2q1.425 0 2.413-.987T19 15.6q0-.6-.213-1.162t-.587-1.013L14.525 9H9.5l-3.7 4.4q-.375.45-.587 1.025T5 15.6q0 1.425.988 2.413T8.4 19" />
                                    </svg>
                                    Kasbon
                                </a>
                            </li>
                            <li>
                                <a href="/panel/tanggapan/shift" class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            <path d="M4 18a2 2 0 1 0 4 0a2 2 0 1 0-4 0m12 0a2 2 0 1 0 4 0a2 2 0 1 0-4 0M6 12v-2a6 6 0 1 1 12 0v2" />
                                            <path d="m15 9l3 3l3-3" />
                                        </g>
                                    </svg>
                                    Shift
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
            <?php endif; ?>


        </ul>
    </div>


</div>