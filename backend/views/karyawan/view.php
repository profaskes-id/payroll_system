<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Karyawan $model */

$this->title = $model->id_karyawan;
$this->params['breadcrumbs'][] = ['label' => 'Karyawans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="karyawan-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'nama_group' => $model->nama_group, 'kode' => $model->kode], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'nama_group' => $model->nama_group, 'kode' => $model->kode], [
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
                'id_karyawan',
                'kode_karyawan',
                'nama',
                'nomer_identitas',
                'jenis_identitas',
                'tanggal_lahir',
                'kode_negara',
                'kode_provinsi',
                'kode_kabupaten_kota',
                'kode_kecamatan',
                'alamat:ntext',
                'kode_jenis_kelamin',
                'email:email',
            ],
        ]) ?>
    </div>

</div>