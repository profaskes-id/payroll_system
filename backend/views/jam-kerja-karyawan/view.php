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
                    'confirm' => 'Apakah Anda Yakin Ingin Menghapus Item Ini ?',
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
                        if ($model->jamKerja->nama_jam_kerja == null) {
                            return '<p class="text-danger">Belum Di Set</p>';
                        }
                        return $model->jamKerja->nama_jam_kerja . ' (' . $model->jamKerja->jenisShift->nama_kode . ')';
                    }
                ],

                [
                    'attribute' => 'Jenis Shift',
                    'value' => function ($model) {
                        $data = $model->shiftKerja($model->id_shift_kerja);
                        if ($data == null) {
                            return '-';
                        }
                        return $data['nama_shift'] . " ({$data['jam_masuk']} - {$data['jam_keluar']})";
                    }
                ],

            ],
        ]) ?>
    </div>

</div>