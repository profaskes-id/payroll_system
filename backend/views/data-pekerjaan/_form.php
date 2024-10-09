<?php

use backend\models\Bagian;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\DataPekerjaan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="data-pekerjaan-form table-container">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">

        <?php $id_karyawan = Yii::$app->request->get('id_karyawan'); ?>

        <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $id_karyawan ?? $model->id_karyawan])->label(false) ?>
        <?= $form->field($model, 'is_currenty')->hiddenInput(['id' => 'is_currenty', 'value' => 1])->label(false) ?>



        <div class="col-4   ">
            <?php
            $data = \yii\helpers\ArrayHelper::map(Bagian::find()->all(), 'id_bagian', 'nama_bagian');
            echo $form->field($model, 'id_bagian')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Bagian ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Bagian');
            ?>
        </div>

        <div class="col-md-4">
            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['jabatan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'jabatan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Jabatan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-4">
            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pekerjaan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'status')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Status Karyawan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Status Karyawan');
            ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'dari')->textInput(['type' => 'date', 'id' => 'dari']) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'selama')->radioList(
                [
                    0 => 'manual',
                    1 => '3 Bulan',
                    2 => '6 Bulan',
                    3 => '1 Tahun',
                ], // Daftar opsi
                ['class' => 'selama', 'itemOptions' => ['labelOptions' => ['style' => 'margin-right: 20px;',]]] // Opsi tambahan, misalnya style
            )->label('Selama ') ?>
        </div>

        <div class="col-md-4 row align-items-center">
            <div class="p-1 col-7">

                <?= $form->field($model, 'sampai')->textInput(['id' => 'kode_sampai', 'type' => 'date', 'disabled' => true])->label('sampai') ?>
            </div>
            <div class="col-5 mt-3">
                <label for="manual_kode">
                    <input type="checkbox" id="manual_kode" checked>
                    <span style="font-size: 12px">Sampai Sekarang</span>
                </label>
            </div>
        </div>


        <div class="col-12">
            <?= $form->field($model, 'surat_lamaran_pekerjaan')->fileInput(['class' => 'form-control'])->label('Surat Lamaran Pekerjaan <sup>(opsional)<sup>') ?>

        </div>
        <div class="col-md-6 ">
            <?= $form->field($model, 'gaji_pokok')->textInput(['id' => 'gaji_pokok',  'class' => 'form-control'])->label('Gaji Pokok Karyawan') ?>
        </div>
        <div class="col-md-6 ">
            <?= $form->field($model, 'terbilang')->textInput(['id' => 'terbilang', 'class' => 'form-control'])->label('Terbilang') ?>
        </div>


    </div>


    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>

    <?php ActiveForm::end(); ?>

</div>



<script>
    const gajiPokokInput = document.querySelector('#gaji_pokok');
    const terbilangInput = document.querySelector('#terbilang');

    gajiPokokInput.addEventListener('input', (e) => {
        const value = e.target.value.replace(/[^\d]/g, ''); // remove non-numeric characters
        const formattedValue = formatCurrency(value); // format as Indonesian currency
        e.target.value = formattedValue;

        const terbilangValue = convertToTerbilang(value); // convert to text representation
        terbilangInput.value = terbilangValue;
    });

    function formatCurrency(value) {
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // add dots as thousand separators
    }

    function convertToTerbilang(value) {
        const numbers = [
            '', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'
        ];
        const levels = ['', 'Ribu', 'Juta', 'Milyar', 'Triliun'];

        let result = '';
        let level = 0;
        let temp = value;

        while (temp > 0) {
            const remainder = temp % 1000;
            temp = Math.floor(temp / 1000);
            result = convertToTerbilangLevel(remainder) + ' ' + levels[level] + ' ' + result;
            level++;
        }

        return result.trim();
    }

    function convertToTerbilangLevel(value) {
        if (value === 0) return '';
        if (value === 1) return 'Se';

        let result = '';
        const hundreds = Math.floor(value / 100);
        value %= 100;
        const tens = Math.floor(value / 10);
        value %= 10;

        if (hundreds > 0) {
            result += numbers[hundreds] + 'ratus ';
        }

        if (tens > 1) {
            result += numbers[tens] + 'puluh ';
        } else if (tens === 1) {
            result += 'Sepuluh ';
        }

        if (value > 0) {
            result += numbers[value];
        }

        return result.trim();
    }
</script>
<script>
    const manual_kode = document.querySelector('#manual_kode');
    const kode_sampai = document.querySelector('#kode_sampai');
    const is_currenty = document.querySelector('#is_currenty');
    const sampai = document.querySelector('.selama');
    const selama = Array.from(document.querySelectorAll('input[name="DataPekerjaan[selama]"]'));
    const dari = document.querySelector('#dari');

    function addMonthOrYear(dateString, month, year) {
        // Parse the input date string into a Date object
        const date = new Date(dateString);

        // Add the specified month(s)
        date.setMonth(date.getMonth() + month);

        // Add the specified year(s) if provided
        if (year) {
            date.setFullYear(date.getFullYear() + year);
        }

        // Return the resulting date as a string in the format "YYYY-MM-DD"
        return `${date.getFullYear()}-${padZero(date.getMonth() + 1)}-${padZero(date.getDate())}`;
    }

    // Helper function to pad a single digit with a zero
    function padZero(num) {
        return (num < 10 ? '0' : '') + num;
    }

    manual_kode.addEventListener('click', () => {
        kode_sampai.disabled = kode_sampai.disabled ? false : true;
        is_currenty.value = kode_sampai.disabled ? 1 : 0;
        kode_sampai.value = '';
        selama.map((item) => {
            item.disabled = item.disabled ? false : true;
            // item.value = '0';
        });

    });

    sampai.addEventListener('change', (e) => {
        console.info()
        if (e.target.value == 0) {
            kode_sampai.value = '';
        } else if (e.target.value == 1) {
            const result = addMonthOrYear(dari.value, 3);
            kode_sampai.value = result;
        } else if (e.target.value == 2) {
            const result = addMonthOrYear(dari.value, 6);
            kode_sampai.value = result;
        } else if (e.target.value == 3) {
            const result = addMonthOrYear(dari.value, 0, 1); // Output: "2025-06-02"
            kode_sampai.value = result;
        }
        is_currenty.value = kode_sampai.disabled ? 1 : 0;
        manual_kode.checked = false;
        kode_sampai.disabled = false;
    });
</script>