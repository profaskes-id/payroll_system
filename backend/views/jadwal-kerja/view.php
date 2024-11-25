<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\JadwalKerja $model */

$this->title = $model->jamKerja->nama_jam_kerja . ' - ' . $model->getNamaHari($model->nama_hari);
$this->params['breadcrumbs'][] = ['label' => 'Jadwal kerja', 'url' => ['jam-kerja/view', 'id_jam_kerja' => $model->id_jam_kerja]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jadwal-kerja-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['jam-kerja/view', 'id_jam_kerja' => $model->id_jam_kerja], ['class' => 'costume-btn']) ?>
        </p>
    </div>



    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_jadwal_kerja' => $model->id_jadwal_kerja], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_jadwal_kerja' => $model->id_jadwal_kerja], [
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
                    'attribute' => 'jam_kerja',
                    'value' => function ($model) {
                        return $model->jamKerja->nama_jam_kerja;
                    }
                ],
                [
                    'label' => 'nama hari',
                    'value' => function ($model) {
                        return $model->getNamaHari($model->nama_hari);
                    },
                ],
                'jam_masuk',
                'jam_keluar',

                [
                    'label' => 'Jumlah Jam Kerja',
                    'value' => function ($model) {
                        return $model['jumlah_jam'] . " Jam";
                    }
                ],
            ],
        ]) ?>
    </div>

</div>