<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Karyawan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="karyawan-form table-container">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="com-sm-12 col-6">
            <?= $form->field($model, 'kode_karyawan')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="com-sm-12 col-6">
            <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="com-sm-12 col-6">
            <?= $form->field($model, 'nomer_identitas')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="com-sm-12 col-6">
            <?= $form->field($model, 'jenis_identitas')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="com-sm-12 col-6">
            <?= $form->field($model, 'tanggal_lahir')->textInput(['type' => 'date']) ?>
        </div>
        <div class="com-sm-12 col-6">
            <?= $form->field($model, 'kode_negara')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="com-sm-12 col-6">
            <?= $form->field($model, 'kode_provinsi')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="com-sm-12 col-6">
            <?= $form->field($model, 'kode_kabupaten_kota')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="com-sm-12 col-6">
            <?= $form->field($model, 'kode_kecamatan')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="com-sm-12 col-6">
            <?= $form->field($model, 'alamat')->textarea(['rows' => 6]) ?>
        </div>
        <div class="com-sm-12 col-6">
            <?= $form->field($model, 'kode_jenis_kelamin')->textInput() ?>
        </div>
        <div class="com-sm-12 col-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Submit
            </span>
        </button>
    </div>

    <?php ActiveForm::end(); ?>

</div>