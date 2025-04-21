<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanShiftSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-shift-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_pengajuan_shift') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'id_shift_kerja') ?>

    <?= $form->field($model, 'diajukan_pada') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'ditanggapi_oleh') ?>

    <?php // echo $form->field($model, 'ditanggapi_pada') ?>

    <?php // echo $form->field($model, 'catatan_admin') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
