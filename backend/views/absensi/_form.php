<?php

use kartik\select2\Select2;
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
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\Karyawan::find()->all(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Karyawan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\JamKerja::find()->all(), 'id_jam_kerja', 'nama_jam_kerja');
            echo $form->field($model, 'id_jam_kerja')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Jam Kerja ...'],
                'pluginOptions' => [
                    // 'tags' => true,
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tanggal')->textInput(['type' => 'date']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'hari')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jam_masuk')->textInput(['type' => 'time']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jam_pulang')->textInput(['type' => 'time']) ?>
        </div>

        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\MasterKode::find()->where(['nama_group' => 'status-hadir'])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'kode_status_hadir')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Status Kehadiran ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
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