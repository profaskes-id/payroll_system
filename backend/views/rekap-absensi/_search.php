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

    <form method="GET" action="">
        <div class="row" style="gap: 10px 0;">

            <div class="col-12 col-md-4">
                <label for="tanggal_awal">Tanggal Awal:</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control w-100"
                    value="<?= $tanggal_awal ?>" required>
            </div>

            <div class="col-12 col-md-4">
                <label for="tanggal_akhir">Tanggal Akhir:</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control w-100"
                    value="<?= $tanggal_akhir ?>" required>
            </div>

            <div class="col-12 col-md-4">
                <div class="items-center form-group d-flex w-100 justify-content-around" style="padding-top: 25px;">
                    <button class="add-button" type="submit">
                        <i class="fas fa-search"></i>
                        <span>Search</span>
                    </button>

                    <a class="reset-button" href="<?= \yii\helpers\Url::to(['index']) ?>">
                        <i class="fas fa-undo"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalAwalInput = document.getElementById('tanggal_awal');
        const tanggalAkhirInput = document.getElementById('tanggal_akhir');

        function calculateEndDate() {
            if (tanggalAwalInput.value) {
                const startDate = new Date(tanggalAwalInput.value);
                const endDate = new Date(startDate);

                endDate.setMonth(endDate.getMonth() + 1);
                endDate.setDate(endDate.getDate() - 1);

                const formattedDate = endDate.toISOString().split('T')[0];
                tanggalAkhirInput.value = formattedDate;
            }
        }

        // HANYA hitung ulang jika user mengubah tanggal_awal
        tanggalAwalInput.addEventListener('change', calculateEndDate);
    });
</script>