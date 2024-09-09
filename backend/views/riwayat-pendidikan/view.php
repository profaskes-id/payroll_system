<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPendidikan $model */

$this->title = $model->karyawan->nama . ' (' . $model->institusi . ')';
$this->params['breadcrumbs'][] = ['label' => 'Riwayat pendidikan', 'url' => ['karyawan/view', 'id_karyawan' => $model->id_karyawan]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="riwayat-pendidikan-view">

    <div class="costume-container">
        <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['karyawan/view?id_karyawan=' . $model->id_karyawan], ['class' => 'costume-btn']) ?>
    </div>

    <div class='table-container'>
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_riwayat_pendidikan' => $model->id_riwayat_pendidikan], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_riwayat_pendidikan' => $model->id_riwayat_pendidikan], [
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
                [
                    'attribute' => 'karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'label' => 'Jenjang Pendidikan',
                    'value' => function ($model) {
                        return $model->jenjangPendidikan->nama_kode;
                    }
                ],
                'institusi',
                'tahun_masuk',
                'tahun_keluar',
            ],
        ]) ?>
    </div>

</div>