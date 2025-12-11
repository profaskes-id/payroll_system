<?php

use backend\models\JadwalKerja;
use backend\models\ShiftKerja;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Absensis';
$this->params['breadcrumbs'][] = $this->title;

$modals = [
    [
        'id' => 'popup-modal',
        'title' => 'Anda Akan Melakukan Aksi Absen Masuk ?',
        'confirm_id' => 'submitButton',
        'face_input' => 'foto_masuk'
    ],

];
$buttonStyles = [
    'masuk' => 'bg-gradient-to-r from-[#EB5A2B] to-[#EA792B] shadow-[#EB5A2B]',
    'pulang' => 'bg-gradient-to-r from-[#CE1705] to-[#EF0802] shadow-[#D51405]',
    'disabled' => 'bg-gradient-to-r from-[#686161] to-[#2b2b2b] shadow-[#9b9b9b]',
];

$modalStyles = [
    'container' => 'hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full',
    'content' => 'relative bg-white rounded-lg shadow dark:bg-gray-700 p-4 md:p-5 text-center',
    'button' => [
        'confirm' => 'text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center',
        'cancel' => 'py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700'
    ]
];

$iconButtonStyles = 'w-[60px] h-[60px] border bg-red-50 border-gray rounded-full grid place-items-center';
?>
<!-- Tambahkan di head -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.3"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<section class="min-h-[90dvh] relative overflow-x-hidden z-50">
    <!-- Confirmation Modals -->
    <div id="popup-modal" tabindex="-1"
        class="fixed inset-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-100/50">
        <div class="relative w-full max-w-md p-4">
            <div class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-700 md:p-5">
                <!-- Close Button -->
                <button type="button" onclick="closeModalFace('popup-modal')"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>

                <div class="p-3 text-center">
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Verifikasi Wajah untuk Absen Masuk</h3>
                    <!-- MediaPipe Liveness Container -->
                    <div id="liveness-container-popup-modal" class="liveness-container">
                        <div class="liveness-video-container">
                            <!-- Video untuk webcam -->
                            <video id="webcam-popup-modal" class="liveness-video" autoplay playsinline></video>
                            <!-- Canvas untuk overlay -->
                            <canvas id="output_canvas-popup-modal" class="liveness-canvas"></canvas>

                            <!-- Instruction overlay -->
                            <div id="instruction-overlay-popup-modal" class="liveness-instruction">
                                <div class="liveness-status">Tekan "Mulai Verifikasi"</div>
                            </div>
                        </div>

                        <!-- Liveness Indicators -->
                        <div id="indicators-popup-modal" class="liveness-indicators" style="display: none;">
                            <div id="blink-indicator-popup-modal" class="liveness-indicator">
                                <span>👁</span>
                                <span>Berkedip</span>
                            </div>
                            <div id="mouth-indicator-popup-modal" class="liveness-indicator">
                                <span>👄</span>
                                <span>Buka Mulut</span>
                            </div>
                        </div>

                        <!-- Status Text -->
                        <div id="status-popup-modal" class="mt-2 text-sm text-gray-600"></div>

                        <!-- Control Buttons -->
                        <div class="flex justify-center mt-3 space-x-4">
                            <button id="startLivenessBtn-popup-modal"
                                onclick="startLivenessVerification('popup-modal')"
                                class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                                Mulai Verifikasi
                            </button>
                            <button id="stopLivenessBtn-popup-modal"
                                onclick="stopLivenessVerification('popup-modal')"
                                class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700"
                                style="display: none;">
                                Berhenti
                            </button>
                        </div>
                    </div>

                    <!-- Results Container -->
                    <div id="results-popup-modal" class="hidden mt-4">
                        <p class="mb-2 font-medium">Foto yang akan dikirim:</p>
                        <div class="w-full mx-auto mb-4">
                            <img id="screenshotResult-popup-modal"
                                class="object-contain w-full h-auto border rounded max-h-48"
                                alt="Screenshot Wajah">
                        </div>


                        <div class="flex justify-end mt-4 space-x-2">
                            <button type="button" onclick="resetLiveness('popup-modal')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200">
                                Ambil Ulang
                            </button>
                            <button type="button" id="submitButton" data-modalid="popup-modal"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 disabled:opacity-50">
                                Simpan Absen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- popup moda keluar -->
    <div id="popup-modal-keluar" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full p-4">
            <div class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-700 md:p-5">
                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal-keluar">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 text-center md:p-5">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Anda Akan Melakukan Aksi Absen Pulang ?</h3>
                    <button id="submitButtonKeluar" data-modal-hide="popup-modal-keluar" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Ya, Yakin
                    </button>
                    <button data-modal-hide="popup-modal-keluar" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Main Content -->
    <section class="grid grid-cols-12">
        <div class="w-full col-span-12 px-5 pt-5">
            <div class="grid grid-cols-12 place-items-center">
                <div class="w-full col-span-12">
                    <?= $this->render('@backend/views/components/fragment/_time') ?>

                    <?php
                    $isShift = $dataJam['karyawan']['is_shift'] ?? null;
                    $manualShift = $manual_shift ?? null;
                    ?>


                    <?= $this->render('utils/_working_hours_info', [
                        'jamKerjaToday' => $jamKerjaToday,
                        'isShift' => $isShift
                    ])
                    ?>


                    <?php if ($isShift == 1 && $dataJam['today']): ?>
                        <div class="w-full ">
                            <p class="mt-2 -mb-3 text-xs text-center capitalize ">Lokasi Anda</p>
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


                        <!-- Warning Box -->
                        <div id="warningBox" class="flex items-center justify-between p-2 mx-4 mt-4 text-sm text-black bg-yellow-200 rounded-lg">
                            Pastikan alamat Anda sudah muncul di bagian berwarna biru di atas sebagai tanda bahwa lokasi Anda telah terdeteksi.
                            Setelah itu, pilih shift yang sesuai, lalu klik Absen Masuk.
                            Jika absensi tidak terkirim, silakan scroll ke bawah untuk melihat apakah terlambat atau terlalu jauh <button id="closeWarning" class="px-2 font-bold text-black hover:text-gray-700">×</button>
                        </div>


                    <?php endif; ?>

                    <br>

                    <?php if ($dataJam['today']) : ?>
                        <?php if (count($absensiToday) > 0): ?>
                            <?php if (empty($absensiToday[0]->jam_pulang)): ?>
                                <?php $formAbsen = ActiveForm::begin([
                                    'method' => 'post',
                                    'id' => 'my-form',
                                    'action' => ['home/absen-pulang']
                                ]) ?>
                                <div class="flex flex-col items-center justify-center space-y-3 lg:flex-row lg:space-y-0">
                                    <div class="grid place-items-center border border-[#D51405]/10 p-3 rounded-full">
                                        <button class="all-none border border-[#D51405]/50 p-3 rounded-full disabled:opacity-50"
                                            type="button"
                                            data-modal-target="popup-modal-keluar"
                                            data-modal-toggle="popup-modal-keluar"
                                            id="pulang-button">
                                            <div class="flex relative w-[225px] h-[225px] <?= $buttonStyles['pulang'] ?> shadow-2xl rounded-full">
                                                <?= Html::img('@root/images/icons/click.svg', [
                                                    'alt' => 'absen',
                                                    'class' => 'w-full h-full -mt-3 object-cover scale-[0.7]'
                                                ]) ?>
                                                <p class="absolute m-0 font-normal text-white -translate-x-1/2 bottom-5 left-1/2">
                                                    Absen Pulang
                                                </p>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                                <p class="my-5 text-xs text-center text-gray-500" id="message">
                                    Absen Pulang Aktif Hanya Saat Jam Pulang
                                </p>
                                <?php ActiveForm::end() ?>
                            <?php else: ?>
                                <div class="flex flex-col items-center justify-center space-y-3 lg:flex-row lg:space-y-0">
                                    <button class="all-none" type="button" disabled>
                                        <div class="flex relative w-[225px] h-[225px] <?= $buttonStyles['disabled'] ?> shadow-2xl rounded-full">
                                            <?= Html::img('@root/images/icons/click.svg', [
                                                'alt' => 'absen',
                                                'class' => 'w-full h-full -mt-3 object-cover scale-[0.7]'
                                            ]) ?>
                                            <p class="absolute m-0 font-normal text-white -translate-x-1/2 bottom-5 left-1/2">
                                                Selesai
                                            </p>
                                        </div>
                                    </button>
                                </div>
                            <?php endif ?>
                        <?php else: ?>
                            <?php $formAbsen = ActiveForm::begin([
                                'method' => 'post',
                                'id' => 'my-form',
                                'action' => ['home/absen-masuk']
                            ]) ?>
                            <?= $formAbsen->field($model, 'latitude')->hiddenInput(['class' => 'coordinate lat'])->label(false) ?>
                            <?= $formAbsen->field($model, 'longitude')->hiddenInput(['class' => 'coordinate lon'])->label(false) ?>
                            <?= $formAbsen->field($model, 'foto_masuk')->hiddenInput(['id' => 'faceData'])->label(false) ?>
                            <?= $formAbsen->field($model, 'liveness_passed')->hiddenInput(['id' => 'faceDescriptor'])->label(false) ?>

                            <?php if ($dataJam['karyawan']['is_shift'] && $manual_shift == 0): ?>
                                <?php

                                $jamKerjaKaryawan = $dataJam['karyawan']->jamKerja;

                                $shiftHariIniSemua = JadwalKerja::find()->select(['id_shift_kerja'])->where(['id_jam_kerja' => $jamKerjaKaryawan['id_jam_kerja'], 'nama_hari' => date('w')])->asArray()->asArray()->all();

                                // Ambil ID shift kerja dari hasil query pertama
                                $ids = array_column($shiftHariIniSemua, 'id_shift_kerja');

                                // Lalu ambil data lengkap ShiftKerja berdasarkan ID-ID tersebut
                                $dataShift = ShiftKerja::find()
                                    ->where(['id_shift_kerja' => $ids])
                                    ->asArray()
                                    ->all();

                                // Format pilihan untuk dropdown
                                $shiftOptions = [];
                                foreach ($dataShift as $shift) {
                                    $jamMasuk = substr($shift['jam_masuk'], 0, 5);
                                    $jamKeluar = substr($shift['jam_keluar'], 0, 5);
                                    $shiftOptions[$shift['id_shift_kerja']] = $shift['nama_shift'] . " ($jamMasuk - $jamKeluar)";
                                }
                                ?>

                                <div class="max-w-md mx-auto">
                                    <?= $formAbsen->field($model, 'id_shift')->dropDownList(
                                        $shiftOptions,
                                        [
                                            'prompt' => '-- Pilih Shift Kerja --',
                                            'class' => 'block w-full px-3 py-2 text-base border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm'
                                        ]
                                    )->label(false) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Tombol Absen Masuk -->
                            <div class="flex flex-col items-center justify-center space-y-3 lg:flex-row lg:space-y-0">
                                <div class="grid place-items-center border border-[#EB5A2B]/10 p-4 rounded-full">
                                    <button class="all-none border border-[#EB5A2B]/50 p-4 rounded-full"
                                        data-modal-target="popup-modal"
                                        data-modal-toggle="popup-modal"
                                        type="button">
                                        <div class="flex relative w-[225px] h-[225px] <?= $buttonStyles['masuk'] ?> shadow-2xl rounded-full">
                                            <?= Html::img('@root/images/icons/click.svg', [
                                                'alt' => 'absen',
                                                'class' => 'w-full h-full -mt-3 object-cover scale-[0.7]'
                                            ]) ?>
                                            <p class="absolute m-0 font-normal text-white -translate-x-1/2 bottom-5 left-1/2">
                                                Absen Masuk
                                            </p>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <?php ActiveForm::end() ?>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <!-- Mobile Action Buttons -->
            <div class="flex items-center justify-between pb-32">
                <?php if (count($absensiToday) > 0): ?>
                    <?php if (empty($absensiToday[0]->jam_pulang)): ?>
                        <div class="grid mt-5 place-items-center">
                            <a href="/panel/home/pulang-cepat">
                                <div class="grid place-items-center">
                                    <div class="<?= $iconButtonStyles ?>">
                                        <div class="grid w-8 h-8 font-black text-center text-white rounded-full place-items-center bg-rose-500">
                                            <span class="w-1 h-5 bg-white rounded-xl"></span>
                                        </div>
                                    </div>
                                    <p class="mt-2 font-medium text-center capitalize">Pulang Cepat</p>
                                </div>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="grid mt-5 place-items-center">
                            <a href="<?= $isPulangCepat ? '/panel/home/pulang-cepat' : '#' ?>">
                                <div class="grid place-items-center">
                                    <div class="<?= $iconButtonStyles ?>">
                                        <div class="grid w-8 h-8 font-black text-center text-white <?= $isPulangCepat ? 'bg-yellow-400' : 'bg-gray-400' ?> rounded-full place-items-center">
                                            <span class="w-1 h-5 bg-white rounded-xl"></span>
                                        </div>
                                    </div>
                                    <p class="mt-2 font-medium text-center capitalize">Pulang Cepat</p>
                                </div>
                            </a>
                        </div>
                    <?php endif ?>
                <?php else: ?>
                    <div class="grid mt-5 place-items-center">
                        <a href="/panel/home/create">
                            <div class="grid place-items-center">
                                <div class="<?= $iconButtonStyles ?>">
                                    <div class="grid w-8 h-8 font-black text-center text-white rounded-full place-items-center bg-rose-500">
                                        <span class="w-5 h-1 bg-white rounded-xl"></span>
                                    </div>
                                </div>
                                <p class="mt-2 font-medium text-center capitalize">Izin Tidak Absen</p>
                            </div>
                        </a>
                    </div>
                <?php endif ?>

                <?= $this->render('@backend/views/components/fragment/_terlambat', ['model' => $model]) ?>
                <?= $this->render('@backend/views/components/fragment/_terlalu_jauh', ['model' => $model, 'dataJam' => $dataJam, 'manual_shift' => $manual_shift]) ?>

                <div class="grid mt-5 place-items-center">
                    <?= Html::a(
                        '
                        <div class="grid place-items-center">
                            <div class="w-[60px] h-[60px] bg-orange-50 border border-gray rounded-full grid place-items-center">
                                <div class="font-black text-white w-8 h-8 text-center flex justify-center ps-1.5 items-start flex-col space-y-1 rounded-sm bg-orange-500">
                                    <span class="w-5 h-1 bg-white rounded-xl"></span>
                                    <span class="w-5 h-1 bg-white rounded-xl"></span>
                                    <span class="w-2 h-1 bg-white rounded-xl"></span>
                                </div>
                            </div>
                            <p class="mt-2 font-medium text-center capitalize">Lihat History</p>
                        </div>',
                        ['/home/view', 'id_user' => Yii::$app->user->identity->id]
                    ) ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden">
        <?= $this->render('@backend/views/components/_footer') ?>
    </div>
</section>



<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<?php
$dataToday = ArrayHelper::toArray($dataJam) ?? [];
$dataTodayJson = json_encode($dataToday, JSON_PRETTY_PRINT) ?? [];

$dataAtasanPenempatan = ArrayHelper::toArray($masterLokasi) ?? [];
$dataAtasanPenempatanJson = json_encode($dataAtasanPenempatan, JSON_PRETTY_PRINT) ?? [];

$manual_shift = json_encode($manual_shift, JSON_PRETTY_PRINT) ?? [];
?>

<?php
echo $this->render('utils/_script_face.php');
echo $this->render('utils/_script_timeandlocation.php', [
    'dataToday' => $dataToday,
    'dataTodayJson' => $dataTodayJson,
    'dataAtasanPenempatan' => $dataAtasanPenempatan,
    'dataAtasanPenempatanJson' => $dataAtasanPenempatanJson,
    'manual_shift' => $manual_shift,
]);
?>