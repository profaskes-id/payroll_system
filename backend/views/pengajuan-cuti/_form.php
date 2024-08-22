<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCuti $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-cuti-form table-container">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'id_karyawan')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tanggal_pengajuan')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tanggal_mulai')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tanggal_selesai')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'alasan_cuti')->textarea(['rows' => 1]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'status')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'catatan_admin')->textarea(['rows' => 1]) ?>
        </div>

        <div class="form-group">
            <button class="add-button" type="submit">
                <span>
                    Submit
                </span>
            </button>
        </div>

    </div>


    <?php ActiveForm::end(); ?>

</div>