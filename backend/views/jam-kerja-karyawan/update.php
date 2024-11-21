<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaKaryawan $model */

$this->title = 'Update Jam Kerja Karyawan';
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerja Karyawan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_jam_kerja_karyawan' => $model->karyawan->nama]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jam-kerja-karyawan-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>