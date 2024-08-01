<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JadwalKerjaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jadwal-kerja-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_jadwal_kerja') ?>

    <?= $form->field($model, 'id_jam_kerja') ?>

    <?= $form->field($model, 'nama_hari') ?>

    <?= $form->field($model, 'jam_masuk') ?>

    <?= $form->field($model, 'jam_keluar') ?>

    <?php // echo $form->field($model, 'lama_istirahat') ?>

    <?php // echo $form->field($model, 'jumlah_jam') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
