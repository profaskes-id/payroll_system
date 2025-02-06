<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<!-- Tambahkan CSS untuk loading spinner -->
<style>
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<div class="px-5">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/home', 'title' => 'Lokasi']); ?>
</div>

<div class="relative flex justify-around items-center overflow-hidden">
    <!-- Tambahkan div untuk loading overlay -->
    <div id="loading-overlay" class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <div class="absolute left-1/2 -translate-x-1/2 bottom-5 bg-white w-[90%] rounded-xl z-50 p-5">
        <div class="flex justify-start space-x-5 items-start">
            <div>
                <div class="w-[70px] h-[70px] rounded-md bg-blue-400 grid place-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                        <path fill="white" d="M11.5 9A2.5 2.5 0 0 1 14 6.5c1.1 0 2.03.71 2.37 1.69c.08.26.13.53.13.81a2.5 2.5 0 0 1-2.5 2.5c-1.09 0-2-.69-2.36-1.66c-.09-.26-.14-.55-.14-.84M5 9c0 4.5 5.08 10.66 6 11.81L10 22S3 14.25 3 9c0-3.17 2.11-5.85 5-6.71C6.16 3.94 5 6.33 5 9m9-7c3.86 0 7 3.13 7 7c0 5.25-7 13-7 13S7 14.25 7 9c0-3.87 3.14-7 7-7m0 2c-2.76 0-5 2.24-5 5c0 1 0 3 5 9.71C19 12 19 10 19 9c0-2.76-2.24-5-5-5" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-muted" id="alamat"></p>
            </div>
        </div>
    </div>
    <div class="w-full" id="map" style="height: 80dvh !important; z-index: 2 !important"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    window.addEventListener('load', function() {
        const loadingOverlay = document.getElementById('loading-overlay');

        function showLoading() {
            loadingOverlay.style.display = 'flex';
        }

        function hideLoading() {
            loadingOverlay.style.display = 'none';
        }

        showLoading(); // Tampilkan loading saat mulai

        navigator.geolocation.watchPosition(function(position) {
                // console.info(position);
                // document.getElementById('latitude').textContent = position.coords.latitude.toFixed(10);
                // document.getElementById('longitude').textContent = position.coords.longitude.toFixed(10);
                dapatkanAlamat(position.coords.latitude.toFixed(10), position.coords.longitude.toFixed(10));


                let map = L.map('map').setView([position.coords.latitude.toFixed(10), position.coords.longitude.toFixed(10)], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
                    subdomains: ['a', 'b', 'c']
                }).addTo(map);

                let marker = L.marker([position.coords.latitude.toFixed(10), position.coords.longitude.toFixed(10)]).addTo(map);

                // Sembunyikan loading setelah peta dimuat
                map.whenReady(function() {
                    hideLoading();
                });
            },
            function(error) {
                console.log("Error: " + error.message);
                hideLoading(); // Sembunyikan loading jika terjadi error
            }, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            });


        function dapatkanAlamat(lat, lon) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                .then(response => response.json())
                .then(data => {
                    var alamatLengkap = data.display_name;

                    document.getElementById('alamat').textContent = alamatLengkap;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });
</script>