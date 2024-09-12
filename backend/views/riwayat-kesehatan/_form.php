<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatKesehatan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="riwayat-kesehatan-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <?php $id_karyawan = Yii::$app->request->get('id_karyawan'); ?>

    <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $id_karyawan ?? $model->id_karyawan])->label(false) ?>

    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'nama_pengecekan')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'keterangan')->textarea(['rows' => 1, 'class' => 'form-control']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'surat_dokter')->fileInput(['maxlength' => true, 'class' => 'form-control']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tanggal')->textInput(['type' => 'date', 'class' => 'form-control']) ?>
        </div>
    </div>

    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>

    <?php ActiveForm::end(); ?>

</div>