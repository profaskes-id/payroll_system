<?php

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
    [
        'id' => 'popup-modal-keluar',
        'title' => 'Anda Akan Melakukan Aksi Absen Pulang ?',
        'confirm_id' => 'submitButtonKeluar',
        'face_input' => 'foto_pulang'
    ]
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


<section class="min-h-[90dvh] relative overflow-x-hidden z-50">
    <!-- Confirmation Modals -->
    <?php foreach ($modals as $modal): ?>
        <div id="<?= $modal['id'] ?>" tabindex="-1"
            class="fixed inset-0 z-50 flex items-center justify-center hidden w-full h-full bg-black bg-opacity-50">
            <div class="relative w-full max-w-md p-4">
                <div class="<?= $modalStyles['content'] ?>">
                    <!-- Close Button -->
                    <button type="button" onclick="closeModal('<?= $modal['id'] ?>')"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>

                    <div class="p-4 text-center md:p-5">
                        <h3 class="mb-5 text-lg font-normal text-gray-500"><?= $modal['title'] ?></h3>

                        <!-- Webcam Container -->
                        <!-- Webcam Container -->
                        <div id="camera-container-<?= $modal['id'] ?>">
                            <div id="camera-<?= $modal['id'] ?>" class="w-full h-64 mx-auto mb-4 bg-gray-200"></div>
                            <div class="flex justify-center space-x-4">
                                <button onclick="takeSnapshot('<?= $modal['id'] ?>')"
                                    class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                                    Ambil Foto
                                </button>
                                <button onclick="stopCamera('<?= $modal['id'] ?>')"
                                    class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">
                                    Tutup Kamera
                                </button>
                            </div>
                        </div>

                        <!-- Results Container -->
                        <div id="results-<?= $modal['id'] ?>" class="hidden">
                            <p class="mb-2">Hasil Foto:</p>
                            <div id="snapshotResult-<?= $modal['id'] ?>" class="object-cover w-[100%] mb-4 bg-gray-200 aspect-[4/3]"></div>

                            <input type="hidden" id="faceData-<?= $modal['id'] ?>" name="<?= $modal['face_input'] ?>">

                            <div class="flex justify-end">
                                <button type="button" onclick="resetCamera('<?= $modal['id'] ?>')"
                                    class="px-4 py-2 mr-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                                    Ulangi
                                </button>
                                <button type="button" id="<?= $modal['confirm_id']; ?>" data-modalid="<?= $modal['id'] ?>" class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Pulang Notification Modal -->
    <div id="modal-pulang" class="hidden w-[80vw] md:w-[40vw] p-5 absolute left-1/2 -translate-x-1/2 top-1/2 z-50 -translate-y-1/2 rounded-xl border-red-400 border bg-white h-[150px]">
        <div id="close-modal-pulang" class="absolute -top-2 px-2.5 py-1 text-white -translate-x-6 bg-red-500 rounded-full cursor-pointer -right-10">X</div>
        <h1 class="text-xl font-bold capitalize">jam kerja anda telah selesai</h1>
        <p class="font-normal capitalize">segera lngkapi absensi pulang anda, dengan click tombol absensi pulang</p>
        <div class="flex flex-col items-center justify-around mb-5 space-y-3 lg:flex-row lg:space-y-0">
            <a href="pengajuan/lebur" class="px-5 py-2 font-bold text-white bg-red-500 rounded-lg">Ajukan Lembur</a>
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

                    <?php if ($jamKerjaToday || $manualShift == 1): ?>
                        <?= $this->render('utils/_working_hours_info', [
                            'jamKerjaToday' => $jamKerjaToday
                        ]) ?>
                    <?php elseif ($isShift == 1 && $manualShift == 0): ?>
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

                    <?php if ($dataJam['today'] || $manualShift == 0) : ?>
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
                                            disabled data-modal-target="popup-modal-keluar"
                                            data-modal-toggle="popup-modal-keluar"
                                            type="button"
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
                            <?= $formAbsen->field($model, 'foto_masuk')->hiddenInput(['id' => 'foto_masuk' , 'class' => 'foto_fr'] )->label(false) ?>
                            <?php if ($dataJam['karyawan']['is_shift'] && $manual_shift == 0): ?>
                                <?php
                                $dataShift = ShiftKerja::find()->asArray()->all();
                                $shiftOptions = [];
                                foreach ($dataShift as $shift) {
                                    $jamMasuk = substr($shift['jam_masuk'], 0, 5);
                                    $jamKeluar = substr($shift['jam_keluar'], 0, 5);
                                    $shiftOptions[$shift['id_shift_kerja']] = $shift['nama_shift'] . " ($jamMasuk - $jamKeluar)";
                                }
                                ?>
                                <div class="max-w-md mx-auto">
                                    <?= $formAbsen->field($model, 'id_shift')->radioList($shiftOptions, [
                                        'item' => function ($index, $label, $name, $checked, $value) {
                                            return "
                                            <div class='inline-block w-1/2 px-1 mb-2'>
                                                <input type='radio' name='{$name}' id='shift-{$value}' value='{$value}' class='hidden peer' " . ($checked ? 'checked' : '') . ">
                                                <label for='shift-{$value}' class='block p-3 text-sm font-medium text-center text-gray-600 transition bg-white border border-gray-300 rounded-lg shadow-sm cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:border-blue-400 hover:bg-blue-100'>
                                                    {$label}
                                                </label>
                                            </div>";
                                        },
                                        'class' => 'flex flex-wrap -mx-1'
                                    ])->label(false) ?>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
$dataToday = ArrayHelper::toArray($dataJam) ?? [];
$dataTodayJson = json_encode($dataToday, JSON_PRETTY_PRINT) ?? [];
$dataAtasanPenempatan = ArrayHelper::toArray($masterLokasi) ?? [];
$dataAtasanPenempatanJson = json_encode($dataAtasanPenempatan, JSON_PRETTY_PRINT) ?? [];
$manual_shift = json_encode($manual_shift, JSON_PRETTY_PRINT) ?? [];
?>

<script>
    // Global Variables
    let manual_shift = <?= $manual_shift ?> ?? 0;
    let todayJson = <?= $dataTodayJson ?> ?? 0;
    let AtasanKaryawanJson = <?= $dataAtasanPenempatanJson ?> ?? 0;
    let globatLat = 0;
    let globatLong = 0;
    
    
    // Variabel global untuk koordinat
    let currentLat = 0;
    let currentLon = 0;
    let wajah_fr = '';
    
    // DOM Elements
    const jam_masuk = todayJson?.today?.jam_masuk;
    const max_telat = todayJson?.karyawan?.max_terlambat;
    const form = document.getElementById('my-form');
    const submitButton = document.getElementById('submitButton');
    const submitButtonKeluar = document.getElementById('submitButtonKeluar');
    const pulang_button = document.querySelector('#pulang-button');
    const warningBox = document.getElementById('warningBox');
    const closeWarning = document.getElementById('closeWarning');
    const modalPulang = document.getElementById('modal-pulang');
    const closeModalPulang = document.getElementById('close-modal-pulang');

    // Functions
    
    
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
                    console.log('Location obtained:', globatLat, globatLong);
                },
                (error) => {
                    console.error('Geolocation error:', error);
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
    console.log(data.display_name);
    if (elemenAlamat) {
      elemenAlamat.textContent = data.display_name;
    }
  })
  .catch(error => console.error('Error:', error));
}

    function cekWaktu() {
        const sekarang = new Date();
        const jam_pulang = todayJson?.today?.jam_keluar;
        let jam, menit, detik;

        if (manual_shift == 1) {
            [jam, menit, detik] = jam_pulang.split(':').map(Number);
        } else {
            [jam, menit, detik] = "00:00:00".split(':').map(Number);
        }

        const waktuPulang = new Date(sekarang);
        waktuPulang.setHours(jam, menit, detik, 0);

        if (sekarang >= waktuPulang) {
            const message = document.querySelector('#message');
            if (message) {
                message.classList.add('hidden');
                if (pulang_button) pulang_button.disabled = false;
            }
        }
    }

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

            wajah_fr = faceData;

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
                    if (alasanterlalujauh) alasanterlalujauh.classList.toggle('hidden');
                }
            } else if (isTerlambat(jam, menit, detik, batasJam, maximalTelatbatasMenit)) {
                if (jam === batasJam && menit <= maximalTelatbatasMenit) {
                    if (distance.toFixed(0) <= AtasanKaryawanJson.radius) {
                        form.submit();
                    } else {
                        if (alasanterlalujauh) alasanterlalujauh.classList.toggle('hidden');
                    }
                } else {
                    if (alasanTerlambat) alasanTerlambat.classList.toggle('hidden');
                }
            } else {
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
    image_format: 'jpeg',
    jpeg_quality: 80,
    flip_horiz: true,
    width: 640 / 2,   // lebar
    height: 480 /2,  // tinggi (640/480 = 4/3)
    constraints: {
        facingMode: 'user',
        width: { ideal: 640 /2 },  // ideal width
        height: { ideal: 480/2 }  // ideal height (sesuai aspect ratio 4:3)
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
                snapshotResult.innerHTML = '<img src="' + data_uri + '" class="aspect-[4/3]"/>';
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
        console.info({
            modal,
            bg
        });
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
            document.querySelectorAll('.foto_fr').forEach(el => el.value = wajah_fr);
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
                text: 'Terjadi kesalahan: ' + error.message,
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