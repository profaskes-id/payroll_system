<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCutiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-cuti-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_pengajuan_cuti') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'tanggal_pengajuan') ?>

    <?= $form->field($model, 'tanggal_mulai') ?>

    <?= $form->field($model, 'tanggal_selesai') ?>

    <?php // echo $form->field($model, 'alasan_cuti') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'catatan_admin') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
