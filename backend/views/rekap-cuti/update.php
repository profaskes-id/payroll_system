<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\RekapCuti $model */

$this->title = 'Update Rekap Cuti: ' . $model->id_rekap_cuti;
$this->params['breadcrumbs'][] = ['label' => 'Rekap Cutis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_rekap_cuti, 'url' => ['view', 'id_rekap_cuti' => $model->id_rekap_cuti]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rekap-cuti-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>