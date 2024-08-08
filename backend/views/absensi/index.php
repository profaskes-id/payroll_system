<?php

use backend\models\Absensi;
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
                $data = \yii\helpers\ArrayHelper::map(\backend\models\Bagian::find()->all(), 'id_bagian', 'nama_bagian');
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
                'karyawan.nama',
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
                            return    $var[0]['jam_masuk'] ?? '-';
                        } else {
                            return '-';
                        }
                    },
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
                            return  $var[0]['jam_pulang'] ?? '-';
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
                                return Html::a("<span class='  text-white'>Sakit</span>", ['update', 'id_absensi' => $model['absensi'][0]['id_absensi']],);;
                            } else {
                                return Html::a("<span class='text-warnging'>Belum di set</span>", ['create', 'tanggal' => Yii::$app->request->post('Absensi')['tanggal'] ?? date('Y-m-d'), 'id_karyawan' => $model['karyawan']['id_karyawan']]);
                            }
                        } else {
                            return Html::a("<span class='text-warnging'>Belum di set</span>", ['create', 'tanggal' => Yii::$app->request->post('Absensi')['tanggal'] ?? date('Y-m-d'), 'id_karyawan' => $model['karyawan']['id_karyawan']]);
                        }
                    },
                    'format' => 'raw',
                ],
                // [
                //     'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                //     'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                //     'class' => 'yii\grid\ActionColumn',
                //     'template' => '{view}',
                //     'buttons' => [
                //         'view' => function ($url, $model) {
                //             $tanggal = Yii::$app->request->post('Absensi')['tanggal'] ?? date('Y-m-d');
                //             $absensiArray = $model['absensi'];
                //             $found = false;

                //             foreach ($absensiArray as $absensi) {
                //                 if ($absensi['tanggal_absensi'] == $tanggal) {
                //                     $found = true;
                //                     break;
                //                 }
                //             }

                //             if ($found) {
                //                 return Html::a('<i class="svgIcon fa fa-regular fa-eye"></i>', ['update', 'id_absensi' => $model['absensi'][0]['id_absensi']], ['class' => 'tambah-button']);
                //             } else {
                //             }
                //         },
                //     ],

                // ],
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