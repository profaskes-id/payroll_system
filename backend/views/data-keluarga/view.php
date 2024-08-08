<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\DataKeluarga $model */

$this->title = "Data Keluarga: " . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Data keluarga', 'url' => ['/karyawan/view', 'id_karyawan' => $model->id_karyawan]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="data-keluarga-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['/karyawan/view', 'id_karyawan' => $model->id_karyawan], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class='table-container'>
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_data_keluarga' => $model->id_data_keluarga,], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_data_keluarga' => $model->id_data_keluarga,], [
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
                'id_data_keluarga',
                [
                    'attribute' => 'id_karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                'nama_anggota_keluarga',
                'hubungan',
                'pekerjaan',
                'tahun_lahir',
            ],
        ]) ?>
    </div>

</div>