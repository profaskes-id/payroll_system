<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\RekapCutiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="rekap-cuti-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_rekap_cuti') ?>

    <?= $form->field($model, 'id_master_cuti') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'total_hari_terpakai') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
