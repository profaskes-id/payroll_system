<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaKaryawan $model */

$this->title = 'jam kerja ' . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerja Karyawan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jam-kerja-karyawan-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_jam_kerja_karyawan' => $model->id_jam_kerja_karyawan], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_jam_kerja_karyawan' => $model->id_jam_kerja_karyawan], [
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
                    'format' => 'raw',
                    'attribute' => 'jam_kerja',
                    'value' => function ($model) {
                        if ($model->jamKerja->nama_jam_kerja) {
                            return '<p class="text-danger">Belum Di Set</p>';
                        }
                        return $model->jamKerja->nama_jam_kerja . ' (' . $model->jamKerja->jenisShift->nama_kode . ')';
                    }
                ],

                [
                    'attribute' => 'Maximal Terlambat',
                    'value' => function ($model) {
                        return date('H:i', strtotime($model->max_terlambat));
                    }
                ],
            ],
        ]) ?>
    </div>

</div>