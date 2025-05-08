<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\JadwalShift $model */

$this->title = $model->karyawan->nama . ' (' . $model->tanggal . ')';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jadwal Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jadwal-shift-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_jadwal_shift' => $model->id_jadwal_shift], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_jadwal_shift' => $model->id_jadwal_shift], [
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
                    'attribute' => 'id_karyawan',
                    'label' => "Karyawan",
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'attribute' => 'id_shift_kerja',
                    'label' => "Shift Kerja",
                    'value' => function ($model) {
                        return $model->shiftKerja->nama_shift;
                    }
                ],
                'tanggal',
            ],
        ]) ?>

    </div>