<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinas $model */

$this->title = 'Pengajuan Dinas: ' . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Dinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]];
$this->params['breadcrumbs'][] = 'Tanggapan';
?>
<div class="pengajuan-dinas-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'detailModels' => $detailModels,
    ]) ?>

</div>