<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\ShiftKerja $model */

$this->title = strtoupper($model->nama_shift);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shift Kerja'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="shift-kerja-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_shift_kerja' => $model->id_shift_kerja], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_shift_kerja' => $model->id_shift_kerja], [
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
                'nama_shift',
                'jam_masuk',
                'jam_keluar',
                'mulai_istirahat',
                'berakhir_istirahat',
                'jumlah_jam',
            ],
        ]) ?>

    </div>
</div>