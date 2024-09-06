<?php

use amnah\yii2\user\models\User;
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
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class='table-container'>
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Tanggapi', ['update', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti], [
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
                'id_pengajuan_cuti',
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
                        return date('d-m-Y', strtotime($model->tanggal_pengajuan));
                    }
                ],
                [
                    'label' => 'Tanggal Mulai Cuti',
                    'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->tanggal_mulai));
                    }
                ],
                [
                    'label' => 'Tanggal Selesai Cuti',
                    'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->tanggal_selesai));
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

    </div>
</div>