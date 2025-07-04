<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\SettinganUmum $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="settingan-umum-form tabel-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row table-container">
        <div class="col-md-12">
            <?= $form->field($model, 'kode_setting')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'nama_setting')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'nilai_setting')
                ->dropDownList([
                    0 => 'Tidak Aktif',
                    1 => 'Aktif',
                ], ['prompt' => 'Pilih Status']) ?>

        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'ket')->textarea(['rows' => 3, 'maxlength' => true]) ?>
        </div>


        <div class="form-group">
            <button class="add-button" type="submit">
                <span>
                    Save
                </span>
            </button>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>