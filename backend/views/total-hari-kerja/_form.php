<?php

use backend\models\helpers\JamKerjaHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TotalHariKerja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<section class="row">

    <div class="col-md-7 total-hari-kerja-form table-container">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-12">
                <?php
                $data = \yii\helpers\ArrayHelper::map(JamKerjaHelper::getJamKerjaData(), 'id_jam_kerja', 'nama_jam_kerja');
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

            <div class="col-12">
                <?= $form->field($model, 'total_hari')->textInput(['maxlength' => true, 'type' => 'number']) ?>
            </div>


            <div class="col-md-6">
                <?= $form->field($model, 'bulan')->dropDownList([
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ], ['prompt' => 'Pilih Bulan']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'tahun')->dropDownList(
                    array_combine(range(date('Y'), 2100), range(date('Y'), 2100)),
                    ['prompt' => 'Pilih Tahun']
                ) ?> </div>


            <div class="col-12">
                <?= $form->field($model, 'keterangan')->textarea(['rows' => 2, 'maxlength' => true]) ?>

            </div>


            <div class="col-12 col-md-4">
                <?= $form->field($model, 'is_aktif')->radioList(
                    [
                        0 => 'Tidak Aktif',
                        1 => 'Aktif',
                    ], // Daftar opsi
                    ['class' => 'selama', 'itemOptions' => ['labelOptions' => ['style' => 'margin-right: 20px;',]]] // Opsi tambahan, misalnya style
                )->label('Apakah Aktif ') ?>
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
    <div class="col-md-5 table-container p-5" style="overflow-y: scroll; height: 500px">
        <h3>Data Hari Libur Tahun <?= date('Y') ?></h3>
        <?php

        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        ?>
        <?php
        foreach ($holidaysGroupedByMonth as $month => $holidayList) {
            // Menampilkan nama bulan
            $monthName = $months[$month];
            $totalHolidays = count($holidayList); // Hitung total hari libur
        ?>
            <h5>
                <?= $monthName ?>
                <span class="badge badge-primary " style="font-size: 10px; transform: translateY(-5px);"><?= $totalHolidays ?></span> <!-- Badge untuk total hari -->
            </h5>

            <ul>
                <?php
                foreach ($holidayList as $holiday) {
                ?>
                    <li style="margin-top: -3px;">
                        <?php
                        echo date('d-m-Y', strtotime($holiday['tanggal'])) . " - Libur: " . $holiday['nama_hari'] . "<br>";
                        ?>
                    </li>
                <?php
                }
                ?>
            </ul>
        <?php
        }
        ?>
    </div>

</section>