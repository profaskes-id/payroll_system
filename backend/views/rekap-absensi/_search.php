<?php

use backend\models\Karyawan;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var backend\models\AbsensiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="absensi-search">

    <form method="GET" action=""> <!-- Ganti 'your_action_page.php' dengan halaman yang sesuai -->

        <div class="row " style="gap: 10px 0;">

            <div class="col-6 col-md-5 ">
                <!-- <label for="bulan">Bulan:</label> -->
                <select name="bulan" id="bulan" class="form-control w-100 " style="width: 100%; display: block;">
                    <?php
                    $bulanArray = [
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
                    ];
                    $currentMonth = $bulan; // Mengambil bulan saat ini
                    foreach ($bulanArray as $key => $value) {
                        echo "<option value='{$key}' " . ($key == $currentMonth ? 'selected' : '') . ">{$value}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-6 col-md-4">
                <!-- <label for="tahun">Tahun:</label> -->
                <select name="tahun" id="tahun" class="form-control w-100">
                    <?php
                    $currentYear = $tahun; // Mengambil tahun saat ini
                    for ($year = $currentYear - 5; $year <= $currentYear + 5; $year++) { // Menampilkan 5 tahun ke belakang dan 5 tahun ke depan
                        echo "<option value='{$year}' " . ($year == $currentYear ? 'selected' : '') . ">{$year}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-3">
                <div class="items-center form-group d-flex w-100 justify-content-around">
                    <button class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                        <i class="fas fa-search"></i>
                        <span>
                            Search
                        </span>
                    </button>

                    <a class="reset-button" href="<?= \yii\helpers\Url::to(['index']) ?>">
                        <i class="fas fa-undo"></i>
                        <span>
                            Reset
                        </span>
                    </a>
                </div>
            </div>
        </div>



</div>



</form>