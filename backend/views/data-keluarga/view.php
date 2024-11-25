<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\DataKeluarga $model */

$this->title = "Data Keluarga  " . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Data keluarga', 'url' => ['/karyawan/view', 'id_karyawan' => $model->id_karyawan]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="data-keluarga-view">

    <div class="costume-container">
        <?php
        $id_karyawan = Yii::$app->request->get('id_karyawan');
        ?>
        <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['karyawan/view?id_karyawan=' . $model->id_karyawan], ['class' => 'costume-btn']) ?>
    </div>


    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_data_keluarga' => $model->id_data_keluarga,], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_data_keluarga' => $model->id_data_keluarga,], [
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
                'nama_anggota_keluarga',
                [
                    'label' => 'hubungan',
                    'value' => function ($model) {
                        return $model->jenisHubungan->nama_kode ?? '';
                    }
                ],
                'pekerjaan',
                'tahun_lahir',
            ],
        ]) ?>
    </div>

</div>