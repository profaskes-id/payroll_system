<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

// Hitung tanggal default: 1 bulan dari tanggal_pengajuan
$defaultTanggalMulaiPotong = null;

if (!empty($model->tanggal_mulai_potong)) {
    $defaultTanggalMulaiPotong = date('Y-m-d', strtotime('+1 month', strtotime($model->tanggal_mulai_potong)));
}
?>
<div class="pengajuan-kasbon-form table-container">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <!-- Pilih Karyawan -->
        <div class="col-12 col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Karyawan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Karyawan');
            ?>
        </div>





        <!-- Jumlah Kasbon -->
        <div class="col-12 col-md-6">
            <?= $form->field($model, 'jumlah_kasbon')->textInput([
                'maxlength' => true,
                'id' => 'jumlah-kasbon',
                'type' => 'number',
                'placeholder' => 'Masukkan jumlah kasbon (contoh: 10000000)',
                'class' => 'form-control number-input'
            ])->label('Jumlah Kasbon (Rp)') ?>
            <p id="terbilang-jumlah-kasbon" class=" text-muted" style="margin-top:-10px; font-size: 13px;"></p>
        </div>

        <!-- Lama Cicilan -->
        <div class="col-12 col-md-6">
            <?= $form->field($model, 'lama_cicilan')->textInput([
                'id' => 'lama-cicilan',
                'type' => 'number',
                'placeholder' => 'Masukkan lama cicilan (bulan)'
            ])->label('Lama Cicilan (bulan)') ?>
        </div>

        <!-- Angsuran Perbulan -->
        <div class="col-12 col-md-6">
            <?= $form->field($model, 'angsuran_perbulan')->textInput([
                'maxlength' => true,
                'id' => 'angsuran-perbulan',
                'type' => 'number',
                'placeholder' => 'Masukkan angsuran per bulan (contoh: 1000000)',
                'class' => 'form-control number-input'
            ])->label('Angsuran Perbulan (Rp)') ?>
            <p id="terbilang-angsuran-perbulan" class=" text-muted" style="margin-top:-10px; font-size: 13px;"></p>
        </div>

        <!-- Keterangan -->
        <div class="col-12">
            <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>
        </div>


        <?php

        //cek apakah new record
        if (!$model->isNewRecord) : ?>

            <!-- Status Pengajuan -->
            <div class="col-12">
                <?php
                $data = \yii\helpers\ArrayHelper::map(
                    MasterKode::find()
                        ->where(['nama_group' => Yii::$app->params['status-pengajuan']])
                        ->andWhere(['!=', 'status', 0])
                        ->orderBy(['urutan' => SORT_ASC])
                        ->all(),
                    'kode',
                    'nama_kode'
                );

                echo $form->field($model, 'status')->radioList($data, [
                    'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                        if ($model->isNewRecord) {
                            $isChecked = $value == 1 ? true : $checked;
                        } else {
                            $isChecked = $checked;
                        }

                        return Html::radio($name, $isChecked, [
                            'value' => $value,
                            'label' => $label,
                            'labelOptions' => ['class' => 'radio-label mr-4'],
                        ]);
                    },
                ])->label('Status Pengajuan');
                ?>
            </div>

            <!-- Tanggal -->
            <div class="col-12 col-md-6">
                <?= $form->field($model, 'tanggal_pencairan')->input('date') ?>
            </div>
            <div class="col-12 col-md-6">
                <?= $form->field($model, 'tanggal_mulai_potong')->input('date', [
                    'value' => $model->tanggal_mulai_potong ?: $defaultTanggalMulaiPotong,
                ]) ?>
            </div>


        <?php endif ?>

        <!-- Tombol -->
        <div class="form-group col-12">
            <button class="add-button" type="submit">
                <span>Save</span>
            </button>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>


<script src="https://unpkg.com/@develoka/angka-terbilang-js/index.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fields = [{
                id: 'jumlah-kasbon',
                output: 'terbilang-jumlah-kasbon'
            },
            {
                id: 'angsuran-perbulan',
                output: 'terbilang-angsuran-perbulan'
            },
        ];

        function updateTerbilang(input, display) {
            const val = input.value.trim();
            if (!val || isNaN(val)) {
                display.textContent = '';
                return;
            }
            const angka = parseInt(val, 10);
            const teks = angkaTerbilang(angka) + ' rupiah';
            display.textContent = teks.charAt(0).toUpperCase() + teks.slice(1);
        }

        // Loop setiap field
        fields.forEach(({
            id,
            output
        }) => {
            const input = document.getElementById(id);
            const display = document.getElementById(output);

            if (input && display) {
                // Jalankan saat user mengetik
                input.addEventListener('input', () => updateTerbilang(input, display));

                // Jalankan sekali saat halaman pertama kali dimuat (misalnya saat update)
                if (input.value && !isNaN(input.value)) {
                    updateTerbilang(input, display);
                }
            }
        });
    });
</script>