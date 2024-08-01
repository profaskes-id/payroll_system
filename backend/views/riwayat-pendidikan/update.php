<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPendidikan $model */

$this->title = 'Update Riwayat Pendidikan: ' . $model->id_riwayat_pendidikan;
$this->params['breadcrumbs'][] = ['label' => 'Riwayat Pendidikans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_riwayat_pendidikan, 'url' => ['view', 'id_riwayat_pendidikan' => $model->id_riwayat_pendidikan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="riwayat-pendidikan-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>