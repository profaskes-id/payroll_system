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

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

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

        <div>
            <?= $form->field($model, 'lokasi')->textInput(['maxlength' => true, 'placeholder' => 'Tentukan Lokasi', 'autocomplete' => 'off', 'value' => 'Rumah', 'class' => 'w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'])->label('Lokasi') ?>
        </div>

        <div>
            <?= $form->field($model, 'alamat')->textarea(['rows' => 3, 'class' => 'w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'])->label('Alamat') ?>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
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



        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <?= $form->field($model, 'longitude')->textInput(['disabled' => 'true', "id" => "lng", 'class' => 'w-full disabled:bg-slate-200 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'])->label('Longitude') ?>
            </div>

            <div>
                <?= $form->field($model, 'latitude')->textInput(['disabled' => 'true', 'id' => "lat", 'class' => 'w-full disabled:bg-slate-200 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'])->label('Latitude') ?>
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

</div>