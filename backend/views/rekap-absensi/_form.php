<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="absensi-form table-container">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'id_karyawan')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tanggal')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jam_masuk')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jam_pulang')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'kode_status_hadir')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'keterangan')->textarea(['rows' => 1]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'lampiran')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>