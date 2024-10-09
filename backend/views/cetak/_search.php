<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\CetakSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cetak-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_cetak') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'id_data_pekerjaan') ?>

    <?= $form->field($model, 'nomor_surat') ?>

    <?= $form->field($model, 'nama_penanda_tangan') ?>

    <?php // echo $form->field($model, 'jabatan_penanda_tangan') ?>

    <?php // echo $form->field($model, 'deskripsi_perusahaan') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
