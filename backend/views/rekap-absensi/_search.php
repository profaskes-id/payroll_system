<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\AbsensiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="absensi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_absensi') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'tanggal')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'jam_masuk') ?>

    <?= $form->field($model, 'jam_pulang') ?>

    <?php // echo $form->field($model, 'kode_status_hadir') 
    ?>

    <?php // echo $form->field($model, 'keterangan') 
    ?>

    <?php // echo $form->field($model, 'lampiran') 
    ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>