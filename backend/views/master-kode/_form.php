<?php

use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\MasterKode $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="master-kode-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->all(), 'nama_group', 'nama_group');
            echo $form->field($model, 'nama_group')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Masukan Nama Group ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-6">
            <?= $form->field($model, 'kode')->textInput(['maxlength' => true, 'placeholder' => ' Kode Group']) ?>
        </div>

        <div class="col-4">
            <?= $form->field($model, 'nama_kode')->textInput(['maxlength' => true, 'placeholder' => 'Masukan Nama Kode']) ?>
        </div>
        <div class="col-4">
            <?= $form->field($model, 'urutan')->textInput(['maxlength' => true, 'type' => 'number', 'placeholder' => 'Masukan Urutan']) ?>
        </div>

        <div class="col-4">
            <?= $form->field($model, 'status')->dropDownList([
                0 => 'Inactive',
                1 => 'Active',
            ], ['prompt' => 'Select status']) ?>
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