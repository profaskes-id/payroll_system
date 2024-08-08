<?php

// use backend\models\HariLibur;
use backend\models\JadwalKerja;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\JamKerja $model */

$this->title = $model->nama_jam_kerja;
$this->params['breadcrumbs'][] = ['label' => 'Jam kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jam-kerja-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class='table-container'>


        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Jadwal Kerja</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-harilibur-tab" data-toggle="pill" href="#custom-tabs-one-harilibur" role="tab" aria-controls="custom-tabs-one-harilibur" aria-selected="false">Hari Libur</a>
                    </li> -->

                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

                        <p class="d-flex justify-content-end " style="gap: 10px;">
                            <?= Html::a('Update', ['update', 'id_jam_kerja' => $model->id_jam_kerja], ['class' => 'add-button']) ?>
                            <?= Html::a('Delete', ['delete', 'id_jam_kerja' => $model->id_jam_kerja], [
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
                                'id_jam_kerja',
                                'nama_jam_kerja',
                                [
                                    'label' => 'Jenis Shift',
                                    'value' => function ($model) {
                                        return $model->jenisShift->nama_kode;
                                    }
                                ],
                            ],
                        ]) ?>


                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                        <p class="d-flex justify-content-end " style="gap: 10px;">
                            <?= Html::a('Add new', ['/jadwal-kerja/create', 'id_jam_kerja' => $model->id_jam_kerja], ['class' => 'tambah-button']) ?>
                        </p>
                        <?= GridView::widget([
                            'dataProvider' => $jadwalKerjaProvider,
                            'columns' => [
                                [
                                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                                    'class' => 'yii\grid\SerialColumn'
                                ],
                                [
                                    'label' => 'nama_hari',
                                    'value' => function ($model) {
                                        return $model->getNamaHari($model->nama_hari);
                                    },
                                ],
                                'jam_masuk',
                                'jam_keluar',
                                [
                                    'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                    'class' => ActionColumn::className(),
                                    'urlCreator' => function ($action, JadwalKerja $model, $key, $index, $column) {
                                        return Url::toRoute(['jadwal-kerja/view', 'id_jadwal_kerja' => $model->id_jadwal_kerja]);
                                    }
                                ],
                            ],
                        ]); ?>
                    </div>
                    <!-- <div class="tab-pane fade" id="custom-tabs-one-harilibur" role="tabpanel" aria-labelledby="custom-tabs-one-harilibur-tab">
                        <p class="d-flex justify-content-end " style="gap: 10px;">
                            <?= Html::a('Add new', ['/jadwal-kerja/create', 'id_jam_kerja' => $model->id_jam_kerja], ['class' => 'tambah-button']) ?>
                        </p>
                        <?php
                        // GridView::widget([
                        //     'dataProvider' => $hariLiburdataProvider,
                        //     'columns' => [
                        //         [
                        //             'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                        //             'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                        //             'class' => 'yii\grid\SerialColumn'
                        //         ],

                        //         'tanggal',
                        //         'nama_hari_libur',
                        //         [

                        //             'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                        //             'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                        //             'class' => ActionColumn::className(),
                        //             'urlCreator' => function ($action, HariLibur $model, $key, $index, $column) {
                        //                 return Url::toRoute(['hari-libur/view  ', 'id_hari_libur' => $model->id_hari_libur]);
                        //             }
                        //         ],
                        //     ],
                        // ]); 
                        ?>
                    </div> -->

                </div>
            </div>

        </div>
    </div>

</div>