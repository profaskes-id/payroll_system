<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JadwalKerja $model */

$this->title = 'Update Jadwal Kerja ';
$this->params['breadcrumbs'][] = ['label' => 'Jadwal kerja', 'url' => ['jam-kerja/view', 'id_jam_kerja' => $model->id_jam_kerja]];
$this->params['breadcrumbs'][] = ['label' => $model->jamKerja->nama_jam_kerja, 'url' => ['view', 'id_jadwal_kerja' => $model->id_jadwal_kerja]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jadwal-kerja-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['jam-kerja/view', 'id_jam_kerja' => $model->id_jam_kerja], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>