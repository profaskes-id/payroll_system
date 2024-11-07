<?php

use backend\models\helpers\PeriodeGajiHelper;
use backend\models\Tanggal;
use backend\models\TransaksiGaji;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGaji $model */

$this->title = 'Transaksi Gaji ' .  $model['nama'] . ' (' . $model['periode_gaji'] . ')';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transaksi Gaji'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transaksi-gaji-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('update', ['update', 'id_transaksi_gaji' => $model->id_transaksi_gaji, 'id_karyawan' => $id_karyawan['id_karyawan'],], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_transaksi_gaji' => $model->id_transaksi_gaji], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-striped table-bordered detail-view'],
            'template' => '<tr><th{captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>',
            'attributes' => [
                [
                    'group' => true,
                    'label' => 'Informasi Karyawan',
                    'rowOptions' => ['class' => 'info'],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'nomer_identitas',
                            'label' => 'Nomor Identitas',
                        ],
                        [
                            'attribute' => 'nama',
                            'label' => 'Nama',
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'bagian',
                            'label' => 'Bagian',
                        ],
                        [
                            'attribute' => 'jabatan',
                            'label' => 'Jabatan',
                        ],
                    ],
                ],
                [
                    'group' => true,
                    'label' => 'Informasi Gaji',
                    'rowOptions' => ['class' => 'info'],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'jam_kerja',
                            'label' => 'Jam Kerja',
                        ],
                        [
                            'label' => "Periode Gaji",
                            'value' => function ($model) {
                                $data =  PeriodeGajiHelper::getPeriodeGaji($model['periode_gaji']);
                                $tanggal = new Tanggal();
                                return  $tanggal->getBulan($data['bulan']) . ' '  . $data['tahun'];
                            }
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'label' => 'Jumlah Hari Kerja',
                            'value' => function ($model) {
                                return $model['jumlah_hari_kerja'] . ' Hari';
                            }
                        ],
                        [
                            'attribute' => 'jumlah_hadir',
                            'label' => 'Jumlah Hadir',
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'jumlah_sakit',
                            'label' => 'Jumlah Sakit',
                        ],
                        [
                            'attribute' => 'jumlah_wfh',
                            'label' => 'Jumlah WFH',
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'jumlah_cuti',
                            'label' => 'Jumlah Cuti',
                        ],
                        [
                            'attribute' => 'jumlah_jam_lembur',
                            'label' => 'Jumlah Jam Lembur',
                        ],
                    ],
                ],
                [
                    'group' => true,
                    'label' => 'Rincian Gaji',
                    'rowOptions' => ['class' => 'info'],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'gaji_pokok',
                            'label' => 'Gaji Pokok',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->gaji_pokok, 0, ',', '.');
                            }
                        ],
                        [
                            'attribute' => 'total_lembur',
                            'label' => 'Total Lembur',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->total_lembur, 0, ',', '.');
                            }
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'jumlah_tunjangan',
                            'label' => 'Jumlah Tunjangan',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->jumlah_tunjangan, 0, ',', '.');
                            }
                        ],
                        [
                            'attribute' => 'jumlah_potongan',
                            'label' => 'Jumlah Potongan',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->jumlah_potongan, 0, ',', '.');
                            }
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'potongan_wfh_hari',
                            'label' => 'Potongan WFH per Hari',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->potongan_wfh_hari, 0, ',', '.');
                            }
                        ],
                        [
                            'attribute' => 'jumlah_potongan_wfh',
                            'label' => 'Jumlah Potongan WFH',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->jumlah_potongan_wfh, 0, ',', '.');
                            }
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'gaji_diterima',
                            'label' => 'Gaji Diterima',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->gaji_diterima, 0, ',', '.');
                            }
                        ],
                    ],
                ],
            ],
        ]) ?>
    </div>



</div>