<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JamKerja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jam-kerja-form table-container">




    <?php



    $form = ActiveForm::begin(); ?>


    <div class="row">


        <div class="col-md-6">

            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\JamKerja::find()->all(), 'nama_jam_kerja', 'nama_jam_kerja');
            echo $form->field($model, 'nama_jam_kerja')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Jam Kerja ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\MasterKode::find()->where(['nama_group' => Yii::$app->params['jenis-shift']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'jenis_shift')->widget(Select2::classname(), [
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