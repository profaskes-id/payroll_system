<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PembayaranKasbon $model */

$this->title =   $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pembayaran Kasbon', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_pembayaran_kasbon' => $model->id_pembayaran_kasbon]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pembayaran-kasbon-update">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>