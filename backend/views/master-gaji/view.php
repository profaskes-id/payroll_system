<?php

use backend\models\Terbilang;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\MasterGaji $model */

$this->title = 'Data Gaji ' . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Gajis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-gaji-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_gaji' => $model->id_gaji], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_gaji' => $model->id_gaji], [
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
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    },
                ],
                [
                    'attribute' => 'nominal_gaji',
                    'format' => 'currency', // Format currency untuk otomatis
                    'contentOptions' => ['style' => 'text-align: left;'], // Align text ke kanan
                ],
                [
                    'attribute' => 'terbilang',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $terbilang = Terbilang::toTerbilang($model->nominal_gaji) . ' Rupiah';
                        return $terbilang;
                    }
                ]
            ],
        ]) ?>

    </div>