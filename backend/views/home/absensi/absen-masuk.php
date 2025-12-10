<?php

use backend\models\JadwalKerja;
use backend\models\JadwalShift;
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>







<section class="min-h-[90dvh] relative overflow-x-hidden z-50">
    <!-- Confirmation Modals -->
    <div id="popup-modal" tabindex="-1"
        class="fixed inset-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-100/50">
        <div class="relative w-full max-w-md p-4">
            <div class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-700 md:p-5">
                <!-- Close Button -->
                <button type="button" onclick="closeModal('popup-modal')"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>

                <div class="p-3 text-center">
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Anda Akan Melakukan Aksi Absen Masuk ?</h3>

                    <!-- Webcam Container -->
                    <div id="camera-container-popup-modal" class="relative">
                        <div id="camera-popup-modal" class="mx-auto mb-4 overflow-hidden bg-black rounded-lg"></div>
                        <div id="liveness-instruction-popup-modal" class="absolute left-0 right-0 text-center top-2">
                            <div class="inline-block px-3 py-1 text-sm text-white bg-black rounded-full bg-opacity-70">
                                Tekan "Mulai Verifikasi"
                            </div>
                        </div>
                        <div class="flex justify-center mt-2 space-x-4">
                            <button id="startLivenessBtn-popup-modal"
                                onclick="runLiveness('popup-modal')"
                                class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700"
                                disabled>
                                Mulai Verifikasi
                            </button>
                        </div>
                    </div>

                    <!-- Results Container -->
                    <div id="results-popup-modal" class="hidden">
                        <p class="mb-2">Hasil Foto:</p>
                        <div id="snapshotResult-popup-modal" class="w-full mx-auto mb-4 bg-gray-200 aspect-video "></div>

                        <input type="hidden" id="faceData-popup-modal" name="foto_masuk">

                        <div class="flex justify-end mt-4">
                            <button type="button" onclick="resetCamera('popup-modal')"
                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700">
                                Ulangi
                            </button>
                            <button type="button" id="submitButton" data-modalid="popup-modal"
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Simpan
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
                            <?= $formAbsen->field($model, 'foto_masuk')->hiddenInput(['id' => 'foto_masuk', 'class' => 'foto_fr'])->label(false) ?>

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


<!-- JavaScript Libraries -->

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php
$dataToday = ArrayHelper::toArray($dataJam) ?? [];
$dataTodayJson = json_encode($dataToday, JSON_PRETTY_PRINT) ?? [];
$dataAtasanPenempatan = ArrayHelper::toArray($masterLokasi) ?? [];
$dataAtasanPenempatanJson = json_encode($dataAtasanPenempatan, JSON_PRETTY_PRINT) ?? [];
$manual_shift = json_encode($manual_shift, JSON_PRETTY_PRINT) ?? [];
?>


<!-- script 2 -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    const MODEL_URL = '<?= Yii::getAlias('@root'); ?>/panel/models';
    let isModelsLoaded = false;
    let faceRecognitionNetLoaded = false;
    let livenessPassed = false;
    let currentModalId = 'popup-modal';
    let webcamAttached = false;
    let overlayCanvas = null;
    let overlayCtx = null;
    let detectionInterval = null;

    // Load Models
    async function loadModels() {
        if (isModelsLoaded) return;
        try {
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            isModelsLoaded = true;
            console.log('Models loaded');
        } catch (err) {
            alert('Gagal load model: ' + err.message);
        }
    }

    async function loadFaceRecognitionNet() {
        if (faceRecognitionNetLoaded) return;
        try {
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            faceRecognitionNetLoaded = true;
        } catch (err) {
            alert('Gagal load faceRecognitionNet: ' + err.message);
        }
    }

    // Webcam + Overlay
    function startCamera(modalId) {
        currentModalId = modalId;
        const cameraDiv = document.getElementById('camera-' + modalId);
        const overlay = document.getElementById('overlay-' + modalId);

        if (!cameraDiv || !overlay) return;

        Webcam.set({
            width: 300, // ← UBAH: 400 jadi 300
            height: 225, // ← UBAH: 380 jadi 225 (300 * 3/4 = 225)
            image_format: 'jpeg',
            jpeg_quality: 80,
            flip_horiz: false,
            mirror: false,
            constraints: {
                width: {
                    min: 240,
                    ideal: 300, // ← IDEAL 300px
                    max: 400
                },
                height: {
                    min: 180,
                    ideal: 225, // ← IDEAL 225px (4:3)
                    max: 300
                }
                // Tetap TANPA aspectRatio
            }
        });

        // UBAH KE object-fit: contain
        cameraDiv.style.width = '300px';
        cameraDiv.style.height = '300px';
        cameraDiv.style.objectFit = 'contain'; // UBAH: contain
        cameraDiv.style.backgroundColor = 'black'; // Background hitam
        cameraDiv.style.borderRadius = '8px';
        cameraDiv.style.overflow = 'hidden';

        overlay.style.width = '300px';
        overlay.style.height = '300px';
        overlay.style.objectFit = 'contain'; // UBAH: contain
        overlay.style.backgroundColor = 'black'; // Background hitam
        overlay.style.borderRadius = '8px';
        overlay.style.position = 'absolute';
        overlay.style.top = '0';
        overlay.style.left = '0';

        overlayCanvas = document.getElementById('overlay-' + modalId);
        if (overlayCanvas) {
            overlayCtx = overlayCanvas.getContext('2d');
            overlayCanvas.width = 300;
            overlayCanvas.height = 300;
            console.log('Overlay context initialized');
        }

        Webcam.attach('#camera-' + modalId);
        webcamAttached = true;

        const inst = document.getElementById('liveness-instruction-' + modalId);
        const btn = document.getElementById('startLivenessBtn-' + modalId);
        inst.innerHTML = '<div class="inline-block px-3 py-1 text-sm text-white bg-yellow-600 rounded-full animate-pulse">Kamera menyala...</div>';
        btn.disabled = true;

        setTimeout(() => {
            if (webcamAttached) {
                startFaceOverlay();
                inst.innerHTML = '<div class="inline-block px-3 py-1 text-sm text-white bg-green-600 rounded-full">Siap! Tekan Mulai</div>';
                btn.disabled = false;
            }
        }, 1500);
    }

    function stopCamera() {
        if (webcamAttached) {
            Webcam.reset();
            webcamAttached = false;
        }
        stopFaceOverlay();
    }

    function startFaceOverlay() {
        if (!isModelsLoaded || !overlayCtx) return;
        stopFaceOverlay();
        detectionInterval = setInterval(async () => {
            Webcam.snap(async (data_uri) => {
                try {
                    const img = new Image();
                    img.src = data_uri;
                    await new Promise(r => img.onload = r);

                    const detections = await faceapi.detectAllFaces(
                        img,
                        // UBAH INI ↓↓↓
                        new faceapi.TinyFaceDetectorOptions({
                            inputSize: 416, // Naikkan dari 320 ke 416
                            scoreThreshold: 0.3 // Turunkan dari 0.4 ke 0.3
                        })
                    ).withFaceLandmarks();

                    const resized = faceapi.resizeResults(detections, {
                        width: 300,
                        height: 300
                    });
                    overlayCtx.clearRect(0, 0, 300, 300);

                    // faceapi.draw.drawDetections(overlayCanvas, resized);
                    // faceapi.draw.drawFaceLandmarks(overlayCanvas, resized);

                } catch (err) {
                    console.warn('Overlay error:', err);
                }
            });
        }, 120);
    }

    function stopFaceOverlay() {
        if (detectionInterval) clearInterval(detectionInterval);
        if (overlayCtx) overlayCtx.clearRect(0, 0, 300, 300);
    }

    async function detectHeadPose() {
        return new Promise(resolve => {
            Webcam.snap(async (data_uri) => {
                try {
                    const img = new Image();
                    img.src = data_uri;
                    await new Promise(r => img.onload = r);

                    const detection = await faceapi
                        .detectSingleFace(img,
                            // UBAH INI ↓↓↓
                            new faceapi.TinyFaceDetectorOptions({
                                inputSize: 416, // SAMA di sini
                                scoreThreshold: 0.3 // SAMA di sini
                            }))
                        .withFaceLandmarks();

                    if (!detection) return resolve(null);

                    const l = detection.landmarks;
                    const leftEye = l.getLeftEye()[0];
                    const rightEye = l.getRightEye()[3];
                    const nose = l.getNose()[3];

                    const eyeDist = rightEye.x - leftEye.x;
                    if (eyeDist < 40) return resolve(null);

                    const yaw = ((rightEye.x - nose.x) - (nose.x - leftEye.x)) / eyeDist * 100;
                    resolve(yaw);

                } catch (err) {
                    resolve(null);
                }
            });
        });
    }
    // FIXED: threshold lebih rendah
    async function waitForPose(dir, timeout = 10000) {
        return new Promise(resolve => {
            const start = Date.now();
            const check = async () => {
                if (Date.now() - start > timeout) return resolve(false);

                const yaw = await detectHeadPose();
                if (yaw === null) return setTimeout(check, 150);

                // LEBIH LONGGAR
                if ((dir === 'left' && yaw > 6) ||
                    (dir === 'right' && yaw < -6) ||
                    (dir === 'center' && Math.abs(yaw) < 10)) {
                    resolve(true);
                } else {
                    setTimeout(check, 150);
                }
            };
            check();
        });
    }

    window.runLiveness = async function(modalId) {
        currentModalId = modalId;
        const btn = document.getElementById('startLivenessBtn-' + modalId);
        const inst = document.getElementById('liveness-instruction-' + modalId);
        btn.disabled = true;

        const steps = [{
                text: 'Tatap lurus',
                dir: null,
                timeout: 2000
            },
            {
                text: 'Lihat ke KANAN',
                dir: 'left',
                timeout: 5000
            },
            {
                text: 'Lihat ke kiri',
                dir: 'right',
                timeout: 5000
            },
            {
                text: 'Kembali ke tengah',
                dir: 'center',
                timeout: 5500
            }
        ];

        for (let s of steps) {
            if (!s.dir) {
                inst.innerHTML = `<div class="inline-block px-3 py-1 text-sm text-white bg-blue-600 rounded-full">${s.text}</div>`;
                await new Promise(r => setTimeout(r, s.timeout));
                continue;
            }
            inst.innerHTML = `<div class="inline-block px-3 py-1 text-sm text-white bg-orange-600 rounded-full">${s.text}</div>`;
            const ok = await waitForPose(s.dir, s.timeout);
            if (!ok) {
                inst.innerHTML = `<div class="inline-block px-3 py-1 text-sm text-white bg-red-600 rounded-full">Gagal: ${s.text}</div>`;
                setTimeout(() => btn.disabled = false, 2000);
                return;
            }
        }

        inst.innerHTML = `<div class="inline-block px-3 py-1 text-sm text-white bg-green-600 rounded-full">Liveness lolos!</div>`;
        livenessPassed = true;
        await computeAndSaveDescriptor();
    };
    async function computeAndSaveDescriptor() {
        try {
            await new Promise(r => setTimeout(r, 500));
            await loadFaceRecognitionNet();

            return new Promise(resolve => {
                Webcam.snap(async (data_uri) => {
                    try {
                        const img = new Image();
                        img.onload = async () => {
                            const canvas = document.createElement('canvas');
                            canvas.width = img.width;
                            canvas.height = img.height;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0);

                            // TAMBAH: Standardize image size jika terlalu kecil
                            if (canvas.width < 400 || canvas.height < 400) {
                                console.log('Image too small, upscaling...');
                                const tempCanvas = document.createElement('canvas');
                                tempCanvas.width = Math.max(canvas.width, 500);
                                tempCanvas.height = Math.max(canvas.height, 500);
                                const tempCtx = tempCanvas.getContext('2d');
                                tempCtx.drawImage(canvas, 0, 0, tempCanvas.width, tempCanvas.height);
                                canvas.width = tempCanvas.width;
                                canvas.height = tempCanvas.height;
                                ctx.drawImage(tempCanvas, 0, 0);
                            }

                            console.log('SCRIPT 2 - Final image size:', canvas.width, 'x', canvas.height);

                            const detection = await faceapi.detectSingleFace(canvas,
                                    // UBAH INI ↓↓↓ (PALING PENTING)
                                    new faceapi.TinyFaceDetectorOptions({
                                        inputSize: 416, // Naikkan ke 416
                                        scoreThreshold: 0.3 // Turunkan ke 0.3
                                    }))
                                .withFaceLandmarks()
                                .withFaceDescriptor();

                            if (!detection) throw new Error('Wajah tidak terdeteksi');

                            const descriptorString = Array.from(detection.descriptor).join(',');

                            console.log('%c[SCRIPT 2] Optimized detection', 'color: blue; font-weight: bold;');
                            console.log('Descriptor mean:',
                                Array.from(detection.descriptor).reduce((a, b) => a + b, 0) / 128);

                            document.getElementById('faceData-' + currentModalId).value = descriptorString;
                            takeVerifiedPhoto(data_uri);
                            resolve();
                        };
                        img.src = data_uri;
                    } catch (err) {
                        console.error('Error:', err);
                        alert('Gagal: ' + err.message);
                        resolve();
                    }
                });
            });
        } catch (err) {
            console.error('Error:', err);
            alert('Gagal: ' + err.message);
        }
    }


    // FUNGSI STANDARD PREPROCESSING (taruh di sini)
    function standardizeImageForFaceDetection(img) {
        const targetSize = 500;

        const canvas = document.createElement('canvas');
        const scale = Math.min(targetSize / img.width, targetSize / img.height);
        const width = Math.round(img.width * scale);
        const height = Math.round(img.height * scale);

        canvas.width = width;
        canvas.height = height;

        const ctx = canvas.getContext('2d');

        // Enhance image quality
        ctx.filter = 'contrast(1.1) brightness(1.05) saturate(1.1)';
        ctx.drawImage(img, 0, 0, width, height);

        console.log(`Image standardized: ${img.width}x${img.height} → ${width}x${height}`);
        return canvas;
    }

    // OPTIONAL: Aligned face descriptor (jika masih rendah)
    async function getOptimizedFaceDescriptor(imageElement) {
        try {
            // Step 1: Standardize image
            const standardizedCanvas = standardizeImageForFaceDetection(imageElement);

            // Step 2: Detect with optimized settings
            const detection = await faceapi
                .detectSingleFace(standardizedCanvas,
                    new faceapi.TinyFaceDetectorOptions({
                        inputSize: 416,
                        scoreThreshold: 0.3
                    }))
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) throw new Error('Wajah tidak terdeteksi');

            console.log('Optimized descriptor generated');
            return detection.descriptor;

        } catch (error) {
            console.error('Error in getOptimizedFaceDescriptor:', error);
            throw error;
        }
    }

    // Di takeVerifiedPhoto() - Script 2:
    function takeVerifiedPhoto(data_uri) {
        const result = document.getElementById('snapshotResult-' + currentModalId);
        const img = new Image();

        img.onload = function() {
            // Canvas untuk contain (bukan crop)
            const canvas = document.createElement('canvas');
            canvas.width = 300;
            canvas.height = 300;
            const ctx = canvas.getContext('2d');

            // Hitung scaling untuk contain
            const scale = Math.min(300 / img.width, 300 / img.height);
            const scaledWidth = img.width * scale;
            const scaledHeight = img.height * scale;
            const offsetX = (300 - scaledWidth) / 2;
            const offsetY = (300 - scaledHeight) / 2;

            // Gambar dengan contain
            ctx.fillStyle = 'black';
            ctx.fillRect(0, 0, 300, 300);
            ctx.drawImage(img, offsetX, offsetY, scaledWidth, scaledHeight);

            const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.8);

            result.innerHTML = `
            <div style="width: 300px; height: 300px; overflow: hidden; border-radius: 8px; margin: 0 auto; background: black;">
                <img src="${croppedDataUrl}" style="width: 300px !important; height: 300px !important; aspect-ratio: 1 / 1;  object-fit: contain !important;" />
            </div>`;
        };
        img.src = data_uri;

        document.getElementById('camera-container-' + currentModalId).classList.add('hidden');
        document.getElementById('results-' + currentModalId).classList.remove('hidden');
    }

    function resetCameraView(modalId) {
        const results = document.getElementById('results-' + modalId);
        const snapshot = document.getElementById('snapshotResult-' + modalId);
        const input = document.getElementById('faceData-' + modalId);
        const inst = document.getElementById('liveness-instruction-' + modalId);
        const btn = document.getElementById('startLivenessBtn-' + modalId);

        results?.classList.add('hidden');
        snapshot && (snapshot.innerHTML = '');
        input && (input.value = '');
        inst && (inst.innerHTML = '<div class="inline-block px-3 py-1 text-sm text-white bg-black rounded-full bg-opacity-70">Tekan "Mulai Verifikasi"</div>');
        btn && (btn.disabled = false);
        livenessPassed = false;
    }

    // DOM Events
    document.addEventListener('DOMContentLoaded', () => {
        loadModels();

        document.querySelector('[data-modal-target="popup-modal"]').addEventListener('click', () => {
            document.getElementById('popup-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            resetCameraView('popup-modal');
            startCamera('popup-modal');
        });

        document.getElementById('submitButton').addEventListener('click', () => {
            const faceData = document.getElementById('faceData-popup-modal')?.value;
            if (!livenessPassed || !faceData) {
                alert('Liveness belum lolos atau foto belum diambil!');
                return;
            }
            document.querySelectorAll('.foto_fr').forEach(el => el.value = faceData);
            stopCamera();
            document.getElementById('popup-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('my-form').submit();
        });

        window.resetCamera = () => {
            resetCameraView(currentModalId);
            startCamera(currentModalId);
        };
    });


    // FUNGSI BARU UNTUK KEDUA SCRIPT: faceAlignment.js
    async function getAlignedFaceDescriptor(imageElement, options = {}) {
        try {
            // Default options
            const opts = {
                inputSize: 320,
                scoreThreshold: 0.4,
                alignFace: true, // Enable alignment
                ...options
            };

            // Buat canvas dari image
            const canvas = document.createElement('canvas');
            canvas.width = imageElement.width || imageElement.videoWidth || 640;
            canvas.height = imageElement.height || imageElement.videoHeight || 480;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(imageElement, 0, 0, canvas.width, canvas.height);

            // Deteksi wajah dengan landmarks
            const detection = await faceapi
                .detectSingleFace(canvas, new faceapi.TinyFaceDetectorOptions({
                    inputSize: opts.inputSize,
                    scoreThreshold: opts.scoreThreshold
                }))
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) {
                throw new Error('Wajah tidak terdeteksi');
            }

            // OPTIONAL: Face alignment (jika diperlukan)
            if (opts.alignFace && detection.landmarks) {
                const alignedCanvas = await alignFace(canvas, detection.landmarks);
                // Re-detect pada aligned face
                const alignedDetection = await faceapi
                    .detectSingleFace(alignedCanvas, new faceapi.TinyFaceDetectorOptions({
                        inputSize: opts.inputSize,
                        scoreThreshold: opts.scoreThreshold
                    }))
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (alignedDetection) {
                    console.log('Face alignment applied');
                    return alignedDetection.descriptor;
                }
            }

            return detection.descriptor;
        } catch (error) {
            console.error('Error in getAlignedFaceDescriptor:', error);
            throw error;
        }
    }

    // Fungsi alignment sederhana
    async function alignFace(canvas, landmarks) {
        const points = landmarks.positions;

        // Ambil eye points
        const leftEye = points[36]; // Left eye corner
        const rightEye = points[45]; // Right eye corner

        // Hitung angle rotasi
        const eyeCenterX = (leftEye.x + rightEye.x) / 2;
        const eyeCenterY = (leftEye.y + rightEye.y) / 2;
        const angle = Math.atan2(rightEye.y - leftEye.y, rightEye.x - leftEye.x) * (180 / Math.PI);

        // Buat canvas untuk aligned face
        const alignedCanvas = document.createElement('canvas');
        alignedCanvas.width = 300; // Standard size
        alignedCanvas.height = 300;
        const alignedCtx = alignedCanvas.getContext('2d'); // ← PERBAIKAN: alignedCanvas bukan alignedCtx

        // Simpan state context
        alignedCtx.save();

        // Pindahkan origin ke center
        alignedCtx.translate(150, 150);
        alignedCtx.rotate(-angle * Math.PI / 180);

        // Gambar dengan rotation
        alignedCtx.drawImage(canvas,
            eyeCenterX - 150,
            eyeCenterY - 150,
            300, 300,
            -150, -150,
            300, 300
        );

        alignedCtx.restore();

        return alignedCanvas;
    }
</script>


<script>
    let manual_shift = <?= $manual_shift ?> ?? 0;
    let todayJson = <?= $dataTodayJson ?> ?? 0;
    let AtasanKaryawanJson = <?= $dataAtasanPenempatanJson ?> ?? 0;
    let globatLat = 0;
    let globatLong = 0;


    // Variabel global untuk koordinat
    let currentLat = 0;
    let currentLon = 0;
    let liveness_passed_fr = '';

    // DOM Elements
    const jam_masuk = todayJson?.today?.jam_masuk;
    const max_telat = todayJson?.karyawan?.max_terlambat;
    const form = document.getElementById('my-form');
    const submitButton = document.getElementById('submitButton');
    const submitButtonKeluar = document.getElementById('submitButtonKeluar');
    const pulang_button = document.querySelector('#pulang-button');
    const warningBox = document.getElementById('warningBox');
    const closeWarning = document.getElementById('closeWarning');

    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;
    const message = document.querySelector('#message');

    if (manual_shift == 1) {
        if (pulang_button) pulang_button.disabled = true;
    }



    const updateCoordinates = function(position) {

        currentLat = position.coords.latitude.toFixed(10);
        currentLon = position.coords.longitude.toFixed(10);

        // Update semua input koordinat di semua form
        document.querySelectorAll('.coordinate.lat').forEach(el => el.value = currentLat);
        document.querySelectorAll('.coordinate.lon').forEach(el => el.value = currentLon);

        globatLat = currentLat;
        globatLong = currentLon;
        dapatkanAlamat(currentLat, currentLon);
    };


    navigator.geolocation.watchPosition(updateCoordinates, function(error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            confirmButtonColor: "#3085d6",
            text: 'Gagal mendapatkan lokasi: ' + error.message
        });
    }, {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
    });

    function checkLocationAccess() {

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    // Lokasi berhasil diambil
                    globatLat = position.coords.latitude;
                    globatLong = position.coords.longitude;
                    // console.log('Location obtained:', globatLat, globatLong);
                },
                (error) => {
                    // console.error('Geolocation error:', error);
                    Swal.fire({
                        confirmButtonColor: "#3085d6",
                        text: "Izinkan Browser Untuk Mengakses Lokasi Anda!"
                    });
                }
            );
        } else {
            Swal.fire({
                confirmButtonColor: "#3085d6",
                text: "Browser Anda tidak mendukung geolokasi!"
            });
        }
    }

    function setupGeolocationWatcher() {
        const latitudeInput = document.querySelector('.latitude');
        const longitudeInput = document.querySelector('.longitude');

        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                (position) => {
                    const lat = position.coords.latitude.toFixed(10);
                    const lon = position.coords.longitude.toFixed(10);

                    document.querySelectorAll('.coordinate.lat').forEach(el => el.value = lat);
                    document.querySelectorAll('.coordinate.lon').forEach(el => el.value = lon);
                    dapatkanAlamat(lat, lng);
                },
                (error) => {
                    console.error('Error watching location:', error);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }
    }

    function dapatkanAlamat(lat, lon) {
        const elemenAlamat = document.getElementById("alamat");
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`, {

            })
            .then(response => response.json())

            .then(data => {
                // console.log(data.display_name);
                if (elemenAlamat) {
                    elemenAlamat.textContent = data.display_name;
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Jika bukan manual shift, langsung aktifkan tombol

    function cekWaktu() {
        // Jika bukan manual shift, tidak perlu cek waktu
        if (manual_shift == 0) {
            if (message) {
                message.classList.add('hidden');
            }
        }

        const sekarang = new Date();
        const jam_sekarang = sekarang.getHours();
        const menit_sekarang = sekarang.getMinutes();

        // Jam 5 sore = 17:00
        const jamPulangString = todayJson?.today?.jam_keluar;

        // Split string "17:00:00" menjadi jam, menit, detik
        const [jam_pulang, menit_pulang, detik_pulang] = jamPulangString.split(':').map(Number);

        // Cek apakah sekarang sudah melewati jam 5 sore
        // Perbandingan: (jam_sekarang > 17) ATAU (jam_sekarang == 17 DAN menit_sekarang >= 0)
        if (jam_sekarang > jam_pulang ||
            (jam_sekarang === jam_pulang && menit_sekarang >= menit_pulang)) {

            const message = document.querySelector('#message');
            if (message) {
                message.classList.add('hidden');
            }
            if (pulang_button) {
                pulang_button.disabled = false;
            }
        }
    }

    setInterval(cekWaktu, 1000);
    cekWaktu(); // Panggil sekali saat load



    // Form Submission Handlers
    if (submitButtonKeluar) {
        submitButtonKeluar.addEventListener('click', () => form.submit());
    }

    if (submitButton) {
        submitButton.addEventListener('click', function(e) {

            let jenis = e.target.getAttribute('data-modalid') ?? '';
            const modalId = e.target.getAttribute('data-modalid');
            const faceData = document.getElementById('faceData-' + modalId)?.value;

            if (!faceData) {
                alert('Silakan ambil foto terlebih dahulu!');
                return;
            }

            liveness_passed_fr = faceData;

            // Update semua input koordinat di semua form
            document.querySelectorAll('.foto_fr').forEach(el => el.value = faceData);

            const alasanTerlambat = document.querySelector('#alasanTerlambat');
            const alasanterlalujauh = document.querySelector('#alasanterlalujauh');

            stopCamera(jenis);
            closeModal(jenis);

            if (manual_shift == 0) {
                // Check if Leaflet is available
                if (typeof L === 'undefined') {
                    console.error('Leaflet library not loaded!');
                    alert('Error: Map library not loaded');
                    return;
                }

                const from = L.latLng(globatLat, globatLong);
                const to = L.latLng(AtasanKaryawanJson.latitude, AtasanKaryawanJson.longtitude);
                const distance = from.distanceTo(to);

                if (distance.toFixed(0) <= AtasanKaryawanJson.radius) {
                    form.submit();
                } else {
                    if (alasanterlalujauh) alasanterlalujauh.classList.toggle('hidden');
                }
                return;
            }

            // For non-manual shift, check time and distance
            const sekarang = new Date();
            const jam = sekarang.getHours();
            const menit = sekarang.getMinutes();
            const detik = sekarang.getSeconds();

            const [batasJam, batasMenit, batasDetik] = jam_masuk.split(':').map(Number);
            const [maximalTelatJam, maximalTelatbatasMenit, maximalTelatbatasDetik] = max_telat.split(':').map(Number);

            const from = L.latLng(globatLat, globatLong);
            const to = L.latLng(AtasanKaryawanJson.latitude, AtasanKaryawanJson.longtitude);
            const distance = from.distanceTo(to);

            if (isSebelumBatas(jam, menit, detik, batasJam, batasMenit, batasDetik)) {
                if (distance.toFixed(0) <= AtasanKaryawanJson.radius) {
                    form.submit();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Jarak Terlalu Jauh',
                        text: 'Anda berada di luar radius yang diizinkan. Silakan scroll ke bawah untuk mengisi alasan.',
                        confirmButtonText: 'Mengerti'
                    });
                    if (alasanterlalujauh) alasanterlalujauh.classList.toggle('hidden');
                }
            } else if (isTerlambat(jam, menit, detik, batasJam, maximalTelatbatasMenit)) {
                if (jam === batasJam && menit <= maximalTelatbatasMenit) {
                    if (distance.toFixed(0) <= AtasanKaryawanJson.radius) {
                        form.submit();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Jarak Terlalu Jauh',
                            text: 'Anda berada di luar radius yang diizinkan. Silakan scroll ke bawah untuk mengisi alasan.',
                            confirmButtonText: 'Mengerti'
                        });
                        if (alasanterlalujauh) alasanterlalujauh.classList.toggle('hidden');
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Anda Terlambat',
                        text: 'Waktu kehadiran Anda melebihi batas yang ditentukan. Silakan scroll ke bawah untuk mengisi alasan keterlambatan.',
                        confirmButtonText: 'Mengerti'
                    });
                    if (alasanTerlambat) alasanTerlambat.classList.toggle('hidden');
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Anda Terlambat',
                    text: 'Waktu kehadiran Anda melebihi batas yang ditentukan. Silakan scroll ke bawah untuk mengisi alasan keterlambatan.',
                    confirmButtonText: 'Mengerti'
                });
                if (alasanTerlambat) alasanTerlambat.classList.toggle('hidden');
            }
        });
    }

    // Helper Functions
    function isSebelumBatas(jam, menit, detik, batasJam, batasMenit, batasDetik) {
        if (jam < batasJam) return true;
        if (jam === batasJam && menit < batasMenit) return true;
        if (jam === batasJam && menit === batasMenit && detik < batasDetik) return true;
        return false;
    }

    function isTerlambat(jam, menit, detik, batasJam, maximalTelatbatasMenit) {
        if (jam > batasJam) return true;
        if (jam == batasJam && menit < maximalTelatbatasMenit) return true;
        return false;
    }

    // Camera Handling Functions
    const cameraStates = {};

    function initModal(modalId) {
        cameraStates[modalId] = {
            isCameraOn: false,
            hasSnapshot: false
        };
    }

    function startCamera(modalId) {
        if (!cameraStates[modalId]) initModal(modalId);
        if (cameraStates[modalId].isCameraOn) return;

        // Check if Webcam is available
        if (typeof Webcam === 'undefined') {
            console.error('Webcam library not loaded!');
            alert('Error: Webcam library not loaded');
            return;
        }

        Webcam.set({
            width: 300, // ← UBAH: 400 jadi 300
            height: 225, // ← UBAH: 380 jadi 225 (300 * 3/4 = 225)
            image_format: 'jpeg',
            jpeg_quality: 80,
            flip_horiz: false,
            mirror: false,
            constraints: {
                width: {
                    min: 240,
                    ideal: 300, // ← IDEAL 300px
                    max: 400
                },
                height: {
                    min: 180,
                    ideal: 225, // ← IDEAL 225px (4:3)
                    max: 300
                }
                // Tetap TANPA aspectRatio
            }
        });
        Webcam.attach('#camera-' + modalId);
        cameraStates[modalId].isCameraOn = true;

        const cameraContainer = document.getElementById('camera-container-' + modalId);
        const resultsContainer = document.getElementById('results-' + modalId);

        if (cameraContainer) cameraContainer.classList.remove('hidden');
        if (resultsContainer) resultsContainer.classList.add('hidden');
    }

    function takeSnapshot(modalId) {
        if (!cameraStates[modalId]?.isCameraOn) {
            alert('Kamera belum diaktifkan!');
            return;
        }

        Webcam.snap(function(data_uri) {
            cameraStates[modalId].hasSnapshot = true;

            const cameraContainer = document.getElementById('camera-container-' + modalId);
            const snapshotResult = document.getElementById('snapshotResult-' + modalId);
            const faceDataInput = document.getElementById('faceData-' + modalId);
            const resultsContainer = document.getElementById('results-' + modalId);

            if (cameraContainer) cameraContainer.classList.add('hidden');
            if (snapshotResult) {
                snapshotResult.innerHTML = '<img src="' + data_uri + '" class="mx-auto mb-4 w-[300px] h-[300px] object-contain aspect-square "/>';
            }
            if (faceDataInput) faceDataInput.value = data_uri;
            if (resultsContainer) resultsContainer.classList.remove('hidden');
        });
    }

    function resetCamera(modalId) {
        stopCamera(modalId);

        const resultsContainer = document.getElementById('results-' + modalId);
        const cameraContainer = document.getElementById('camera-container-' + modalId);

        if (resultsContainer) resultsContainer.classList.add('hidden');
        if (cameraContainer) cameraContainer.classList.remove('hidden');

        startCamera(modalId);
    }

    function stopCamera(modalId) {
        if (cameraStates[modalId]?.isCameraOn) {
            Webcam.reset('#camera-' + modalId);
            cameraStates[modalId].isCameraOn = false;
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const bg = document.querySelector('.z-40');

        if (modal) {
            // Webcam.reset('#camera-' + modalId);
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        if (bg) {
            bg.classList.add('hidden');
        }
    }

    function handleSave(modalId, confirmFunction) {
        if (!cameraStates[modalId]?.hasSnapshot) {
            alert('Silakan ambil foto terlebih dahulu!');
            return;
        }

        if (typeof window[confirmFunction] === 'function') {
            window[confirmFunction]();
        } else {
            console.error('Function ' + confirmFunction + ' tidak ditemukan!');
        }

        // closeModal(modalId);
    }


    initModal('popup-modal'); // Ganti dengan ID modal Anda

    // Tambahkan event untuk tombol buka kamera
    const btnBukaKamera = document.querySelector('[data-modal-toggle="popup-modal"]');
    if (btnBukaKamera) {
        btnBukaKamera.addEventListener('click', function() {
            startCamera('popup-modal'); // Ganti dengan ID modal Anda
        });
    }

    // Event untuk tombol ambil foto
    const btnAmbilFoto = document.getElementById('btn-ambil-foto');
    if (btnAmbilFoto) {
        btnAmbilFoto.addEventListener('click', function() {
            takeSnapshot('popup-modal'); // Ganti dengan ID modal Anda
            // Update semua input koordinat di semua form
            document.querySelectorAll('.foto_fr').forEach(el => el.value = liveness_passed_fr);
        });
    }



    // Ganti dengan salah satu dari berikut:
    // 1. Untuk DOMContentLoaded (saat HTML selesai diparse)
    document.addEventListener('DOMContentLoaded', function() {
        let currentLat, currentLon;

        navigator.geolocation.watchPosition(function(position) {
            currentLat = position.coords.latitude.toFixed(10);
            currentLon = position.coords.longitude.toFixed(10);

            // Update form utama
            document.querySelectorAll('.latitude, .longitude').forEach(el => {
                el.value = el.classList.contains('latitude') ? currentLat : currentLon;
            });

            if (position) {
                dapatkanAlamat(globatLat, globatLong);
            }

        }, function(error) {
            // Menangani kesalahan dan menampilkan SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan: Lokasi anda tidak terdeteksi',
                confirmButtonColor: "#3085d6", // Warna biru biasa
                confirmButtonText: 'OK',
                footer: '<p>Pastikan izin lokasi diaktifkan</p>'
            });

        }, {
            enableHighAccuracy: true,
            timeout: 10000, // Ubah timeout menjadi 10 detik
            maximumAge: 0
        });
    });
</script>

<style>
    .swal2-confirm {
        background-color: #3085d6 !important;
        color: white !important;
        border: none !important;
        box-shadow: none !important;
    }
</style>