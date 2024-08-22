<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanLembur $model */

$this->title = "Pengajuan Lembur";
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Lemburs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengajuan-lembur-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class='table-container'>
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur], [
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
                'id_pengajuan_lembur',
                [
                    'label' => 'karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                'pekerjaan:ntext',
                [
                    'format' => 'raw',
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->statusPengajuan->nama_kode ?? "<span class='text-danger'>master kode tidak aktif</span>";
                    },
                ],
                'jam_mulai',
                'jam_selesai',
                'tanggal',
                'disetujui_oleh',
            ],
        ]) ?>

    </div>
</div>