<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGajiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="transaksi-gaji-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_transaksi_gaji') ?>

    <?= $form->field($model, 'nomer_identitas') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'bagian') ?>

    <?= $form->field($model, 'jabatan') ?>

    <?php // echo $form->field($model, 'jam_kerja') ?>

    <?php // echo $form->field($model, 'status_karyawan') ?>

    <?php // echo $form->field($model, 'periode_gaji_bulan') ?>

    <?php // echo $form->field($model, 'periode_gaji_tahun') ?>

    <?php // echo $form->field($model, 'jumlah_hari_kerja') ?>

    <?php // echo $form->field($model, 'jumlah_hadir') ?>

    <?php // echo $form->field($model, 'jumlah_sakit') ?>

    <?php // echo $form->field($model, 'jumlah_wfh') ?>

    <?php // echo $form->field($model, 'jumlah_cuti') ?>

    <?php // echo $form->field($model, 'gaji_pokok') ?>

    <?php // echo $form->field($model, 'jumlah_jam_lembur') ?>

    <?php // echo $form->field($model, 'lembur_perjam') ?>

    <?php // echo $form->field($model, 'total_lembur') ?>

    <?php // echo $form->field($model, 'jumlah_tunjangan') ?>

    <?php // echo $form->field($model, 'jumlah_potongan') ?>

    <?php // echo $form->field($model, 'potongan_wfh_hari') ?>

    <?php // echo $form->field($model, 'jumlah_potongan_wfh') ?>

    <?php // echo $form->field($model, 'gaji_diterima') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
