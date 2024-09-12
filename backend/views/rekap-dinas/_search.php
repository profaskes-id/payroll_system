<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\RekapDinasSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-dinas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_pengajuan_dinas') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'keterangan_perjalanan') ?>

    <?= $form->field($model, 'tanggal_mulai') ?>

    <?= $form->field($model, 'tanggal_selesai') ?>

    <?php // echo $form->field($model, 'estimasi_biaya') ?>

    <?php // echo $form->field($model, 'biaya_yang_disetujui') ?>

    <?php // echo $form->field($model, 'disetujui_oleh') ?>

    <?php // echo $form->field($model, 'disetujui_pada') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
