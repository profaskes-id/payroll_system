<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\MasterCuti $model */

$this->title = $model->jenis_cuti;
$this->params['breadcrumbs'][] = ['label' => 'Master Cuti', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-cuti-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_master_cuti' => $model->id_master_cuti], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_master_cuti' => $model->id_master_cuti], [
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
                'jenis_cuti',
                'deskripsi_singkat:ntext',
                'total_hari_pertahun',
                [
                    'attribute' => 'status',
                    'value' => $model->status == 1 ? 'Aktif' : 'Tidak Aktif',
                ]
            ],
        ]) ?>

    </div>
</div>