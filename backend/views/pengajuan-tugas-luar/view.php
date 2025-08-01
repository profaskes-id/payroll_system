<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanTugasLuar $model */

$this->title = $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Tugas Luars'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengajuan-tugas-luar-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('update', ['update', 'id_tugas_luar' => $model->id_tugas_luar], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_tugas_luar' => $model->id_tugas_luar], [
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
                    'attribute' => 'karyawan',
                    'label' => "Karyawan",
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'status_pengajuan',
                    'label' => 'Status',
                    'value' => function ($model) {
                        if ($model->status_pengajuan !== null) {
                            if (strtolower($model->status_pengajuan) == 0) {
                                return "<span class='text-capitalize text-warning '>Pending</span>";
                            } elseif (strtolower($model->status_pengajuan) == 1) {
                                return "<span class='text-capitalize text-success '>disetujui</span>";
                            } elseif (strtolower($model->status_pengajuan) == 2) {
                                return "<span class='text-capitalize text-danger '>ditolak</span>";
                            }
                        } else {
                            return "<span class='text-danger'>master kode tidak aktif</span>";
                        }
                    },
                ],
                'created_at',
                'updated_at',
            ],
        ]) ?>

        <h4 class="mt-4">Detail Tugas Luar</h4>

        <?= GridView::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $model->detailTugasLuars,
                'pagination' => false,
            ]),
            'tableOptions' => ['class' => 'table table-striped table-bordered'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'keterangan',
                [
                    'attribute' => 'jam_diajukan',
                    'value' => function ($model) {
                        return date('H:i', strtotime($model->jam_diajukan));
                    }
                ],
                [
                    'attribute' => 'jam_check_in',
                    'value' => function ($model) {
                        return $model->jam_check_in ? date('H:i', strtotime($model->jam_check_in)) : '-';
                    }
                ],
                [
                    'attribute' => 'status_check',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status_check === 1) {
                            return "<span class='text-success'>Checked In</span>";
                        } else {
                            return "<span class='text-warning'>Belum Check In</span>";
                        }
                    }
                ],
                [
                    'attribute' => 'bukti_foto',
                    'label' => 'Bukti Foto',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->bukti_foto) {
                            $imageUrl = Yii::getAlias('@web/uploads/bukti_tugas_luar/') . $model->bukti_foto;
                            return Html::a(
                                Html::img($imageUrl, [
                                    'class' => 'img-thumbnail',
                                    'style' => 'width: 80px; height: 60px; object-fit: cover;',
                                    'alt' => 'Bukti Tugas Luar'
                                ]),
                                $imageUrl,
                                [
                                    'target' => '_blank',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Klik untuk melihat ukuran penuh'
                                ]
                            );
                        }
                        return '-';
                    },
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                ],
                [
                    'attribute' => 'lokasi',
                    'label' => 'Lokasi',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->latitude && $model->longitude) {
                            $mapUrl = "https://www.google.com/maps?q={$model->latitude},{$model->longitude}";
                            return Html::a(
                                '<i class="fa fa-map-marker-alt"></i> Lihat Peta',
                                $mapUrl,
                                [
                                    'class' => 'btn btn-sm btn-outline-primary',
                                    'target' => '_blank',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Buka di Google Maps'
                                ]
                            );
                        }
                        return '-';
                    },
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            return Html::a(
                                '<i class="fas fa-trash"></i>',
                                ['delete-detail', 'id' => $model->id_detail],
                                [
                                    'class' => 'btn btn-sm btn-danger',
                                    'data' => [
                                        'confirm' => 'Apakah Anda yakin ingin menghapus detail ini?',
                                        'method' => 'post',
                                    ],
                                    'title' => 'Hapus'
                                ]
                            );
                        }
                    ],
                    'contentOptions' => ['style' => 'width: 50px; text-align: center;'],
                ],
            ],
        ]); ?>
    </div>
</div>