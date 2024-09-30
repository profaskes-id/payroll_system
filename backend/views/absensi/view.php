<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */

$this->title = 'Absensi ' .  $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Absensi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="absensi-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_absensi' => $model->id_absensi], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_absensi' => $model->id_absensi], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],

                [
                    'label' => 'TanggalAbsen',
                    // 'format' => 'datetime',
                    'value' => function ($model) {
                        return date('d-M-Y', strtotime($model->tanggal));
                    }
                ],
                'jam_masuk',
                'jam_pulang',
                [
                    'label' => 'Status Hadir',
                    'value' => function ($model) {
                        return $model->statusHadir->nama_kode;
                    }
                ],

            ],
        ]) ?>
    </div>


</div>
<div class='table-container'>
    <p>Lokasi Karyawan Mengisi Absen</p>
    <?php
    echo '<div id="map" style="height: 400px;"></div>';
    ?>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<?php
$latitude = $model->latitude;
$longitude = $model->longitude;

$this->registerJs("
    $(document).ready(function() {
        // Inisialisasi peta
        let map = L.map('map').setView([$latitude, $longitude], 15);
        
        // Tambahkan tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href=\"https://www.openstreetmap.org/\">OpenStreetMap</a>',
            subdomains: ['a', 'b', 'c']
        }).addTo(map);
        
        // Tambahkan marker
        L.marker([$latitude, $longitude]).addTo(map);
    });
");
?>