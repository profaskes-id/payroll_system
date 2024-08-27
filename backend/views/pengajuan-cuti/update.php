<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCuti $model */

$this->title = 'Update Pengajuan Cuti: ' . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Cuti', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pengajuan-cuti-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>