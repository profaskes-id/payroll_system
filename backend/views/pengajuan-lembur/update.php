<?php

use yii\helpers\Html;


$this->title = 'Pengajuan Lembur';
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Lembur', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur]];
$this->params['breadcrumbs'][] = 'Tanggapan';
?>
<div class="pengajuan-lembur-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'poinArray' => $poinArray

    ]) ?>

</div>