<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JadwalKerja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jadwal-kerja-form table-container">

    <?php
    $formatter = new \IntlDateFormatter(
        'id_ID',
        \IntlDateFormatter::FULL,
        \IntlDateFormatter::NONE,
        null, // Default timezone
        null, // Default calendar
        'eeee' // Hanya nama hari dalam minggu
    );
    $hari = [];

    for ($i = 0; $i < 7; $i++) {
        $date = new DateTime("Sunday +$i days");
        $hari[$i] = $formatter->format($date);
    }


    $form = ActiveForm::begin(); ?>

    <div class="row">

        <?php $id_jam_kerja = Yii::$app->request->get('id_jam_kerja'); ?>


        <?= $form->field($model, 'id_jam_kerja')->hiddenInput(['value' => $id_jam_kerja ?? $model->id_jam_kerja])->label(false) ?>

        <div class="col-md-6">
            <?= $form->field($model, 'nama_hari')->dropDownList($hari, ['prompt' => 'Pilih Hari']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jam_masuk')->textInput(['type' => 'time', 'value' => '08:00', 'id' => 'jam-masuk']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jam_keluar')->textInput(['type' => 'time', 'value' => '17:00', 'id' => 'jam-keluar']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'mulai_istirahat')->textInput(['type' => 'time', 'value' => '12:00', 'id' => 'mulai-istirahat']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'berakhir_istirahat')->textInput(['type' => 'time', 'value' => '13:00', 'id' => 'berakhir-istirahat']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jumlah_jam')->textInput(['type' => 'number', 'id' => 'jumlah-jam', 'readonly' => true])->label('Jumlah Jam Kerja') ?>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {

            function calculateWorkHours() {
                const jamMasuk = $('#jam-masuk').val();
                const jamKeluar = $('#jam-keluar').val();
                const mulaiIstirahat = $('#mulai-istirahat').val();
                const berakhirIstirahat = $('#berakhir-istirahat').val();

                if (jamMasuk && jamKeluar && mulaiIstirahat && berakhirIstirahat) {
                    const timeToMinutes = (time) => {
                        const [hour, minute] = time.split(':').map(Number);
                        return hour * 60 + minute;
                    };

                    // Calculate minutes
                    const masukMinutes = timeToMinutes(jamMasuk);
                    const keluarMinutes = timeToMinutes(jamKeluar);
                    const mulaiIstirahatMinutes = timeToMinutes(mulaiIstirahat);
                    const berakhirIstirahatMinutes = timeToMinutes(berakhirIstirahat);

                    // Calculate total work minutes excluding break
                    let totalMinutes = (keluarMinutes - masukMinutes) - (berakhirIstirahatMinutes - mulaiIstirahatMinutes);

                    // Handle overnight shifts (if necessary)
                    if (totalMinutes < 0) {
                        totalMinutes += 24 * 60; // Add 24 hours worth of minutes
                    }

                    // Convert minutes to hours and display
                    const totalHours = (totalMinutes / 60).toFixed(2);
                    $('#jumlah-jam').val(Number(totalHours));
                }
            }

            // Add event listeners
            calculateWorkHours();
            $('#jam-masuk, #jam-keluar, #mulai-istirahat, #berakhir-istirahat').on('change', calculateWorkHours);
        });
    </script>

</div>