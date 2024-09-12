<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\RekapLemburSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-lembur-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_pengajuan_lembur') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'pekerjaan') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'jam_mulai') ?>

    <?php // echo $form->field($model, 'jam_selesai') ?>

    <?php // echo $form->field($model, 'tanggal') ?>

    <?php // echo $form->field($model, 'disetujui_oleh') ?>

    <?php // echo $form->field($model, 'disetujui_pada') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
