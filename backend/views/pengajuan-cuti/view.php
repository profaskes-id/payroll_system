<?php

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
                'tanggal_pengajuan',
                'tanggal_mulai',
                'tanggal_selesai',
                'alasan_cuti:ntext',
                [
                    'format' => 'raw',
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->statusPengajuan->nama_kode ?? "<span class='text-danger'>master kode tidak aktif</span>";
                    },
                ],
                [
                    'label' => 'Tanggapan Admin',
                    'value' => function ($model) {
                        return $model->catatan_admin ?? '-';
                    }
                ],
            ],
        ]); ?>

    </div>
</div>