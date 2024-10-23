<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPelatihan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="riwayat-pelatihan-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <?php $id_karyawan = Yii::$app->request->get('id_karyawan'); ?>

    <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $id_karyawan ?? $model->id_karyawan])->label(false) ?>

    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'judul_pelatihan')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'penyelenggara')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'tanggal_mulai')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'tanggal_selesai')->textInput(['type' => 'date']) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'sertifikat')->fileInput(['class' => 'form-control', 'maxlength' => true])->label('Sertifikat') ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'deskripsi')->textarea(['rows' => 1]) ?>
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