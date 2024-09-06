<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\MasterCutiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-cuti-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_master_cuti') ?>

    <?= $form->field($model, 'jenis_cuti') ?>

    <?= $form->field($model, 'deskripsi_singkat') ?>

    <?= $form->field($model, 'total_hari_pertahun') ?>

    <?= $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
