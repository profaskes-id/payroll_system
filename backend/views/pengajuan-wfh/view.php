<?php

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanWfh $model */

$this->title = "Pengajuan WFH " . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Wfhs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="pengajuan-wfh-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Tanggapi', ['update', 'id_pengajuan_wfh' => $model->id_pengajuan_wfh], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_pengajuan_wfh' => $model->id_pengajuan_wfh], [
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
                    'label' => 'Karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    },
                ],
                'alasan:ntext',
                'lokasi',
                [
                    'format' => 'raw',
                    'attribute' => 'tanggal',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        $day_wfh =  json_decode($model->tanggal_array);
                        if ($day_wfh) {
                            $finalValue = [];
                            foreach ($day_wfh as $item) {
                                $finalValue[] = "<li style='margin-left: 20px'>" . $tanggalFormat->getIndonesiaFormatTanggal($item) . "</li>";
                            }
                            return implode('', $finalValue);
                        } else {
                            return 'belum di set';
                        }
                    },
                ],
                'longitude',
                'latitude',
                [
                    'format' => 'raw',
                    'attribute' => 'status',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Menuggu Tanggapan</span>';
                        } elseif ($model->status == 1) {
                            return '<span class="text-success">Pengajuan Telah Disetujui</span>';
                        } elseif ($model->status == 2) {
                            return '<span class="text-danger">Pengajuan  Ditolak</span>';
                        } else {
                            return "<span class='text-danger'>master kode tidak aktif</span>";
                        }
                    },
                ],
            ],
        ]) ?>

    </div>
    <div class=" w-full" id="map" style=" height: 400px; z-index: 2 !important "></div>
</div>






<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    navigator.geolocation.watchPosition(function(position) {



            let map = L.map('map').setView([(<?= $model->latitude ?>).toFixed(10), <?= $model->longitude ?>.toFixed(10)], 15); // set initial view to the specified location

            // Add a tile layer (e.g. OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
                subdomains: ['a', 'b', 'c']
            }).addTo(map);

            // Add a marker at the specified location
            let marker = L.marker([<?= $model->latitude ?>.toFixed(10), <?= $model->longitude ?>.toFixed(10)]).addTo(map);
        },
        function(error) {
            console.log("Error: " + error.message);
        }, {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        });
</script>