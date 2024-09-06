<?php

use backend\models\JamKerja;
use backend\models\Karyawan;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaKaryawan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jam-kerja-karyawan-form table-container">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(Karyawan::find()->all(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Karyawan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Karyawan');
            ?>
        </div>
        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(JamKerja::find()->all(), 'id_jam_kerja', 'nama_jam_kerja');
            echo $form->field($model, 'id_jam_kerja')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Jam Kerja ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Jam Kerja');
            ?>
        </div>

        <div class="col-md-6">
            <?php
            $jenisShift = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['jenis-shift']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'jenis_shift')->widget(Select2::classname(), [
                'data' => $jenisShift,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Jam Kerja ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Jenis Shift');
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