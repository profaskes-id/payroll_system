<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JamKerja $model */

$this->title = 'Update Jam Kerja: ' . $model->id_jam_kerja;
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_jam_kerja, 'url' => ['view', 'id_jam_kerja' => $model->id_jam_kerja]];
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