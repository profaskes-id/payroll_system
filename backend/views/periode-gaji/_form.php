<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PeriodeGaji $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="periode-gaji-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bulan')->textInput() ?>

    <?= $form->field($model, 'tahun')->textInput() ?>

    <?= $form->field($model, 'tanggal_awal')->textInput() ?>

    <?= $form->field($model, 'tanggal_akhir')->textInput() ?>

    <?= $form->field($model, 'terima')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
