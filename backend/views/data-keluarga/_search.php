<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\DataKeluargaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="data-keluarga-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_data_keluarga') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'nama_anggota_keluarga') ?>

    <?= $form->field($model, 'hubungan') ?>

    <?= $form->field($model, 'pekerjaan') ?>

    <?php // echo $form->field($model, 'tahun_lahir') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
