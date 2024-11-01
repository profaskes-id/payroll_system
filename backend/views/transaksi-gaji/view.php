<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGaji $model */

$this->title = $model->id_transaksi_gaji;
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
            <?= Html::a('update', ['update', 'id_transaksi_gaji' => $model->id_transaksi_gaji], ['class' => 'add-button']) ?>
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
            'attributes' => [
                'id_transaksi_gaji',
                'nomer_identitas',
                'nama',
                'bagian',
                'jabatan',
                'jam_kerja',
                'status_karyawan',
                'periode_gaji_bulan',
                'periode_gaji_tahun',
                'jumlah_hari_kerja',
                'jumlah_hadir',
                'jumlah_sakit',
                'jumlah_wfh',
                'jumlah_cuti',
                'gaji_pokok',
                'jumlah_jam_lembur',
                'lembur_perjam',
                'total_lembur',
                'jumlah_tunjangan',
                'jumlah_potongan',
                'potongan_wfh_hari',
                'jumlah_potongan_wfh',
                'gaji_diterima',
            ],
        ]) ?>

    </div>
</div>