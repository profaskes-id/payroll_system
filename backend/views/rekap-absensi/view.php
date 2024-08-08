<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */

$this->title = $model->id_absensi;
$this->params['breadcrumbs'][] = ['label' => 'Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="absensi-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class='table-container'>
        <p class="d-flex justify-content-end " style="gap: 10px;">
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
                'id_karyawan',
                'tanggal',
                'jam_masuk',
                'jam_pulang',
                'kode_status_hadir',
                'keterangan:ntext',
                'lampiran',
            ],
        ]) ?>

    </div>


</div>