<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\SettinganUmum $model */

$this->title = $model->kode_setting;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settingan Lainnya'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="settingan-umum-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_settingan_umum' => $model->id_settingan_umum], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_settingan_umum' => $model->id_settingan_umum], [
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
                'kode_setting',
                'nama_setting',
                [
                    'label' => "Status",
                    'value' => $model->nilai_setting == 1 ? "Aktif" : "Tidak Aktif",
                ],
                [
                    'label' => "Nilai Setting",
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->ket ?? "";
                    },
                ]
            ],
        ]) ?>

    </div>