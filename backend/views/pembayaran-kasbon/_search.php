<?php

use backend\models\helpers\KaryawanHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PembayaranKasbonSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pembayaran-kasbon-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">

        <div class="col-md-3 col-12">
            <?php $nama_kode = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Karyawan...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label('karyawan');
            ?>
        </div>
        <div class="col-md-3 col-12">
            <?php
            $databulan = [
                '1' => 'Januari',
                '2' => 'Februari',
                '3' => 'Maret',
                '4' => 'April',
                '5' => 'Mei',
                '6' => 'Juni',
                '7' => 'Juli',
                '8' => 'Agustus',
                '9' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember',
            ];
            echo $form->field($model, 'bulan')->widget(Select2::classname(), [
                'data' => $databulan,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Nama Bulan ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label("Bulan");
            ?>
        </div>

        <div class="col-md-3 col-12">
            <?= $form->field($model, 'tahun')->textInput(['type' => 'number', 'maxlength' => true, 'value' => date('Y')]) ?>
        </div>


        <div class="mt-4 col-3">
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


    <?php ActiveForm::end(); ?>

</div>