<?php

use backend\models\Absensi;
use backend\models\Bagian;
use backend\models\Karyawan;
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

$this->title = 'Absensi';
$this->params['breadcrumbs'][] = $this->title;

$today = date('Y-m-d');
?>

<?php Pjax::begin(); ?>
<div class="absensi-index position-relative">

    <?php $form = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',   'action' => ['absensi/index']]); ?>
    <div class='table-container'>
        <div class="row mb-2">
            <div class="col-12 col-md-6">
                <?= $form->field($absensi, 'tanggal')->textInput([
                    'class' => 'form-control',
                    'value' => Yii::$app->request->post('Absensi')['tanggal'] ?? $today,
                    'type' => 'date',
                    'id' => 'tanggal-input' // Tambahkan ID
                ])->label(false) ?>
            </div>


            <div class="col-12 col-md-5">
                <?php
                $idBagian = Yii::$app->request->post('Bagian')['id_bagian'] ?? 0;
                $data = \yii\helpers\ArrayHelper::map(Bagian::find()->all(), 'id_bagian', 'nama_bagian');
                echo $form->field($bagian, 'id_bagian')->widget(Select2::classname(), [
                    'data' => $data,
                    'options' => ['placeholder' => 'Pilih Divisi ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ])->label(false);
                ?>

            </div>
            <div class="col-12 col-md-1">
                <button class="add-button" type="submit">
                    <i class="fas fa-search"></i>
                </button>
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
                    'value' => function ($model) {
                        if ($model['absensi']) {
                            return Html::a('<button  style=" border-radius: 6px !important; background: #488aec50 !important; color:#252525; all:unset;  display: block;">
        <span style="margin: 3px 3px !important;display: block; background: #488aec !important; padding: 0px 3px 0.1px !important; border-radius: 6px !important;">
        <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0"/></svg>
        </span>
        </button>', ['view', 'id_absensi' => $model['absensi'][0]['id_absensi']],);
                        }
                        return '<p class="text-center text-sm text-danger">Belum Hadir<p>';
                    }
                ],
                [
                    'label' => 'Karyawan',
                    'value' => function($model){
                        
                        return $model['karyawan']['nama'];
                    }
                    ],
                [
                    'headerOptions' => ['style' => 'width: 200px; text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'label' => 'Tanggal',
                    'value' => function () {
                        $tanggal = Yii::$app->request->post('Absensi')['tanggal'] ?? date('Y-m-d');
                        $formattedTanggal = date('d-m-Y', strtotime($tanggal));

                        return $formattedTanggal;
                    }
                ],

                [
                    'headerOptions' => ['style' => 'width: 15%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 15%; text-align: center;'],
                    'label' => 'Jam Masuk',
                    'value' => function ($model) {
                        $item = Yii::$app->request->post('Absensi')['tanggal'] ?? date('Y-m-d');

                        $result = array_filter($model['absensi'], function ($value) use ($item) {
                            return $value['tanggal_absensi'] == $item;
                        });
                        if (!empty($result)) {
                            $var = [...$result];
                            return  date('H:i', strtotime($var[0]['jam_masuk'] ?? '00:00:00')) ?? '-';
                        } else {
                            return '-';
                        }
                    },
                ],
                [
                    'headerOptions' => ['style' => 'width: 15%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 15%; text-align: center;'],
                    'label' => 'Jam Pulang',
                    'value' => function ($model) {
                        $item = Yii::$app->request->post('Absensi')['tanggal'] ?? date('Y-m-d');

                        $result = array_filter($model['absensi'], function ($value) use ($item) {
                            return $value['tanggal_absensi'] == $item;
                        });
                        if (!empty($result)) {
                            $var = [...$result];
                            return date('H:i', strtotime($var[0]['jam_pulang'] ?? '00:00:00')) ?? '-';
                        } else {
                            return '-';
                        }
                    },
                ],
                [
                    'headerOptions' => ['style' => 'width: 15%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 15%; text-align: center;'],

                    'label' => 'Kehadiran',
                    'value' => function ($model) {
                        $item = Yii::$app->request->post('Absensi')['tanggal'] ?? date('Y-m-d');

                        $result = array_filter($model['absensi'], function ($value) use ($item) {
                            return $value['tanggal_absensi'] == $item;
                        });
                        if (!empty($result)) {

                            $val = [...$result];
                            if ($val[0]['kode_status_hadir'] == 1) {
                                return Html::a("<span class='text-success'>Hadir</span>", ['update', 'id_absensi' => $model['absensi'][0]['id_absensi']],);;
                            } else if ($val[0]['kode_status_hadir'] == 2) {
                                return Html::a("<span class='text-black'>IZIN</span>", ['update', 'id_absensi' => $model['absensi'][0]['id_absensi']],);;
                            } else if ($val[0]['kode_status_hadir'] == 3) {
                                return Html::a("<span class='  text-primary'>Sakit</span>", ['update', 'id_absensi' => $model['absensi'][0]['id_absensi']],);;
                            } else {
                                return Html::a("<span class='text-warnging'>Belum di set</span>", ['create', 'tanggal' => Yii::$app->request->post('Absensi')['tanggal'] ?? date('Y-m-d'), 'id_karyawan' => $model['karyawan']['id_karyawan']]);
                            }
                        } else {
                            return Html::a("<span class='text-warnging'>Belum di set</span>", ['create', 'tanggal' => Yii::$app->request->post('Absensi')['tanggal'] ?? date('Y-m-d'), 'id_karyawan' => $model['karyawan']['id_karyawan']]);
                        }
                    },
                    'format' => 'raw',
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