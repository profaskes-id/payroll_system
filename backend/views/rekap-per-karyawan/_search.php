<?php

use backend\models\Karyawan;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var backend\models\AbsensiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="absensi-search">

    <form method="GET" action="">
        <div class="row" style="gap: 10px 0;">

            <div class="col-md-3">
                <?php


                // Ambil daftar karyawan
                $nama_group = ArrayHelper::map(
                    Karyawan::find()->where(['is_aktif' => 1])->all(),
                    'id_karyawan',
                    function ($model) {
                        return $model['nama'] . ' - ' . $model['kode_karyawan'];
                    }
                );

                // Ambil ID yang dipilih dari GET
                $selected_id = Yii::$app->request->get('id_karyawan');
                ?>

                <label for="id_karyawan">Karyawan:</label>
                <select name="id_karyawan" id="id_karyawan_filter" class="form-control select2" style="width: 100%;">
                    <option value="">Pilih Karyawan...</option>
                    <?php foreach ($nama_group as $kode => $label): ?>
                        <option value="<?= $kode ?>" <?= ($kode == $selected_id) ? 'selected' : '' ?>>
                            <?= Html::encode($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <label for="tanggal_awal">Tanggal Awal:</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control w-100"
                    value="<?= $tanggal_awal ?>" required>
            </div>

            <div class="col-12 col-md-3">
                <label for="tanggal_akhir">Tanggal Akhir:</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control w-100"
                    value="<?= $tanggal_akhir ?>" required>
            </div>

            <div class="col-12 col-md-3">
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

<?php
$this->registerJs(<<<JS
    $('#id_karyawan_filter').select2({
        placeholder: 'Pilih Karyawan...',
        allowClear: true
    });
JS);
?>

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