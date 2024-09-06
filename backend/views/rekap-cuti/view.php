<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\RekapCuti $model */

$this->title = "Rekap Cuti - " . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Rekap Cutis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="rekap-cuti-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class='table-container'>
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('update', ['update', 'id_rekap_cuti' => $model->id_rekap_cuti], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_rekap_cuti' => $model->id_rekap_cuti], [
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
                'id_rekap_cuti',
                [
                    'label' => 'Master Cuti',
                    'value' => function ($model) {
                        return $model->masterCuti->jenis_cuti;
                    }
                ],
                [
                    'label' => 'Karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'label' => 'Total Hari Terpakai',
                    'value' => function ($model) {
                        return $model->total_hari_terpakai . " Hari";
                    }
                ]
            ],
        ]) ?>

    </div>
</div>