<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengalamanKerja $model */

$this->title = $model->karyawan->nama . ' (' . $model->perusahaan . ')';
$this->params['breadcrumbs'][] = ['label' => 'Pengalaman kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengalaman-kerja-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['karyawan/view', 'id_karyawan' => $model->id_karyawan], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <div class="table-container table-responsive">

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_pengalaman_kerja' => $model->id_pengalaman_kerja], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_pengalaman_kerja' => $model->id_pengalaman_kerja], [
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
                    'label' => 'Karyawan',
                    'value' => $model->karyawan->nama
                ],
                'perusahaan',
                'posisi',
                [
                    'label' => 'Masuk Pada',
                    'value' => date('d-m-Y', strtotime($model->masuk_pada))
                ],
                [
                    'label' => 'Keluar Pada',
                    'value' => date('d-m-Y', strtotime($model->keluar_pada))
                ],
            ],
        ]) ?>
    </div>

</div>