<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaKaryawan $model */

$this->title = 'Update Jam Kerja Karyawan' ;
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerja Karyawan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_jam_kerja_karyawan, 'url' => ['view', 'id_jam_kerja_karyawan' => $model->id_jam_kerja_karyawan]];
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