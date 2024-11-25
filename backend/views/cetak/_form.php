<?php

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Cetak $model */
/** @var yii\widgets\ActiveForm $form */
?>


<style>
    tr td:first-child {
        width: 150px;
    }
</style>

<div class="row">

    <div class=' col-lg-6 table-container mb-3 mb-lg-0'>
        <table class="w-100 table table-responsive">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td> <strong><?= $karyawan->nama ?> </strong></td>
            </tr>
            <tr>
                <td>Nomor Identitas</td>
                <td>:</td>
                <td> <?= $karyawan->nomer_identitas ?></td>
            </tr>
            <tr>
                <td>Jenis Identitas</td>
                <td>:</td>
                <td> <?= $karyawan->jenisidentitas->nama_kode ?></td>
            </tr>
            <tr>
                <td>Status Nikah</td>
                <td>:</td>
                <td> <?= $karyawan->statusNikah->nama_kode ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td> <?= $karyawan->kode_jenis_kelamin == 'L' || $karyawan->kode_jenis_kelamin == 1 ? 'Laki-Laki' : 'Perempuan' ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>
                    <?php if ($karyawan->is_current_domisili == 1) :  ?>
                        <?= $karyawan['alamat_identitas'] . ', ' . $karyawan['desa_lurah_identitas'] . ', ' . $karyawan->kecamatanidentitas->nama_kec, ', ' . $karyawan->kabupatenidentitas->nama_kab . ', ' . $karyawan->provinsiidentitas->nama_prop ?> <br>
                    <?php else :  ?>
                        <?= $karyawan['alamat_domisili'] . ' ' . $karyawan['desa_lurah_domisili'] . ', ' . $karyawan->kecamatandomisili->nama_kec, ', ' . $karyawan->kabupatendomisili->nama_kab . ', ' . $karyawan->provinsidomisili->nama_prop ?> <br>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="cetak-form  table-container col-lg-6  p-3">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-12">
            </div>

            <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => "{$karyawan->id_karyawan}"])->label(false) ?>

            <div class="col-12">
                <?= $form->field($model, 'id_data_pekerjaan')->hiddenInput(['value' => "{$pekerjaan->id_data_pekerjaan}"])->label(false) ?>
            </div>


            <div class="col-12">
                <?= $form->field($model, 'nomor_surat')->textInput(['maxlength' => true]) ?>
            </div>


            <div class="col-12">
                <?= $form->field($model, 'nama_penanda_tangan')->textInput(['maxlength' => true, 'placeholder' => 'Nama', 'value' => $perusahaan->direktur]) ?>
            </div>


            <div class="col-12">
                <?= $form->field($model, 'jabatan_penanda_tangan')->textInput(['maxlength' => true, 'placeholder' => 'Jabatan', 'value' => 'Direktur Utama']) ?>
            </div>

            <div class="col-12">
                <?php
                $tanggal = new Tanggal();
                ?>
                <?= $form->field($model, 'tempat_dan_tanggal_surat')->textInput(['maxlength' => true, 'placeholder' => 'Tempat dan Tanggal', 'value' =>    implode(' ', array_slice(explode(' ', $perusahaan->kabupatenPerusahaan->nama_kab), 1)) . ', ' .  $tanggal->getIndonesiaFormatTanggal($pekerjaan->dari)]) ?>
            </div>




            <div class="form-group">
                <button class="add-button" type="submit">
                    <span>
                        Save
                    </span>
                </button>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>