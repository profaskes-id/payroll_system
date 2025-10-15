<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\RekapLembur $model */



$this->title = $model->karyawan->nama . ' (' . date('d-m-Y', strtotime($model->tanggal)) . ')';



$this->params['breadcrumbs'][] = ['label' => 'Rekap Lembur', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="rekap-lembur-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Tanggapi', ['update', 'id_rekap_lembur' => $model->id_rekap_lembur], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_rekap_lembur' => $model->id_rekap_lembur], [
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
                    'label' => 'Karyawan',
                    'value'  => function ($model) {
                        return $model->karyawan->nama;
                    },
                ],
                'tanggal',
                'jam_total',
            ],
        ]) ?>

    </div>
</div>