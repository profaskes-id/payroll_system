<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\helpers\StatusPengajuanHelper;
use backend\models\Karyawan;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanWfh $model */
/** @var yii\widgets\ActiveForm $form */
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="relative p-2 bg-white rounded-lg shadow-md md:p-6">

    <?php $form = ActiveForm::begin(); ?>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 ">

        <div class="">
            <p class="block mb-2 text-sm font-medium text-gray-900 capitalize ">Karyawan</p>
            <?php
            $data = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->dropDownList(
                $data,
                [
                    'disabled' => true,
                    'prompt' => 'Pilih Karyawan ...',
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '
                ]
            )->label(false);
            ?>
        </div>

        <div>
            <?= $form->field($model, 'alasan')->textarea(['rows' => 3, 'class' => 'w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'])->label('Alasan') ?>
        </div>

        <div class="grid grid-cols-1 col-span-2 gap-6 md:grid-cols-2">
            <div>
                <?= $form->field($model, 'tanggal_mulai')->textInput([
                    'type' => 'date',
                    'value' => isset($tanggal_awal) ? $tanggal_awal : null,
                    'class' => 'w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'
                ])->label('Tanggal Mulai') ?>
            </div>

            <div>
                <?= $form->field($model, 'tanggal_selesai')->textInput([
                    'type' => 'date',
                    'value' => isset($tanggal_akhir) ? $tanggal_akhir : null,
                    'class' => 'w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'
                ])->label('Tanggal Selesai') ?>
            </div>
        </div>
        <div >
            <?= $form->field($model, 'lokasi')->textInput(['maxlength' => true, 'placeholder' => 'Tentukan Lokasi', 'autocomplete' => 'off', 'value' => 'Rumah', 'class' => 'w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'])->label('Lokasi') ?>
        </div>





        <div class="col-span-2 space-y-4">
            <!-- Judul Section -->
            <div class="pb-2 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Koordinat Lokasi</h3>
                <p class="mt-1 text-sm text-gray-500">Koordinat GPS yang terdeteksi otomatis</p>
            </div>

            <!-- Input Fields dengan Map Preview -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <?= $form->field($model, 'latitude', [
                        'options' => ['class' => 'space-y-1'],
                        'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700']
                    ])->textInput([
                        'disabled' => true,
                        'id' => "lat",
                        'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-500',
                        'placeholder' => 'Loading...'
                    ]) ?>
                </div>

                <div>
                    <?= $form->field($model, 'longitude', [
                        'options' => ['class' => 'space-y-1'],
                        'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700']
                    ])->textInput([
                        'disabled' => true,
                        'id' => "lng",
                        'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-500',
                        'placeholder' => 'Loading...'
                    ]) ?>
                </div>
            </div>

            <!-- Map Preview -->
            <div class="mt-4">
                <div id="map-preview" class="w-full h-48 overflow-hidden border border-gray-300 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-center h-full text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Pratinjau peta akan muncul di sini</span>
                    </div>
                </div>
                <div class="flex justify-end mt-2">
                    <button type="button" id="refresh-location" class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-600 rounded-md bg-blue-50 hover:bg-blue-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="text-xs "> click jika anda ingin memperbarui lokasi</span> <span></span>
                    </button>
                </div>
            </div>

            <!-- Open in Maps Button (akan muncul setelah koordinat terisi) -->
            <div id="open-maps-container" class="hidden">
                <a id="open-maps-link" href="#" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    Buka di Google Maps
                </a>
            </div>
        </div>



        <div class="col-12">

            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');


            echo $form->field($model, 'status')->radioList($data, [
                'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                    // Tentukan apakah radio button untuk value 1 harus checked

                    if ($model->isNewRecord) {
                        $isChecked = $value == 1 ? true : $checked;
                    } else {
                        $isChecked = $checked;
                    }

                    return Html::radio($name, $isChecked, [
                        'value' => $value,
                        'label' => $label,
                        'labelOptions' => ['class' => 'radio-label mr-4'],
                    ]);
                },
            ])->label('Status Pengajuan');
            ?>
        </div>


        <!-- Catatan Admin Field (hanya jika bukan record baru) -->
        <?php if (!$model->isNewRecord): ?>
            <div class="">
                <?= $form->field($model, 'catatan_admin')->textarea([
                    'rows' => 2,
                    'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
                ]) ?>
            </div>
        <?php endif; ?>

    </div>

    <div class="mt-6">
        <button class="px-4 py-2 font-bold text-white transition duration-200 bg-blue-500 rounded-lg hover:bg-blue-700" type="submit">
            Save
        </button>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php if ($model->isNewRecord) : ?>
    <div class="mt-6 table-container table-responsive">
        <p class="font-bold text-red-600">Pilih Lokasi WFH untuk mendapatkan koordinat</p>
        <?php
        echo '<div id="map" style="height: 400px;" class="mt-4 rounded-lg shadow-md"></div>';
        ?>
    </div>
<?php endif ?>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    var map = L.map('map').setView([<?= $model['latitude'] ?? -7.250445 ?>, <?= $model['longitude'] ?? 112.768845 ?>], <?= $model['latitude'] ? 13 : 4 ?>); // Koordinat default

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
                alert(data.message);
            })
            .catch(error => console.error('Error:', error));
    });
</script>


<script>
    // Contoh JavaScript untuk menangani pembaruan lokasi
    document.getElementById('refresh-location').addEventListener('click', function() {
        // Implementasi geolocation API di sini
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('lat').value = position.coords.latitude;
                document.getElementById('lng').value = position.coords.longitude;

                // Update map preview
                updateMapPreview(position.coords.latitude, position.coords.longitude);

                // Show open maps button
                document.getElementById('open-maps-container').classList.remove('hidden');
                document.getElementById('open-maps-link').href = `https://www.google.com/maps?q=${position.coords.latitude},${position.coords.longitude}`;
            });
        } else {
            alert("Geolocation tidak didukung oleh browser Anda");
        }
    });

    function updateMapPreview(lat, lng) {
        // Implementasi untuk menampilkan peta kecil
        // Bisa menggunakan Google Maps Embed API atau library lain
        const mapPreview = document.getElementById('map-preview');
        mapPreview.innerHTML = `<iframe class="w-full h-full" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
        src="https://maps.google.com/maps?q=${lat},${lng}&z=15&output=embed"></iframe>`;
    }
</script>
</div>