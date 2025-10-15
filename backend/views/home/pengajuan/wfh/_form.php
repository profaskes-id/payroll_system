<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin(); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="relative min-h-[85dvh] z-40">

    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">alasan work from home </label>
        <?= $form->field($model, 'alasan')->textarea(['required' => true, 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>

    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">tanggal mulai</label>
        <?= $form->field($model, 'tanggal_mulai')->textInput(['required' => true, 'type' => 'date', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>
    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">tanggal selesai</label>
        <?= $form->field($model, 'tanggal_selesai')->textInput(['required' => true, 'type' => 'date', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>


    <p class="block mb-2 text-sm font-medium text-gray-900 capitalize"> lokasi anda saat ini</p>
    <div class="flex items-center justify-between space-x-2">
        <div class="mb-3 ">
            <label class="block mb-2 text-xs text-gray-900 capitalize">Longitude</label>
            <?= $form->field($model, 'longitude')->textInput(['required' => true, 'readonly' => true, 'class' => 'w-[42dvw] bg-gray-200 bg-gray-200 border-gray-300 h-[20px] text-xs rounded-md ', "id" => "lng"])->label(false) ?>
        </div>

        <div class="mb-">
            <label class="block mb-2 text-xs text-gray-900 capitalize">Latitude</label>
            <?= $form->field($model, 'latitude')->textInput(['required' => true, 'readonly' => true, 'class' => 'w-[42dvw] bg-gray-200 bg-gray-200 border-gray-300 h-[20px] text-xs rounded-md ', 'id' => "lat"])->label(false) ?>
        </div>

    </div>


    <div class="flex items-center justify-between w-full space-x-3">
        <div class="">
            <button type="button" class=" text-xs text-white bg-[#4285F4] hover:bg-[#4285F4]/90 focus:ring-4 focus:outline-none focus:ring-[#4285F4]/50 font-medium rounded  p-2 text-center  w-full flex items-center dark:focus:ring-[#4285F4]/55 me-2 mb-2" data-drawer-target="drawer-bottom-example" data-drawer-show="drawer-bottom-example" data-drawer-placement="bottom" aria-controls="drawer-bottom-example">
                Atur Secara Manual
            </button>
        </div>
        <div class="">
            <button id="use-current-location" type="button" class="text-xs text-white bg-[#050708] hover:bg-[#050708]/90 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-medium  rounded p-2 -translate-y-1">
                Gunakan Lokasi Saat ini
            </button>
        </div>
    </div>



    <div class="absolute bottom-0 left-0 right-0 ">
        <div class="">
            <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>



<div id="drawer-bottom-example" class="fixed bottom-0 left-0 right-0 z-40 w-full p-4 overflow-y-auto transition-transform translate-y-full bg-white dark:bg-gray-800 " tabindex="-1" aria-labelledby="drawer-bottom-label" aria-hidden="true" aria-modal="false">
    <h5 id="drawer-bottom-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400"><svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>Lokasi WFH</h5>
    <button type="button" data-drawer-hide="drawer-bottom-example" aria-controls="drawer-bottom-example" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
        <span class="sr-only">Close menu</span>
    </button>

    <div class="table-container table-responsive">
        <p class="mb-2 text-xs ">Pilih Lokasi WFH untuk mendapatkan koordinat</p>
        <?php
        echo '<div id="map" style="height: 70vh;"></div>';
        ?>
    </div>
</div>





<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>



<script>
    const useCurrentLocation = document.querySelector('#use-current-location');
    useCurrentLocation.addEventListener('click', function() {
        document.getElementById('lat').value = "";
        document.getElementById('lng').value = "";
        Swal.fire({
            confirmButtonColor: "#3085d6",
            text: "Pastikan anda Telah Mengaktifkan Lokasi Anda!"
        });
        navigator.geolocation.watchPosition(function(position) {

            console.info(position);
            document.getElementById('lat').value = position.coords.latitude.toFixed(10);
            document.getElementById('lng').value = position.coords.longitude.toFixed(10);
        });
    });
</script>

<script>
    var map = L.map('map').setView([<?= $model->latitude ?? -7.250445 ?>, <?= $model->longtitude ?? 112.768845 ?>], 4); // Koordinat default

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    var marker;

    map.on('click', function(e) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker(e.latlng).addTo(map);
        document.getElementById('lat').value = e.latlng.lat;
        document.getElementById('lng').value = e.latlng.lng;
    });

    document.getElementById('saveCoordinates').addEventListener('click', function() {
        var lat = document.getElementById('lat').value;
        var lng = document.getElementById('lng').value;

        fetch('<?= \yii\helpers\Url::to(['site/save-coordinates']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng
                }),
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    confirmButtonColor: "#3085d6",
                    text: `${data.message}`,
                });
            })
            .catch(error => console.error('Error:', error));
    });
</script>

</div>