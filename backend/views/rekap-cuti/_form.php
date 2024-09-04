<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\RekapCuti $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="rekap-cuti-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_master_cuti')->textInput() ?>

    <?= $form->field($model, 'id_karyawan')->textInput() ?>

    <?= $form->field($model, 'total_hari_terpakai')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
