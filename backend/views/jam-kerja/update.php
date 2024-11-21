<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JamKerja $model */

$this->title = 'Update ' . $model->nama_jam_kerja;
$this->params['breadcrumbs'][] = ['label' => 'Jam kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_jam_kerja, 'url' => ['view', 'id_jam_kerja' => $model->nama_jam_kerja]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jam-kerja-update">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>