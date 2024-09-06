<?php

use backend\models\Karyawan;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-dinas-form table-container">

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
            ])->label('Nama');
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tanggal_mulai')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'tanggal_selesai')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'keterangan_perjalanan')->textarea(['rows' => 1]) ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'estimasi_biaya')->textInput(['maxlength' => true, "type" => "number", "step" => "0.01", "min" => "0", "placeholder" => "0.00"]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'biaya_yang_disetujui')->textInput(['maxlength' => true, "type" => "number", "step" => "0.01", "min" => "0", "placeholder" => "0.00",]) ?>
        </div>

        <div class="col-12">
            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'status')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Status Kehadiran ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Status Hadir');
            ?>
        </div>

        <div class="form-group">
            <button class="add-button" type="submit">
                <span>
                    Submit
                </span>
            </button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>