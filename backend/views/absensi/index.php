<?php

use backend\models\Absensi;
use backend\models\Bagian;
use backend\models\Karyawan;
use backend\models\Tanggal;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var backend\models\AbsensiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Absensi Hari Ini';
$this->params['breadcrumbs'][] = $this->title;

$today = date('Y-m-d');
?>



<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    function getDistance(latitude_now, longitude_now, latitude_penempatan, longitude_penempatan, idElement) {
        if (!idElement) {
            return false;
        } else {

            let from = L.latLng(latitude_now, longitude_now);
            let to = L.latLng(latitude_penempatan, longitude_penempatan);
            let distance = from.distanceTo(to); // Jarak dalam meter
            idElement.innerHTML = distance.toFixed(0) + ' Meter';

        }
    }
</script>

<?php Pjax::begin(); ?>
<div class="absensi-index position-relative">

    <?php $form = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',   'action' => ['absensi/index']]); ?>
    <div class="table-container table-responsive">
        <div class="row mb-2">

            <div class="col-lg-4 col-12">
                <?= $form->field($absensi, 'tanggal')->textInput(['type' => 'date',  'value' => $tanggalSet ?? $today])->label(false); ?>
            </div>


            <div class="col-lg-5 col-12">
                <?php
                $idBagian = Yii::$app->request->post('Bagian')['id_bagian'] ?? 0;
                $data = \yii\helpers\ArrayHelper::map(Bagian::find()->all(), 'id_bagian', 'nama_bagian');
                echo $form->field($bagian, 'id_bagian')->widget(Select2::classname(), [
                    'data' => $data,
                    'options' => ['placeholder' => 'Pilih Bagian ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ])->label(false);
                ?>

            </div>

            <div class="col-lg-3 d-flex justify-content-start   " style="gap: 10px;">

                <div class=" ">
                    <button class="add-button" type="submit">
                        <i class="fas fa-search"></i>Search
                    </button>
                </div>
                <div class="">
                    <a href="/panel/absensi/index">
                        <button class="reset-button" type="button">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

        <?= GridView::widget([
            'id' => 'grid-view',
            'summary' => false,
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],
                [
                    'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'format' => 'raw',
                    'value' => function ($model) use ($tanggalSet) {
                        if ($model['absensi']) {
                            return Html::a('<button  style=" border-radius: 6px !important; background: #488aec50 !important; color:#252525; all:unset;  display: block;">
                                        <span style="margin: 3px 3px !important;display: block; background: #488aec !important; padding: 0px 3px 0.1px !important; border-radius: 6px !important;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0"/></svg>
                                        </span>
                                        </button>', ['view', 'id_absensi' => $model['absensi']['id_absensi']],);
                        } else {
                            return Html::a('<button  style=" border-radius: 6px !important; background: #E9EC4850 !important; color:#252525; all:unset;  display: block;">
                                        <span style="margin: 3px 3px !important;display: block; background: #E9EC48 !important; padding: 0px 3px 0.1px !important; border-radius: 6px !important;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844l2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565l6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/></svg>
                                         </span>
                                        </button>', ['create', 'id_karyawan' => $model['karyawan']['id_karyawan'], 'tanggal' => $tanggalSet, 'id_jadwal_kerja' => $model['jadwal_kerja']['id_jadwal_kerja'] ?? '-1'],);
                        }
                    }
                ],
                [
                    'label' => 'Karyawan',
                    'value' => function ($model) {
                        return $model['karyawan']['nama'];
                    }
                ],
                [

                    'label' => 'Hari',
                    'contentOptions' => ['style' => 'width: 15%; text-align: center;'],
                    'headerOptions' => ['style' => 'width: 15%; text-align: center;'],
                    'value' => function ($model) use ($tanggalSet) {

                        $nama_hari = date('l', strtotime($tanggalSet ?? date('Y-m-d')));
                        $tanggal = new Tanggal();
                        return $tanggal->getIndonesiaHari($nama_hari);
                    }
                ],
                [
                    'contentOptions' => ['style' => 'width: 15%; text-align: center;'],
                    'headerOptions' => ['style' => 'width: 15%; text-align: center;'],
                    'label' => 'Jam Masuk',
                    'format' => 'raw',
                    'value' => function ($model) {

                        if ($model['absensi']) {
                            $jam_karyawan_masuk = $model['absensi']['jam_masuk'];
                            if ($model['absensi']['is_terlambat'] == 0) {
                                return "<span style='color: black;'>$jam_karyawan_masuk</span>";
                            } else {
                                return "<span style='color: red;'>$jam_karyawan_masuk</span>";
                            }
                        } else {
                            return "<span style='color: red;'>00:00:00</span>";
                        }
                    },
                ],

                [
                    'headerOptions' => ['style' => 'width: 15%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 15%; text-align: center;'],
                    'label' => 'Jam Pulang',
                    'value' => function ($model) {
                        if ($model['absensi']) {
                            return $model['absensi']['jam_pulang'] ?? '00:00:00';
                        } else {
                            return '00:00:00';
                        }
                    },
                ],
                [
                    'headerOptions' => ['style' => 'width: 15%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 15%; text-align: center;'],

                    'label' => 'Kehadiran',
                    'value' => function ($model) {
                        // if ($model['jadwal_kerja'] == null) {
                        //     return "<span style='color: red; font-size: 10px; text-transform: capitalize;'> jadwal kerja  Belum di set</span>";
                        // }
                        if ($model['absensi']) {

                            if ($model['absensi']['kode_status_hadir'] == "H") {
                                return "<span class='text-success'>Hadir</span>";
                            } else if ($model['absensi']['kode_status_hadir'] == 'I') {
                                return "<span class='text-warning'>IZIN</span>";
                            } else if ($model['absensi']['kode_status_hadir'] == 'S') {
                                return "<span class='text-primary'>Sakit</span>";
                            } else {
                                return "<span class='text-black'>Tidak Hadir</span>";
                            }
                        } else {
                            return "<span class=' text-black'>Tidak Hadir</span>";
                        }
                    },
                    'format' => 'raw',
                ],
                [
                    'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                    'label' => 'Jarak',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (!$model['absensi']) {
                            return '-';
                        }

                        // Ambil nilai parameter dari model
                        $longitud_absensi = $model['absensi']['long'];
                        $latitude_absensi = $model['absensi']['lat'];
                        $penempatan_longitude = strval($model['absensi']['penempatan_long']);
                        $penempatan_latitude = strval($model['absensi']['penempatan_lat']);

                        // Buat span dengan ID yang unik dan script untuk menghitung jarak
                        $spanId = uniqid('distance_');
                        $script = "
                            <script>
                                 getDistance($latitude_absensi, $longitud_absensi, $penempatan_latitude, $penempatan_longitude , $spanId);
                            </script>
                        ";

                        return "<span id='$spanId' style='cursor: pointer;'>Belum Di Set</span>" . $script;
                    }
                ],
            ],
        ]); ?>
    </div>

</div>
<?php Pjax::end(); ?>








<script>
    document.addEventListener('DOMContentLoaded', function() {






        // Ambil elemen input dengan ID tanggal-input
        var tanggalInput = document.getElementById('tanggal-input');
        var bagian = document.getElementById('bagian-input');

        // Tambahkan event listener untuk perubahan

        function autosubmit() {


            var form = tanggalInput.closest('form');

            // Kirim form menggunakan metode GET
            if (form) {
                setTimeout(function() {
                    form.submit();
                }, 1500);
            }
        }

        // tanggalInput.addEventListener('input', autosubmit);
        // bagian.addEventListener('input', autosubmit);
    });
</script>