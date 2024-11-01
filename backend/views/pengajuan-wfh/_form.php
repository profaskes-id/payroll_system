<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\helpers\StatusPengajuanHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanWfh $model */
/** @var yii\widgets\ActiveForm $form */
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="pengajuan-wfh-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">


        <div class="col-12">
            <?php
            $data = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Karyawan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Karyawan');
            ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'alasan')->textarea(['rows' => 3]) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'lokasi')->textInput(['maxlength' => true, 'placeholder' => 'Tentukan Lokasi', 'autocomplete' => 'off', 'value' => 'Rumah']) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'alamat')->textarea(['rows' => 3]) ?>
        </div>



        <div class="col-12 col-md-6">
            <?= $form->field($model, 'tanggal_mulai')->textInput(['type' => 'date', 'value' => $tglmulai ?? ""])->label('Tanggal Mulai'); ?>
        </div>

        <div class="col-12 col-md-6">
            <!-- Input Tanggal Selesai -->
            <?= $form->field($model, 'tanggal_selesai')->textInput(['type' => 'date', 'value' => $tglselesai ?? ''])->label('Tanggal Selesai'); ?>
        </div>




        <?php if (!$model->isNewRecord) : ?>
            <div class="col-12 ">
                <?= $form->field($model, 'catatan_admin')->textarea(['rows' => 2]) ?>
            </div>

        <?php endif ?>


        <div class="col-12 col-md-6">
            <?= $form->field($model, 'longitude')->textInput(['readonly' => 'true', "id" => "lng"]) ?>
        </div>

        <div class="col-12 col-md-6">
            <?= $form->field($model, 'latitude')->textInput(['readonly' => 'true', 'id' => "lat"]) ?>
        </div>



        <div class="col-12">
            <?php

            $data = \yii\helpers\ArrayHelper::map(StatusPengajuanHelper::getStatusPengajuan(), 'kode', 'nama_kode');
            echo $form->field($model, 'status')->radioList($data, [
                'item' => function ($index, $label, $name, $checked, $value) use ($model) {
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
            ])->label('Status');
            ?>
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


<?php if ($model->isNewRecord) : ?>


    <div class="table-container table-responsive">
        <p class="text-danger fw-bold">Pilih Lokasi WFH untuk mendapatkan koordinat</p>
        <?php
        echo '<div id="map" style="height: 400px;"></div>';
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