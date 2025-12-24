<script>
    let manual_shift = <?= $manual_shift ?> ?? 0;
    let todayJson = <?= $dataTodayJson ?> ?? 0;
    let AtasanKaryawanJson = <?= $dataAtasanPenempatanJson ?> ?? 0;
    let globatLat = 0;
    let globatLong = 0;


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
            text: 'Gagal mendapatkan lokasi, pastikan izin lokasi diaktifkan!'
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

        const [jam_pulang, menit_pulang, detik_pulang] = jamPulangString.split(':').map(Number);
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
            closeModalFace('popup-modal');

            const alasanterlalujauh = document.querySelector('#alasanterlalujauh');
            const alasanTerlambat = document.querySelector('#alasanTerlambat');


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