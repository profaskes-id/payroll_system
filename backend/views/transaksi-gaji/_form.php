<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGaji $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="transaksi-gaji-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nomer_identitas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bagian')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jabatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jam_kerja')->textInput() ?>

    <?= $form->field($model, 'status_karyawan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'periode_gaji_bulan')->textInput() ?>

    <?= $form->field($model, 'periode_gaji_tahun')->textInput() ?>

    <?= $form->field($model, 'jumlah_hari_kerja')->textInput() ?>

    <?= $form->field($model, 'jumlah_hadir')->textInput() ?>

    <?= $form->field($model, 'jumlah_sakit')->textInput() ?>

    <?= $form->field($model, 'jumlah_wfh')->textInput() ?>

    <?= $form->field($model, 'jumlah_cuti')->textInput() ?>

    <?= $form->field($model, 'gaji_pokok')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jumlah_jam_lembur')->textInput() ?>

    <?= $form->field($model, 'lembur_perjam')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_lembur')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jumlah_tunjangan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jumlah_potongan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'potongan_wfh_hari')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jumlah_potongan_wfh')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gaji_diterima')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
