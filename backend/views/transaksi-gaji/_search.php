<?php

use backend\models\helpers\KaryawanHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;



// Ambil bulan dan tahun sekarang
$bulanIni = date('m'); // Contoh: 10
$tahunIni = date('Y'); // Contoh: 2025

// Set default value jika belum ada
if ($model->isNewRecord || empty($model->bulan)) {
    $model->bulan = $bulanIni;
}

if ($model->isNewRecord || empty($model->tahun)) {
    $model->tahun = $tahunIni;
}
?>

<?php
// Array bulan dalam format angka => nama
$bulanList = [
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




<?php $form = ActiveForm::begin(['method' => 'get', 'id' => 'my-form',   'action' => ['transaksi-gaji/index']]); ?>
<div class="transaksi-gaji-search row">



    <div class="col-md-3 col-12">
        <?php
        $nama_group = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
        $selectedKaryawan = Yii::$app->request->get('TransaksiGaji')['id_karyawan'] ?? $model->id_karyawan ?? null;

        echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
            'data' => $nama_group,
            'language' => 'id',
            'options' => [
                'placeholder' => 'Cari Karyawan ...',
                'value' => $selectedKaryawan
            ],
            'pluginOptions' => [
                'tags' => true,
                'allowClear' => true
            ],
        ])->label("Karyawan");
        ?>
    </div>




    <div class="col-md-3">
        <?= $form->field($model, 'bulan')->dropDownList(
            $bulanList,
            [
                'prompt' => 'Pilih Bulan',
                'value' => Yii::$app->request->get('TransaksiGaji')['bulan'] ?? $model->bulan ?? date('m'),
            ]
        ) ?>
    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'tahun')->textInput([
            'placeholder' => 'Masukkan Tahun (contoh: 2025)',
            'maxlength' => true,
            'value' => Yii::$app->request->get('TransaksiGaji')['tahun'] ?? $model->tahun ?? date('Y')
        ]) ?>
    </div>



    <div class="mt-4 col-3 d-flex justify-content-start " style="gap: 10px;">
        <div class="">
            <button class="add-button" type="submit">
                <i class="fas fa-search"></i>Search
            </button>
        </div>
        <div class="">
            <a href="/panel/transaksi-gaji">
                <button class="reset-button" type="button">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </a>
        </div>

    </div>
</div>
<?php ActiveForm::end(); ?>