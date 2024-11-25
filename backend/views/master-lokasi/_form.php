<?php

use backend\models\Perusahaan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\MasterLokasi $model */
/** @var yii\widgets\ActiveForm $form */
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="master-lokasi-form table-container">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(Perusahaan::find()->all(), 'nama_perusahaan', 'nama_perusahaan');
            echo $form->field($model, 'label')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Perusahaan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Perusahaan');
            ?>
        </div>

        <div class="col-12 col-md-6">
            <?= $form->field($model, 'alamat')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-12 ">
            <?= $form->field($model, 'radius')->textInput(['type' => 'number'])->label('Radius Absensi Yang Diperbolehkan') ?>
        </div>
        <div class="col-12 col-md-6">
            <?= $form->field($model, 'longtitude')->textInput(['readonly' => 'true', "id" => "lng"]) ?>
        </div>

        <div class="col-12 col-md-6">
            <?= $form->field($model, 'latitude')->textInput(['readonly' => 'true', 'id' => "lat"]) ?>
        </div>

    </div>

    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<div class="table-container table-responsive">
    <p class="text-danger fw-bold">Pilih Lokasi Kantor untuk mendapatkan koordinat</p>
    <?php
    echo '<div id="map" style="height: 400px;"></div>';
    ?>
</div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    var map = L.map('map').setView([<?= $model->latitude ?? -7.250445 ?>, <?= $model->longtitude ?? 112.768845 ?>], 13); // Koordinat default

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