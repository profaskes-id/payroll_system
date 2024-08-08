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


        <?php $id_karyawan = Yii::$app->request->get('id_karyawan'); ?>
        <?php $tanggal = Yii::$app->request->get('tanggal'); ?>

        <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $id_karyawan ?? $model->id_karyawan])->label(false) ?>


        <?= $form->field($model, 'tanggal')->hiddenInput(['value' => $tanggal])->label(false) ?>


        <div class="col-md-6">
            <?= $form->field($model, 'jam_masuk')->textInput(['type' => 'time', 'value' => '08:00']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jam_pulang')->textInput(['type' => 'time', 'value' => '17:00']) ?>
        </div>
        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\MasterKode::find()->where(['nama_group' => Yii::$app->params['status-hadir']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'kode_status_hadir')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Status Kehadiran ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Status Hadir');
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'keterangan')->textarea(['rows' => 1]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'lampiran')->textInput(["placeholder" => "Lampiran", "class" => "form-control", 'type' => 'file'])->label('Lampiran') ?>
            <!-- <p style="margin-top: -15px; font-size: 14.5px;" class="text-capitalize  text-muted"> Dokumen ini dibutuhkan untuk verifikasi data klinik</p> -->
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