<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\DataKeluarga $model */

$this->title = $model->id_data_keluarga;
$this->params['breadcrumbs'][] = ['label' => 'Data keluarga', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="data-keluarga-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
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
                'id_karyawan',
                'nama_anggota_keluarga',
                'hubungan',
                'pekerjaan',
                'tahun_lahir',
            ],
        ]) ?>
    </div>

</div>