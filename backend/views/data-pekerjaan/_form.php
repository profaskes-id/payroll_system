<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\DataPekerjaan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="data-pekerjaan-form table-container">

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
            $data = \yii\helpers\ArrayHelper::map(\backend\models\Bagian::find()->all(), 'id_bagian', 'nama_bagian');
            echo $form->field($model, 'id_bagian')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Bagian ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'dari')->textInput(['type' => 'date']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'sampai')->textInput(['type' => 'date']) ?>
        </div>

        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\MasterKode::find()->where(['nama_group' => 'status-pekerjaan'])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'status')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Status Pekerjaan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jabatan')->textInput(['maxlength' => true]) ?>
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