<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\RekapLembur $model */

$this->title = 'Update Rekap Lembur: ' . $model->id_rekap_lembur;
$this->params['breadcrumbs'][] = ['label' => 'Rekap Lemburs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_rekap_lembur, 'url' => ['view', 'id_rekap_lembur' => $model->id_rekap_lembur]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rekap-lembur-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>