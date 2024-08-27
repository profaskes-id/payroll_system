<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPendidikan $model */

$this->title = 'Update Riwayat Pendidikan ';
$this->params['breadcrumbs'][] = ['label' => 'Riwayat Pendidikans', 'url' => ['karyawan/view', 'id_karyawan' => $model->id_karyawan]];
$this->params['breadcrumbs'][] = ['label' => $model->institusi, 'url' => ['view', 'id_riwayat_pendidikan' => $model->id_riwayat_pendidikan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="riwayat-pendidikan-update">

    <div class="costume-container">

        <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['karyawan/view?id_karyawan=' . $model->id_karyawan], ['class' => 'costume-btn']) ?>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>