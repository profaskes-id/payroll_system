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

// CSS Variables

$modals = [
    [
        'id' => 'popup-modal',
        'title' => 'Anda Akan Melakukan Aksi Absen Masuk ?',
        'confirm_id' => 'submitButton'
    ],
    [
        'id' => 'popup-modal-keluar',
        'title' => 'Anda Akan Melakukan Aksi Absen Pulang ?',
        'confirm_id' => 'submitButtonKeluar'
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

<section class="min-h-[90dvh] relative overflow-x-hidden">
    <!-- Confirmation Modals -->

    <?php foreach ($modals as $modal): ?>
        <div id="<?= $modal['id'] ?>" tabindex="-1" class="<?= $modalStyles['container'] ?>">
            <div class="relative w-full max-w-md max-h-full p-4">
                <div class="<?= $modalStyles['content'] ?>">
                    <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="<?= $modal['id'] ?>">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 text-center md:p-5">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400"><?= $modal['title'] ?></h3>
                        <button id="<?= $modal['confirm_id'] ?>" data-modal-hide="<?= $modal['id'] ?>" type="button" class="<?= $modalStyles['button']['confirm'] ?>">
                            Ya, Yakin
                        </button>
                        <button data-modal-hide="<?= $modal['id'] ?>" type="button" class="<?= $modalStyles['button']['cancel'] ?>">Batalkan</button>
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
                        <p class="mt-10 text-center text-gray-500">
                            Shift anda akan direkap otomatis sesuai jam absensi anda
                        </p>
                    <?php endif; ?>





                    <br><br>


                    <?php if ($dataJam['today'] || $manual_shift == 0) : ?>


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
                            <?= $formAbsen->field($model, 'latitude')->hiddenInput(['class' => 'latitude'])->label(false) ?>
                            <?= $formAbsen->field($model, 'longitude')->hiddenInput(['class' => 'longitude'])->label(false) ?>

                            <?php if ($dataJam['karyawan']['is_shift'] && $manual_shift == 0): ?>
                                <?= $formAbsen->field($model, 'id_shift')->radioList([
                                    1 => 'Shift 1 (06:00 - 14:00)',
                                    2 => 'Shift 2 (14:00 - 22:00)',
                                    3 => 'Shift 3 (22:00 - 06:00)'
                                ], [
                                    'item' => function ($index, $label, $name, $checked, $value) {
                                        return "<div class='flex items-center mb-4'>
            <input id='shift-main-{$value}' type='radio' name='{$name}' value='{$value}' class='w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 shift-radio focus:ring-blue-500'>
            <label for='shift-main-{$value}' class='text-sm font-medium text-gray-900 ms-2'>{$label}</label>
        </div>";
                                    }
                                ])->label(false) ?>

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
                    )
                    ?>
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
$redirectUrl = Yii::getAlias('@web');
?>
<script>
    function checkLocationAccess() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {},
                function(error) {
                    Swal.fire({
                        confirmButtonColor: "#3085d6",
                        text: "Izinkan Browser Untuk Mengakses Lokasi Anda!"
                    });
                }
            );
        } else {
            Swal.fire({
                confirmButtonColor: "#3085d6",
                text: "Izinkan Browser Untuk Mengakses Lokasi Anda!"
            });
        }
    }

    checkLocationAccess();
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ketika modal terlalu jauh akan ditampilkan
        document.querySelector('[data-modal-toggle="popup-modal-terlalujauh"]').addEventListener('click', function() {
            // Cari radio button yang dipilih di form utama
            const selectedShift = document.querySelector('#my-form .shift-radio:checked');

            if (selectedShift) {
                const shiftValue = selectedShift.value;
                // Set nilai yang sama di form terlalu jauh
                document.querySelector(`#my-form-terlalujauh input.shift-radio[value="${shiftValue}"]`).checked = true;
            }
        });
    });
</script>


<?php
$dataToday = ArrayHelper::toArray($dataJam);
$dataTodayJson = json_encode($dataToday, JSON_PRETTY_PRINT);
$dataAtasanPenempatan = ArrayHelper::toArray($masterLokasi);
$dataAtasanPenempatanJson = json_encode($dataAtasanPenempatan, JSON_PRETTY_PRINT);
$manual_shift = json_encode($manual_shift, JSON_PRETTY_PRINT);
?>
<script>
    let manual_shift = <?= $manual_shift ?>;
    let todayJson = <?= $dataTodayJson ?>;
    let AtasanKaryawanJson = <?= $dataAtasanPenempatanJson ?>;

    jam_masuk = todayJson?.today?.jam_masuk;
    max_telat = todayJson?.karyawan?.max_terlambat;
    let form = document.getElementById('my-form');
    let submitButton = document.getElementById('submitButton');
    let submitButtonKeluar = document.getElementById('submitButtonKeluar');
    let submitButtonLembur = document.getElementById('submitButtonLembur');


    window.addEventListener('load', function() {
        let latitudeBig = document.querySelector('.latitude');
        let longitudeBig = document.querySelector('.longitude');
        let Latismall = document.querySelector('.lati');
        let Longismall = document.querySelector('.longi');
        navigator.geolocation.watchPosition(function(position) {
            latitudeBig.value = position.coords.latitude.toFixed(10);
            longitudeBig.value = position.coords.longitude.toFixed(10);
            Latismall.value = position.coords.latitude.toFixed(10);
            Longismall.value = position.coords.longitude.toFixed(10);

            globatLat = position.coords.latitude.toFixed(10);
            globatLong = position.coords.longitude.toFixed(10);

            if (position) {
                dapatkanAlamat(globatLat, globatLong);
            }

        }, function(error) {
            // Menangani kesalahan dan menampilkan SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan: ' + error.message,
                confirmButtonColor: "#3085d6",
                footer: '<a href="">Mengapa ini terjadi?</a>'
            });
        }, {
            enableHighAccuracy: true,
            timeout: 10000, // Ubah timeout menjadi 10 detik
            maximumAge: 0
        });
    });

    function dapatkanAlamat(lat, lon) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
            .then(response => response.json())
            .then(data => {
                var alamatLengkap = data.display_name;
                var elemenAlamat = document.getElementById('alamat');
                if (elemenAlamat) {
                    elemenAlamat.textContent = alamatLengkap;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }


    submitButtonKeluar.addEventListener('click', function() {
        form.submit();
    });


    let globatLat = 0;
    let globatLong = 0;


    submitButton.addEventListener('click', function() {
        const alasanTerlambat = document.querySelector('#alasanTerlambat');
        const alasanterlalujauh = document.querySelector('#alasanterlalujauh');
        if (manual_shift == 0) {
            // Cek jarak saja, tidak perlu cek jam
            let from = L.latLng(globatLat, globatLong);
            let to = L.latLng(AtasanKaryawanJson.latitude, AtasanKaryawanJson.longtitude);
            let distance = from.distanceTo(to);

            if (distance.toFixed(0) <= AtasanKaryawanJson.radius) {
                form.submit();
            } else {
                alasanterlalujauh.classList.toggle('hidden');
            }

            return; // selesai, keluar dari event handler
        }

        // Lanjut ke pengecekan waktu jika manual_shift != 0
        function getWaktuSaatIni() {
            const sekarang = new Date();
            return {
                jam: sekarang.getHours(),
                menit: sekarang.getMinutes(),
                detik: sekarang.getSeconds()
            };
        }

        const {
            jam,
            menit,
            detik
        } = getWaktuSaatIni();

        const [batasJam, batasMenit, batasDetik] = jam_masuk.split(':').map(Number);
        const [maximalTelatJam, maximalTelatbatasMenit, maximalTelatbatasDetik] = max_telat.split(':').map(Number);

        function isSebelumBatas(jam, menit, detik) {
            if (jam < batasJam) return true;
            if (jam === batasJam && menit < batasMenit) return true;
            if (jam === batasJam && menit === batasMenit && detik < batasDetik) return true;
            return false;
        }

        function isTerlambat(jam, menit, detik) {
            if (jam > batasJam) return true;
            if (jam == batasJam && menit < maximalTelatbatasMenit) return true;
            return false;
        }


        let from = L.latLng(globatLat, globatLong);
        let to = L.latLng(AtasanKaryawanJson.latitude, AtasanKaryawanJson.longtitude);
        let distance = from.distanceTo(to);

        if (isSebelumBatas(jam, menit, detik)) {
            if (distance.toFixed(0) <= AtasanKaryawanJson.radius) {
                form.submit();
            } else {
                alasanterlalujauh.classList.toggle('hidden');
                return;
            }
        } else if (isTerlambat(jam, menit, detik)) {
            if (jam === batasJam && menit <= maximalTelatbatasMenit) {
                if (distance.toFixed(0) <= AtasanKaryawanJson.radius) {
                    form.submit();
                } else {
                    alasanterlalujauh.classList.toggle('hidden');
                }
            } else {
                // terlambat diluar masa tenggang
                alasanTerlambat.classList.toggle('hidden');
            }
        } else {
            // terlambat diluar masa tenggang
            alasanTerlambat.classList.toggle('hidden');
        }
    });
</script>


<script>
    const pulang_button = document.querySelector('#pulang-button');
    const jam_pulang = todayJson?.today?.jam_keluar;

    // Fungsi untuk mengaktifkan tombol jika waktu saat ini telah melewati jam pulang
    function cekWaktu(currentTime) {
        // Jika currentTime tidak diberikan, gunakan waktu saat ini
        const sekarang = currentTime ? new Date(currentTime) : new Date();

        if (jam_pulang) {

            // Mengambil jam, menit, dan detik dari waktu jam pulang
            const [jam, menit, detik] = jam_pulang.split(':').map(Number);


            // Membuat objek Date untuk waktu jam pulang
            const waktuPulang = new Date(sekarang - sekarang.getTimezoneOffset() * 60 * 1000);
            waktuPulang.setHours(jam, menit, detik, 0);

            // Mengecek apakah waktu saat ini sudah melewati jam pulang
            if (sekarang >= waktuPulang) {
                const message = document.querySelector('#message');
                if (message) {
                    message.classList.add('hidden');
                    pulang_button.disabled = false;
                }
            }
        }
    }


    setInterval(cekWaktu, 1000);
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var closeButton = document.getElementById('close-modal-pulang');
        var modal = document.getElementById('modal-pulang');

        closeButton.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'none';
        });
    });
</script>