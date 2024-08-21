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


        <div class="grid gap-6 mb-6 md:grid-cols-12">


            <?php
            $pendidikan = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['jenjang-pendidikan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');

            ?>

            <div class="col-span-12">

                <?= $form->field($model, 'jenjang_pendidikan')->dropDownList($pendidikan, ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', 'prompt' => 'Select Jenjang Pendidikan']) ?>
            </div>
            <div class="col-span-12">
                <?= $form->field($model, 'institusi')->textInput(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', 'maxlength' => true]) ?>
            </div>

            <div class="col-span-12">
                <?= $form->field($model, 'tahun_masuk')->textInput(["class" => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', 'type' => 'number']) ?>
            </div>

            <div class="col-span-12">
                <?= $form->field($model, 'tahun_keluar')->textInput(["class" => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', 'type' => 'number']) ?>
            </div>
        </div>
        <div class="col-span-12">
            <div class="">
                <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
            </div>
        </div>
    </div>





    <?php ActiveForm::end(); ?>

</div>