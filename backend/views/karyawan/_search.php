<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\KaryawanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="karyawan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'kode_karyawan') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'nomer_identitas') ?>

    <?= $form->field($model, 'jenis_identitas') ?>

    <?php // echo $form->field($model, 'kode_jenis_kelamin') ?>

    <?php // echo $form->field($model, 'tempat_lahir') ?>

    <?php // echo $form->field($model, 'tanggal_lahir') ?>

    <?php // echo $form->field($model, 'status_nikah') ?>

    <?php // echo $form->field($model, 'agama') ?>

    <?php // echo $form->field($model, 'suku') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'nomer_telepon') ?>

    <?php // echo $form->field($model, 'foto') ?>

    <?php // echo $form->field($model, 'ktp') ?>

    <?php // echo $form->field($model, 'cv') ?>

    <?php // echo $form->field($model, 'ijazah_terakhir') ?>

    <?php // echo $form->field($model, 'kode_negara') ?>

    <?php // echo $form->field($model, 'kode_provinsi_identitas') ?>

    <?php // echo $form->field($model, 'kode_kabupaten_kota_identitas') ?>

    <?php // echo $form->field($model, 'kode_kecamatan_identitas') ?>

    <?php // echo $form->field($model, 'desa_lurah_identitas') ?>

    <?php // echo $form->field($model, 'alamat_identitas') ?>

    <?php // echo $form->field($model, 'rt_identitas') ?>

    <?php // echo $form->field($model, 'rw_identitas') ?>

    <?php // echo $form->field($model, 'kode_post_identitas') ?>

    <?php // echo $form->field($model, 'is_current_domisili') ?>

    <?php // echo $form->field($model, 'kode_provinsi_domisili') ?>

    <?php // echo $form->field($model, 'kode_kabupaten_kota_domisili') ?>

    <?php // echo $form->field($model, 'kode_kecamatan_domisili') ?>

    <?php // echo $form->field($model, 'desa_lurah_domisili') ?>

    <?php // echo $form->field($model, 'alamat_domisili') ?>

    <?php // echo $form->field($model, 'rt_domisili') ?>

    <?php // echo $form->field($model, 'rw_domisili') ?>

    <?php // echo $form->field($model, 'kode_post_domisili') ?>

    <?php // echo $form->field($model, 'informasi_lain') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
