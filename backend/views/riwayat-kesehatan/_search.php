<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatKesehatanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="riwayat-kesehatan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_riwayat_kesehatan') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'nama_pengecekan') ?>

    <?= $form->field($model, 'keterangan') ?>

    <?= $form->field($model, 'surat_dokter') ?>

    <?php // echo $form->field($model, 'tanggal') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
