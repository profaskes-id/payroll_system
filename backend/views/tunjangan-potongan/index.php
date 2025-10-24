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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<div>
    <button style="width: 100%;" class="add-button" type="button" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
        <i class="fas fa-search"></i>
        <span>Search</span>
    </button>
    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div class="" style="width: 100%;">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>
</div>

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
                                                '<button style="border-radius: 6px !important; background: #E9EC4850 !important; color:#252525; all:unset; display: block;">
                <span style="margin: 3px 3px !important; display: block; background: #E9EC48 !important; padding: 2px 4px !important; border-radius: 6px !important;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                        <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="m14.304 4.844l2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565l6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                    </svg>
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
                                                '<button style="border-radius: 6px !important; background: #E9EC4850 !important; color:#252525; all:unset; display: block;">
                <span style="margin: 3px 3px !important; display: block; background: #E9EC48 !important; padding: 2px 4px !important; border-radius: 6px !important;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                        <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="m14.304 4.844l2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565l6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                    </svg>
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