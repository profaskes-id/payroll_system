<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinasSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-dinas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_pengajuan_dinas') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'keterangan_perjalanan') ?>

    <?= $form->field($model, 'tanggal_mulai') ?>
    <?= $form->field($model, 'tanggal_selesai') ?>

    <?= $form->field($model, 'estimasi_biaya') ?>

    <?php // echo $form->field($model, 'biaya_yang_disetujui') 
    ?>

    <?php // echo $form->field($model, 'disetujui_oleh') 
    ?>

    <?php // echo $form->field($model, 'disetujui_pada') 
    ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>