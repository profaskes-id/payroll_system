<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JadwalKerja $model */

$this->title = 'Tambah Jadwal Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Jadwal kerja', 'url' => ['jam-kerja/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-kerja-create">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['jam-kerja/index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'id_jam_kerja' => $id_jam_kerja,
        'model' => $model,
    ]) ?>

</div>