<?php

use backend\models\Tanggal;
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
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_absensi' => $model->id_absensi], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_absensi' => $model->id_absensi], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Apakah Anda Yakin Ingin Menghapus Item Ini ?',
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
                    'label' => 'Tanggal Absen',
                    // 'format' => 'datetime',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal);
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
                [
                    'label' => 'Lampiran',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->lampiran != null) {
                            return Html::a('preview', Yii::getAlias('@root') . '/panel/' . $model->lampiran, ['target' => '_blank']);
                        }
                        return '<p>Belum Di Set<p>';
                    }
                ],
                [
                    'label' => 'Apakah Lembur',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->is_lembur == 1 ? "Iya" : "Tidak";
                    }
                ],
                [
                    'label' => 'Apakah WFH',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model['is_wfh'] == 1 ? "Iya" : "Tidak";
                    }
                ],
                [
                    'label' => 'Alasan Terlambat',
                    'format' => 'raw',
                    'value' => function ($model) {

                        return $model->alasan_terlambat ?? '-';
                    }
                ],
                [
                    'label' => 'Keterangan',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->keterangan ?? '-';
                    }
                ],
                [
                    'label' => 'Alasan Isi Absens terlalu jauh',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->alasan_terlalu_jauh ?? '-';
                    }
                ],
                [
                    'label' => 'jarak ke lokasi',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<span id="distance"></span>';
                    }
                ]

            ],
        ]) ?>
    </div>


</div>


<div class="table-container table-responsive">
    <p>Lokasi Karyawan Mengisi Absen</p>
    <?php
    echo '<div id="map" style="height: 400px;"></div>';
    ?>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<?php
$latitude_now = $model->latitude;
$longitude_now = $model->longitude;
$latitude_penempatan = strval($alamat->latitude);
$longitude_penempatan = strval($alamat->longtitude);
// $latitude_now = "-0.350190";
// $longitude_now = "100.372248";

// Debugging: Pastikan nilai-nilai ini benar
echo "<script>console.log('Now: {$latitude_now}, {$longitude_now}, Penempatan: {$latitude_penempatan}, {$longitude_penempatan}');</script>";

$this->registerJs("
        // Inisialisasi peta
        let map = L.map('map').setView([$latitude_now, $longitude_now], 15);
        
        // Tambahkan tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href=\"https://www.openstreetmap.org/\">OpenStreetMap</a>',
            subdomains: ['a', 'b', 'c']
        }).addTo(map);

        
           // Buat ikon untuk marker ungu dan kuning
        let purpleIcon = L.divIcon({
            className: 'custom-icon',
            html: '<div style=\"color: purple; font-size: 24px;\">🚗</div>',
            iconSize: [24, 24]
        });

        // let yellowIcon = L.divIcon({
        //     className: 'custom-icon',
        //     html: '<div style=\"color: yellow; font-size: 24px;\">📌</div>',
        //     iconSize: [24, 24]
        // });

        // Tambahkan marker untuk lokasi sekarang dengan ikon ungu
        L.marker([$latitude_now, $longitude_now], { icon: purpleIcon }).addTo(map)
            .bindPopup('Lokasi Sekarang')
            .openPopup();
        
        // Tambahkan marker untuk lokasi penempatan dengan ikon kuning
        L.marker([$latitude_penempatan, $longitude_penempatan]).addTo(map)
            .bindPopup('Lokasi Penempatan');

   // Hitung jarak
        let from = L.latLng($latitude_now, $longitude_now);
        let to = L.latLng($latitude_penempatan, $longitude_penempatan);
        let distance = from.distanceTo(to); // Jarak dalam meter

        // Tampilkan jarak dalam kilometer
        var container = document.getElementById('distance');
        // container.innerHTML = (distance / 1000).toFixed(2) + ' km'; // Jarak dalam kilometer
      container.innerHTML = distance.toFixed(0) + ' Meter'; // Jarak dalam meter



            ");
?>