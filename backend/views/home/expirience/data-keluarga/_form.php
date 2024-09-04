<?php

use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengalamanKerja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengalaman-kerja-form table-container relative">

    <?php $form = ActiveForm::begin(); ?>

    <div class="mt-10 relative ">


        <div class="grid gap-6 mb-6 relative overflow-hidden">
            <div class="col-span-12">
                <?= $form->field($model, 'nama_anggota_keluarga')->textInput(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', 'maxlength' => true])->label('Nama Anggota Keluarga')
                ?>
            </div>
            <div class="col-span-12">
                <?= $form->field($model, 'hubungan')->textInput(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', 'maxlength' => true]) ?>
            </div>

            <div class="col-span-12">
                <?= $form->field($model, 'pekerjaan')->textInput(["class" => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5',]) ?>
            </div>

            <div class="col-span-12">
                <?= $form->field($model, 'tahun_lahir')->textInput(["class" => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', "min" => "1900-01-01", "max" => "2100-12-31", "step" => "year", 'type' => 'number']) ?>
            </div>
            <div class="col-span-12">
                <div class="">
                    <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
                </div>
            </div>
        </div>


    </div>


    <?php ActiveForm::end(); ?>

</div>