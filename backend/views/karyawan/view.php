<?php

use backend\models\DataKeluarga;
use backend\models\DataPekerjaan;
use backend\models\PengalamanKerja;
use backend\models\RiwayatPendidikan;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Karyawan $model */

$this->title = $model->nama . ' (' . $model->kode_karyawan . ')';
$this->params['breadcrumbs'][] = ['label' => 'karyawan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="karyawan-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container">
        <div class="w-100">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Data Pribadi</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Pengalaman Kerja</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Riwayat Pendidikan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Data Keluarga</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-pekerjaan-tab" data-toggle="pill" href="#custom-tabs-one-pekerjaan" role="tab" aria-controls="custom-tabs-one-pekerjaan" aria-selected="false">Data Pekerjaan</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Update', ['update', 'id_karyawan' => $model->id_karyawan], ['class' => 'add-button']) ?>
                                <?= Html::a('Delete', ['delete', 'id_karyawan' => $model->id_karyawan], [
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
                                    'id_karyawan',
                                    'kode_karyawan',
                                    'nama',
                                    'nomer_identitas',
                                    'jenis_identitas',
                                    'tanggal_lahir',
                                    'kode_negara',
                                    'kode_provinsi',
                                    'kode_kabupaten_kota',
                                    'kode_kecamatan',
                                    [
                                        'label' => 'Jenis Kelamin',
                                        'value' => function ($model) {
                                            return $model->kode_jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan';
                                        }
                                    ],
                                    'email:email',
                                ],
                            ]) ?>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">

                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Add new', ['/pengalaman-kerja/create'], ['class' => 'tambah-button']) ?>
                            </p>

                            <?= GridView::widget([
                                'dataProvider' => $pengalamankerjaProvider,
                                'columns' => [
                                    [
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'class' => 'yii\grid\SerialColumn'
                                    ],
                                    'perusahaan',
                                    'posisi',
                                    'masuk_pada',
                                    'keluar_pada',
                                    [
                                        'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'class' => ActionColumn::className(),
                                        'urlCreator' => function ($action, PengalamanKerja $model, $key, $index, $column) {
                                            return Url::toRoute(['pengalaman-kerja/view/', 'id_pengalaman_kerja' => $model->id_pengalaman_kerja]);
                                        }
                                    ],
                                ],
                            ]); ?>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">

                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Add new', ['/riwayat-pendidikan/create'], ['class' => 'tambah-button']) ?>
                            </p>


                            <?php
                            echo  GridView::widget([
                                'dataProvider' => $riwayarProvider,

                                'columns' => [
                                    [
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'class' => 'yii\grid\SerialColumn'
                                    ],
                                    [
                                        'label' => 'Jenjang Pendidikan',
                                        'value' => function ($model) {
                                            return $model->jenjangPendidikan->nama_kode;
                                        }
                                    ],
                                    'institusi',
                                    'tahun_masuk',
                                    'tahun_keluar',
                                    [
                                        'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'class' => ActionColumn::className(),
                                        'urlCreator' => function ($action, RiwayatPendidikan $model, $key, $index, $column) {
                                            return Url::toRoute(['riwayat-pendidikan/view/', 'id_riwayat_pendidikan' => $model->id_riwayat_pendidikan]);
                                        }
                                    ],
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">

                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Add new', ['/data-keluarga/create'], ['class' => 'tambah-button']) ?>
                            </p>


                            <?= GridView::widget([
                                'dataProvider' => $dataKeluargaProvider,
                                'columns' => [
                                    [
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'class' => 'yii\grid\SerialColumn'
                                    ],
                                    'nama_anggota_keluarga',
                                    'hubungan',
                                    'pekerjaan',
                                    'tahun_lahir',
                                    [
                                        'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'class' => ActionColumn::className(),
                                        'urlCreator' => function ($action, DataKeluarga $model, $key, $index, $column) {
                                            return Url::toRoute(['data-keluarga/view/', 'id_data_keluarga' => $model->id_data_keluarga]);
                                        }
                                    ],
                                ],
                            ]); ?>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-pekerjaan" role="tabpanel" aria-labelledby="custom-tabs-one-pekerjaan-tab">

                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Add new', ['/data-pekerjaan/create', 'id_karyawan' => $model->id_karyawan], ['class' => 'tambah-button']) ?>
                            </p>


                            <?= GridView::widget([
                                'dataProvider' => $pekrjaandataProvider,
                                'columns' => [
                                    [
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'class' => 'yii\grid\SerialColumn'
                                    ],

                                    [
                                        'attribute' => 'Karyawan',
                                        'value' => function ($model) {
                                            return $model->karyawan->nama;
                                        }
                                    ],

                                    // 'dari',
                                    // 'sampai',
                                    [
                                        'attribute' => 'status',
                                        'value' => function ($model) {
                                            return $model->statusPekerjaan->nama_kode;
                                        }
                                    ],
                                    'jabatan',
                                    [
                                        'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'class' => ActionColumn::className(),
                                        'urlCreator' => function ($action, DataPekerjaan $model, $key, $index, $column) {
                                            return Url::toRoute(['data-pekerjaan/view/', 'id_data_pekerjaan' => $model->id_data_pekerjaan]);
                                        }
                                    ],
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


</div>