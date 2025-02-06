<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\TunjanganDetail $model */

$this->title = "Tunjangan " . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tunjangan Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tunjangan-detail-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('update', ['update', 'id_tunjangan_detail' => $model->id_tunjangan_detail], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_tunjangan_detail' => $model->id_tunjangan_detail], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Apakah Anda Yakin Ingin Menghapus Item Ini ?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => "Tunjangan",
                    'value' => function ($model) {
                        return $model->tunjangan->nama_tunjangan;
                    },
                ],
                [
                    'label' => "Karyawan",
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    },
                ],
                'jumlah',
            ],
        ]) ?>

    </div>
</div>