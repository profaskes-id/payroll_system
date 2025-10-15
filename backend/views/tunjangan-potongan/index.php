<?php

use backend\models\JamKerja;
use backend\models\ShiftKerja;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tunjangan & Potongan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-index">


    <div class="w-100">
        <div class="card card-primary card-tabs">
            <div class="p-0 pt-1 card-header">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-tunjangan-tab" data-toggle="pill" href="#custom-tabs-one-tunjangan" role="tab" aria-controls="custom-tabs-one-tunjangan" aria-selected="true">Tunjangan </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-potongan-tab" data-toggle="pill" href="#custom-tabs-one-potongan" role="tab" aria-controls="custom-tabs-one-potongan" aria-selected="false">Potongan</a>
                    </li>

                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">


                    <div class="tab-pane fade active show" id="custom-tabs-one-tunjangan" role="tabpanel" aria-labelledby="custom-tabs-one-tunjangan-tab">

                        <p class="d-flex justify-content-end " style="gap: 10px;">
                            <?= Html::a('Add new', ['/tunjangan-detail/create'], ['class' => 'tambah-button']) ?>
                        </p>
                        <div class="table-container table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $dataTunjanganProvider,
                                'columns' => [
                                    [
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'class' => 'yii\grid\SerialColumn'
                                    ],
                                    [
                                        'class' => 'yii\grid\Column',

                                        'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'contentOptions' => ['style' => 'text-align: center;'],
                                        'content' => function ($model, $key, $index, $column) {
                                            // dd($model);
                                            return "<div class='d-flex justify-content-center'>" .
                                                Html::a(
                                                    '<i class="fas fa-edit"></i>',
                                                    ['/tunjangan-detail/update', 'id_tunjangan_detail' => $model['id_tunjangan_detail']],
                                                    ['class' => 'btn btn-sm btn-primary me-2', 'title' => 'Edit']
                                                ) .
                                                Html::a(
                                                    '<i class="fas fa-trash"></i>',
                                                    ['/tunjangan-detail/delete', 'id_tunjangan_detail' => $model['id_tunjangan_detail']],
                                                    [
                                                        'class' => 'btn btn-sm btn-danger',
                                                        'title' => 'Hapus',
                                                        'data' => [
                                                            'confirm' => 'Apakah Anda yakin ingin menghapus item ini?',
                                                            'method' => 'post',
                                                        ],
                                                    ]
                                                ) .
                                                "</div>";
                                        },
                                    ],
                                    [
                                        'attribute' => 'nama_tunjangan',
                                        'label' => 'Tunjangan',
                                    ],
                                    [
                                        'attribute' => 'nama',
                                        'label' => 'Karyawan',
                                    ],
                                    [
                                        'attribute' => 'nama_bagian',
                                        'label' => 'Bagian',
                                    ],
                                    [
                                        'attribute' => 'nama_kode',
                                        'label' => ' Jabatan',
                                    ],
                                    [
                                        'attribute' => 'jumlah',
                                        'label' => 'Jumlah',
                                        'value' => function ($model) {
                                            $jumlah = $model['jumlah'];

                                            if ($jumlah < 100) {
                                                return number_format($jumlah, 2, ',', '.') . '%';
                                            } else {
                                                return 'Rp ' . number_format($jumlah, 2, ',', '.');
                                            }
                                        },
                                    ],

                                    [
                                        'attribute' => 'status',
                                        'label' => 'Status',
                                        'value' => function ($model) {
                                            return $model['status'] == 1 ? 'Aktif' : 'Tidak Aktif';
                                        },
                                        'contentOptions' => ['style' => 'text-align: center;'],
                                    ],
                                ],
                            ]); ?>



                        </div>


                    </div>

                    <div class="tab-pane fade" id="custom-tabs-one-potongan" role="tabpanel" aria-labelledby="custom-tabs-one-potongan-tab">

                        <p class="d-flex justify-content-end " style="gap: 10px;">
                            <?= Html::a('Add new', ['/potongan-detail/create'], ['class' => 'tambah-button']) ?>
                        </p>


                        <div class="table-container table-responsive">

                            <?= GridView::widget([
                                'dataProvider' => $dataPotonganProvider,
                                'columns' => [
                                    [
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'class' => 'yii\grid\SerialColumn'
                                    ],
                                    [
                                        'class' => 'yii\grid\Column',
                                        'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'contentOptions' => ['style' => 'text-align: center;'],
                                        'content' => function ($model, $key, $index, $column) {
                                            return "<div class='d-flex justify-content-center'>" .
                                                Html::a(
                                                    '<i class="fas fa-edit"></i>',
                                                    ['/potongan-detail/update', 'id_potongan_detail' => $model['id_potongan_detail']],
                                                    ['class' => 'btn btn-sm btn-primary me-2', 'title' => 'Edit']
                                                ) .
                                                Html::a(
                                                    '<i class="fas fa-trash"></i>',
                                                    ['/potongan-detail/delete', 'id_potongan_detail' => $model['id_potongan_detail']],
                                                    [
                                                        'class' => 'btn btn-sm btn-danger',
                                                        'title' => 'Hapus',
                                                        'data' => [
                                                            'confirm' => 'Apakah Anda yakin ingin menghapus item ini?',
                                                            'method' => 'post',
                                                        ],
                                                    ]
                                                ) .
                                                "</div>";
                                        },
                                    ],
                                    [
                                        'attribute' => 'nama_potongan',
                                        'label' => 'Potongan',
                                    ],
                                    [
                                        'attribute' => 'nama',
                                        'label' => 'Karyawan',
                                    ],
                                    [
                                        'attribute' => 'nama_bagian',
                                        'label' => 'Bagian',
                                    ],
                                    [
                                        'attribute' => 'nama_kode',
                                        'label' => 'Jabatan',
                                    ],
                                    [
                                        'attribute' => 'jumlah',
                                        'label' => 'Jumlah',
                                        'value' => function ($model) {
                                            $jumlah = $model['jumlah'];

                                            if ($jumlah < 100) {
                                                return number_format($jumlah, 2, ',', '.') . '%';
                                            } else {
                                                return 'Rp. ' . number_format($jumlah, 2, ',', '.');
                                            }
                                        },
                                    ],

                                    [
                                        'attribute' => 'status',
                                        'label' => 'Status',
                                        'value' => function ($model) {
                                            return $model['status'] == 1 ? 'Aktif' : 'Tidak Aktif';
                                        },
                                        'contentOptions' => ['style' => 'text-align: center;'],
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