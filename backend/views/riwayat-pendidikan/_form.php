<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPendidikan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="riwayat-pendidikan-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
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
                $data = \yii\helpers\ArrayHelper::map(\backend\models\MasterKode::find()->where(['nama_group' => 'jenjang-penidikan'])->all(), 'kode', 'nama_kode');
                echo $form->field($model, 'jenjang_pendidikan')->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Pilih Jenajng Pedidikan ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'institusi')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'tahun_masuk')->textInput() ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'tahun_keluar')->textInput() ?>
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