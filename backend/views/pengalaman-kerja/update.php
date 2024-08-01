<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengalamanKerja $model */

$this->title = 'Update Pengalaman Kerja: ' . $model->id_pengalaman_kerja;
$this->params['breadcrumbs'][] = ['label' => 'Pengalaman Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_pengalaman_kerja, 'url' => ['view', 'id_pengalaman_kerja' => $model->id_pengalaman_kerja]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pengalaman-kerja-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>