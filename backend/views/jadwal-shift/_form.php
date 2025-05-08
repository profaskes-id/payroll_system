<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\ShiftKerja;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JadwalShift $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jadwal-shift-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-12 ">
            <?php $nama_group = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $nama_group,
                'language' => 'id',
                'options' => [
                    'placeholder' => 'Pilih karyawan ...',
                    'multiple' => true, // ini yang bikin bisa multi pilih
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label('Karyawan');
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tanggal_awal')->input('date', ['id' => 'tanggal-awal']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'tanggal_akhir')->input('date', ['id' => 'tanggal-akhir']) ?>
        </div>

        <div class=" col-12">
            <?php $nama_kode = \yii\helpers\ArrayHelper::map(ShiftKerja::find()->asArray()->all(), 'id_shift_kerja', 'nama_shift');
            echo $form->field($model, 'id_shift_kerja')->widget(Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Nama Shift'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Shift Kerja');
            ?>
        </div>

        <div class="form-group col-12">
            <button class="add-button" type="submit">
                <span>
                    Save
                </span>
            </button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>




<?php
$script = <<<JS
    // Update tanggal akhir otomatis 7 hari setelah tanggal awal
    \$('#tanggal-awal').on('change', function() {
        let inputDate = \$(this).val();
        if (!inputDate) return;

        let tanggalAwal = new Date(inputDate);
        tanggalAwal.setDate(tanggalAwal.getDate() + 7);

        let year = tanggalAwal.getFullYear();
        let month = String(tanggalAwal.getMonth() + 1).padStart(2, '0');
        let day = String(tanggalAwal.getDate()).padStart(2, '0');

        let tanggalAkhir = `\${year}-\${month}-\${day}`;
        \$('#tanggal-akhir').val(tanggalAkhir);
    });
JS;
$this->registerJs($script);
?>