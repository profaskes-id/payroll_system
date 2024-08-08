<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */

$this->title = 'Absensi ' .  $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Absensi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="absensi-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_absensi' => $model->id_absensi], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_absensi' => $model->id_absensi], [
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
                'id_absensi',
                [
                    'attribute' => 'karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'attribute' => 'jam kerja',
                    'value' => function ($model) {
                        return $model->jamKerja->nama_jam_kerja;
                    }
                ],
                'tanggal',
                'hari',
                'jam_masuk',
                'jam_pulang',
                [
                    'label' => 'Status Hadir',
                    'value' => function ($model) {
                        return $model->statusHadir->nama_kode;
                    }
                ],
            ],
        ]) ?>
    </div>


</div>