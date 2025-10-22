<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tunjangan & Potongan';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 (Bundle = termasuk Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<div class="jam-kerja-index">

    <div class="w-100">
        <div class="card card-primary card-tabs">
            <div class="p-0 pt-1 card-header">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-tunjangan-tab" data-toggle="pill" href="#custom-tabs-one-tunjangan" role="tab" aria-controls="custom-tabs-one-tunjangan" aria-selected="true">Tunjangan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-potongan-tab" data-toggle="pill" href="#custom-tabs-one-potongan" role="tab" aria-controls="custom-tabs-one-potongan" aria-selected="false">Potongan</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">

                    <!-- TAB TUNJANGAN -->
                    <div class="tab-pane fade active show" id="custom-tabs-one-tunjangan" role="tabpanel" aria-labelledby="custom-tabs-one-tunjangan-tab">
                        <p class="d-flex justify-content-end" style="gap: 10px;">
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
                                        'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return Html::a(
                                                '
            <button style="border-radius: 6px !important; background: #488aec50 !important; color:#252525; all:unset; display: block;">
                <span style="margin: 3px 3px !important; display: block; background: #488aec !important; padding: 2px 4px !important; border-radius: 6px !important;">
                    <i class="fas fa-plus fa-sm" style="color: #fff;"></i>
                </span>
            </button>',
                                                ['/tunjangan-detail/create', 'id_karyawan' => $model['id_karyawan']]
                                            );
                                        }
                                    ],
                                    [
                                        'attribute' => 'nama',
                                        'label' => 'Karyawan',
                                    ],
                                    [
                                        'attribute' => 'nama_bagian',
                                        'label' => 'Bagian',
                                        'value' => function ($model) {
                                            return $model['nama_bagian'] ?: '-';
                                        },
                                    ],
                                    [
                                        'attribute' => 'nama_kode',
                                        'label' => 'Jabatan',
                                        'value' => function ($model) {
                                            return $model['nama_kode'] ?: '-';
                                        },
                                    ],
                                    [
                                        'attribute' => 'total_tunjangan',
                                        'label' => 'Total Tunjangan',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $total = $model['total_tunjangan'];
                                            $formattedAmount = 'Rp ' . number_format($total, 2, ',', '.');

                                            return Html::button($formattedAmount, [
                                                'class' => 'btn btn-link btn-detail-tunjangan p-0',
                                                'style' => 'border: none; background: none; color: #007bff; text-decoration: underline;',
                                                'data-bs-toggle' => 'modal',
                                                'data-bs-target' => '#modalTunjanganDetail',
                                                'data-karyawan-id' => $model['id_karyawan'],
                                                'data-karyawan-nama' => $model['nama'],
                                                'title' => 'Klik untuk melihat detail tunjangan'
                                            ]);
                                        },
                                        'contentOptions' => ['style' => 'text-align: right;'],
                                    ],
                                ],
                            ]); ?>
                        </div>
                    </div>

                    <!-- TAB POTONGAN -->
                    <div class="tab-pane fade" id="custom-tabs-one-potongan" role="tabpanel" aria-labelledby="custom-tabs-one-potongan-tab">
                        <p class="d-flex justify-content-end" style="gap: 10px;">
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
                                        'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return Html::a(
                                                '
            <button style="border-radius: 6px !important; background: #488aec50 !important; color:#252525; all:unset; display: block;">
                <span style="margin: 3px 3px !important; display: block; background: #488aec !important; padding: 2px 4px !important; border-radius: 6px !important;">
                    <i class="fas fa-plus fa-sm" style="color: #fff;"></i>
                </span>
            </button>',
                                                ['/potongan-detail/create', 'id_karyawan' => $model['id_karyawan']]
                                            );
                                        }
                                    ],
                                    [
                                        'attribute' => 'nama',
                                        'label' => 'Karyawan',
                                    ],
                                    [
                                        'attribute' => 'nama_bagian',
                                        'label' => 'Bagian',
                                        'value' => function ($model) {
                                            return $model['nama_bagian'] ?: '-';
                                        },
                                    ],
                                    [
                                        'attribute' => 'nama_kode',
                                        'label' => 'Jabatan',
                                        'value' => function ($model) {
                                            return $model['nama_kode'] ?: '-';
                                        },
                                    ],
                                    [
                                        'attribute' => 'total_potongan',
                                        'label' => 'Total Potongan',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $total = $model['total_potongan'];
                                            $formattedAmount = 'Rp ' . number_format($total, 2, ',', '.');

                                            return Html::button($formattedAmount, [
                                                'class' => 'btn btn-link btn-detail-potongan p-0',
                                                'style' => 'border: none; background: none; color: #dc3545; text-decoration: underline;',
                                                'data-bs-toggle' => 'modal',
                                                'data-bs-target' => '#modalPotonganDetail',
                                                'data-karyawan-id' => $model['id_karyawan'],
                                                'data-karyawan-nama' => $model['nama'],
                                                'title' => 'Klik untuk melihat detail potongan'
                                            ]);
                                        },
                                        'contentOptions' => ['style' => 'text-align: right;'],
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





<?= $this->render('_modal_tunjangan_detail'); ?>
<?= $this->render('_modal_potongan_detail'); ?>