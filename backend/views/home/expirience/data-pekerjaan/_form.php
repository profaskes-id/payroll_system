<?php

use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengalamanKerja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengalaman-kerja-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="mt-10">


        <div class="grid gap-6 mb-6 md:grid-cols-2">


            <div class="col-md-6">
                <?= $form->field($model, 'perusahaan')->textInput(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', 'maxlength' => true])->label('Nama Perusahaan')
                ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'posisi')->textInput(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', 'maxlength' => true]) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'masuk_pada')->textInput(["class" => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', "min" => "1900-01-01", "max" => "2100-12-31", "step" => "year", 'type' => 'date']) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'keluar_pada')->textInput(["class" => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', "min" => "1900-01-01", "max" => "2100-12-31", "step" => "year", 'type' => 'date']) ?>
            </div>
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