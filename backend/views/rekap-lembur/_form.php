<?php

use backend\models\Karyawan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanLembur $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-lembur-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">

        <div class="col-md-6">
            <?php
            $jenisShift = \yii\helpers\ArrayHelper::map(Karyawan::find()->orderBy(['nama' => SORT_ASC])->all(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $jenisShift,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Karyawan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Karyawan');
            ?>
        </div>


        <div class="col-md-6"></div>


        <div class="col-md-6">
            <?= $form->field($model, 'pekerjaan')->textarea(['rows' => 6]) ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'status')->textInput() ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'jam_mulai')->textInput() ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'jam_selesai')->textInput() ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'tanggal')->textInput() ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'disetujui_oleh')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'disetujui_pada')->textInput() ?>
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