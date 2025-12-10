<?php

use amnah\yii2\user\models\User;
use backend\models\Tanggal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCuti $model */

$this->title = 'Pengajuan Cuti : ' . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Cuti', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengajuan-cuti-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Tanggapi', ['update', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti], [
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
                    'label' => 'Nama',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'label' => 'Jenis Cuti',
                    'value' => function ($model) {
                        return $model->jenisCuti->jenis_cuti;
                    }
                ],
                [
                    'label' => 'Diajukan Pada',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal_pengajuan);
                        // return date('d-m-Y', strtotime($model->tanggal_pengajuan));
                    }
                ],

                'alasan_cuti:ntext',
                [
                    'attribute' => 'Ditanggapi Oleh',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Menuggu Tanggapan</span>';
                        }
                        $nama = User::findOne(['id' => $model->ditanggapi_oleh]);

                        return  $nama->username ?? '<span class="text-danger">User Tidak Terdaftar</span>';
                    },
                    "format" => 'raw',
                ],
                [
                    'attribute' => 'Ditanggapi Pada',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Menuggu Tanggapan</span>';
                        }
                        if ($model->ditanggapi_pada) {
                            return date('d-m-Y', strtotime($model->ditanggapi_pada)) ?? '<span class="text-danger">-</span>';
                        } else {
                            return '<span class="text-danger">-</span>';
                        }
                    },
                    "format" => 'raw',
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'status',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Menuggu Tanggapan</span>';
                        } elseif ($model->status == 1) {
                            return '<span class="text-success">Pengajuan Telah Disetujui</span>';
                        } elseif ($model->status == 2) {
                            return '<span class="text-danger">Pengajuan  Ditolak</span>';
                        } else {
                            return "<span class='text-danger'>master kode tidak aktif</span>";
                        }
                    },
                ],
            ],
        ]); ?>


        <h4 class="mt-4">Detail Cuti</h4>

        <?= GridView::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $model->detailCuti, // ganti sesuai data yang ingin ditampilkan
                'pagination' => false,
            ]),
            'tableOptions' => ['class' => 'table table-striped table-bordered'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'tanggal',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDate($model->tanggal, 'php:d M Y');
                    },
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                ],

                [
                    'attribute' => 'keterangan',
                    'value' => function ($model) {
                        return $model->keterangan ?? '-';
                    },
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: left;'],
                ],

                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $status = (int) $model->status;
                        return match ($status) {
                            0 => "<span class='text-warning'>Pending</span>",
                            1 => "<span class='text-success'>Disetujui</span>",
                            2 => "<span class='text-danger'>Ditolak</span>",
                            default => "<span class='text-secondary'>Tidak Diketahui</span>",
                        };
                    },
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            return Html::a(
                                '<i class="fas fa-trash"></i>',
                                ['delete-detail', 'id' => $model->id_detail_cuti, 'id_pengajuan_cuti' => $model->id_pengajuan_cuti],
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