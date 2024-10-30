<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGaji $model */

$this->title = $model->id_transaksi_gaji;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transaksi Gajis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transaksi-gaji-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id_transaksi_gaji' => $model->id_transaksi_gaji], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id_transaksi_gaji' => $model->id_transaksi_gaji], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
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
