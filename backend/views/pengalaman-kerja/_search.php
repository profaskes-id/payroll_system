<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengalamanKerjaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengalaman-kerja-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_pengalaman_kerja') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'perusahaan') ?>

    <?= $form->field($model, 'posisi') ?>

    <?= $form->field($model, 'masuk_pada') ?>

    <?php // echo $form->field($model, 'keluar_pada') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
