<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PeriodeGaji $model */

$this->title = $model->bulan;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periode Gaji'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="periode-gaji-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class="table-container table-responsive">

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'bulan' => $model->bulan, 'tahun' => $model->tahun], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'bulan' => $model->bulan, 'tahun' => $model->tahun], [
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
                'bulan',
                'tahun',
                'tanggal_awal',
                'tanggal_akhir',
                'terima',
            ],
        ]) ?>

    </div>
</div>