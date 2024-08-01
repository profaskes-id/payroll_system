<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JadwalKerja $model */

$this->title = 'Update Jadwal Kerja: ' . $model->id_jadwal_kerja;
$this->params['breadcrumbs'][] = ['label' => 'Jadwal Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_jadwal_kerja, 'url' => ['view', 'id_jadwal_kerja' => $model->id_jadwal_kerja]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jadwal-kerja-update">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>