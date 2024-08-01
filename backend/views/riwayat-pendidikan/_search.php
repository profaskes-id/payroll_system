<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPendidikanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="riwayat-pendidikan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_riwayat_pendidikan') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'jenjang_pendidikan') ?>

    <?= $form->field($model, 'institusi') ?>

    <?= $form->field($model, 'tahun_masuk') ?>

    <?php // echo $form->field($model, 'tahun_keluar') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
