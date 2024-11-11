<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\MasterLokasi $model */

$this->title = $model->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Lokasis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="master-lokasi-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class="table-container table-responsive">

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_master_lokasi' => $model->id_master_lokasi], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_master_lokasi' => $model->id_master_lokasi], [
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
                    'label' => 'Perusahaan',
                    'value' => function ($model) {
                        return $model->label;
                    }
                ],
                'alamat',
                'longtitude',
                'latitude',
                [
                    'label' => "radius Absensi",
                    'value' => function ($model) {
                        if ($model->radius == null) {
                            $model->radius = 0;
                        }
                        return $model->radius . " meter";
                    }
                ]
            ],
        ]) ?>

    </div>




    <div class=" w-full" id="map" style=" height: 80dvh !important; z-index: 2 !important "></div>



    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // console.info()
        window.addEventListener('load', function() {

            navigator.geolocation.watchPosition(function(position) {



                    let map = L.map('map').setView([(<?= $model->latitude ?>).toFixed(10), <?= $model->longtitude ?>.toFixed(10)], 15); // set initial view to the specified location

                    // Add a tile layer (e.g. OpenStreetMap)
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
                        subdomains: ['a', 'b', 'c']
                    }).addTo(map);

                    // Add a marker at the specified location
                    let marker = L.marker([<?= $model->latitude ?>.toFixed(10), <?= $model->longtitude ?>.toFixed(10)]).addTo(map);
                },
                function(error) {
                    console.log("Error: " + error.message);
                }, {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                });
        });
    </script>