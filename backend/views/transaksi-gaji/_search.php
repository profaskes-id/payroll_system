<?php

use backend\models\helpers\KaryawanHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGajiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="transaksi-gaji-search">

    <?php $form = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',   'action' => ['transaksi-gaji/index']]); ?>
    <div class="table-container table-responsive">
        <div class="mb-2 row">

            <div class="col-lg-3 col-12">
                <?php
                // Memastikan data yang digunakan untuk Select2 sesuai dengan kondisi $karyawanID
                if ($karyawanID != null) {
                    $nama_group = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanById($karyawanID), 'id_karyawan', 'nama');
                    $selectedValue = $karyawanID; // Jika $karyawanID ada, set nilai yang dipilih ke karyawanID
                } else {
                    $nama_group = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
                    $selectedValue = null; // Jika $karyawanID tidak ada, tidak ada nilai yang dipilih
                }

                echo $form->field($karyawan, 'id_karyawan')->widget(kartik\select2\Select2::classname(), [
                    'data' => $nama_group,
                    'language' => 'id',
                    'options' => [
                        'placeholder' => 'Cari karyawan ...',
                        'value' => $selectedValue, // Menetapkan nilai yang dipilih sesuai dengan kondisi $karyawanID
                    ],
                    'pluginOptions' => [
                        'tags' => true,
                        'allowClear' => true
                    ],
                ])->label(false);
                ?>

            </div>
            <div class="col-lg-4 col-12">
                <?php

                $nama_group = \yii\helpers\ArrayHelper::map($model->getPeriodeGajidpw(), 'id_periode_gaji', 'tampilan');
                if ($periode_gajiID != null) {
                    $selectedValuePeriode = $periode_gajiID; // Jika $karyawanID ada, set nilai yang dipilih ke karyawanID
                } else {
                    $selectedValuePeriode = null; // Jika $karyawanID tidak ada, tidak ada nilai yang dipilih
                }
                echo $form->field($periode_gaji, 'id_periode_gaji')->widget(kartik\select2\Select2::classname(), [
                    'data' => $nama_group,
                    'language' => 'id',
                    'options' => [
                        'placeholder' => 'Cari Periode Gaji ...',
                        'value' => $selectedValuePeriode, // Menetapkan nilai yang dipilih sesuai dengan kondisi $karyawanID
                    ],
                    'pluginOptions' => [
                        // 'tags' => true,
                        'allowClear' => true
                    ],
                ])->label(false);
                ?>
            </div>


            <div class="col-5 d-flex justify-content-start " style="gap: 10px;">
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
    </div>
    <?php ActiveForm::end(); ?>


</div>