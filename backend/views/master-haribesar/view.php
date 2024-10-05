<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\MasterHaribesar $model */

$this->title = $model->nama_hari;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Haribesars'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-haribesar-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class="table-container table-responsive">

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'kode' => $model->kode], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'kode' => $model->kode], [
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
                'tanggal',
                'nama_hari:ntext',
                'libur_nasional',
                'pesan_default:ntext',
                'lampiran:ntext',
            ],
        ]) ?>

    </div>