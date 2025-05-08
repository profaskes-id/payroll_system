<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanShift $model */

$this->title = Yii::t('app', 'Pengajuan penggantian Shift');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_pengajuan_shift' => $model->id_pengajuan_shift]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="pengajuan-shift-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>