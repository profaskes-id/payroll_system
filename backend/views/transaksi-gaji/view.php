<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGaji $model */

$this->title = $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi Gaji', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="transaksi-gaji-view">

    <div class="costume-container">
        <p>
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">

        <p class="d-flex justify-content-start" style="gap: 10px;">
            <?= Html::a('cetak', ['cetak', 'id_transaksi_gaji' => $model->id_transaksi_gaji], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_transaksi_gaji' => $model->id_transaksi_gaji], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Apakah Anda yakin ingin menghapus item ini?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id_karyawan',
                'nama',
                'nama_bagian',
                'jabatan',
                [
                    'attribute' => 'bulan',
                    'value' => function ($model) {
                        return DateTime::createFromFormat('!m', $model->bulan)->format('F');
                    }
                ],
                'tahun',

                'total_absensi',
                'terlambat',
                'total_alfa_range',
                [
                    'attribute' => 'nominal_gaji',
                    'format' => ['decimal', 0],
                ],
                [
                    'attribute' => 'tunjangan_karyawan',
                    'format' => ['decimal', 0],
                ],
                [
                    'attribute' => 'potongan_karyawan',
                    'format' => ['decimal', 0],
                ],
                [
                    'attribute' => 'potongan_terlambat',
                    'format' => ['decimal', 0],
                ],
                [
                    'attribute' => 'potongan_absensi',
                    'format' => ['decimal', 0],
                ],
                'jam_lembur',
                [
                    'attribute' => 'total_pendapatan_lembur',
                    'format' => ['decimal', 0],
                ],
                [
                    'attribute' => 'dinas_luar_belum_terbayar',
                    'format' => ['decimal', 0],
                ],
                'created_at',
                'updated_at',
                'created_by',
                'updated_by',
            ],
        ]) ?>
    </div>
</div>