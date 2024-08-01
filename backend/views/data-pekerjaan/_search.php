<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\DataPekerjaanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="data-pekerjaan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_data_pekerjaan') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'id_bagian') ?>

    <?= $form->field($model, 'dari') ?>

    <?= $form->field($model, 'sampai') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'jabatan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
