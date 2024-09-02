<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinas $model */

$this->title = "Pengajuan Dinas " . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Dinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengajuan-dinas-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class='table-container'>
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Tanggapi', ['update', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas], [
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
                'id_pengajuan_dinas',
                [
                    'label' => 'Karyawan',
                    'value' => $model->karyawan->nama
                ],
                'keterangan_perjalanan:ntext',
                'tanggal',
                'estimasi_biaya',
                'biaya_yang_disetujui',
                [
                    'attribute' => 'Disetujui Oleh',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Menuggu Persetujuan</span>';
                        }
                        return $model->user->username ?? '<span class="text-danger">User Tidak Terdaftar</span>';
                    },
                    "format" => 'raw',
                ],
                'disetujui_pada',
                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->statusPengajuan->nama_kode ?? "<span class='text-danger'>master kode tidak aktif</span>";
                    },
                    "format" => 'raw',
                ],

            ],

        ]) ?>

    </div>
</div>