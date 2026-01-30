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



        <div class="col-12">
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

        <div class=" col-12">
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
        <div class="col-12">
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

        <div class="col-12 col-md-4">
            <?= $form->field($model, 'dari')->textInput(['type' => 'date', 'id' => 'dari', 'max' => '2100-12-31'])->label("Mulai Dari") ?>
        </div>

        <div class="col-12 col-md-4">
            <?= $form->field($model, 'selama')->radioList(
                [
                    1 => '3 Bulan',
                    2 => '6 Bulan',
                    3 => '1 Tahun',
                    4 => '2 Tahun', // Ditambahkan opsi 2 tahun
                ],
                ['class' => 'selama', 'itemOptions' => ['labelOptions' => ['style' => 'margin-right: 20px;', 'max' => '2100-12-31']]]
            )->label("Selama") ?>
        </div>


        <div class="col-12 col-md-4 row align-items-center">
            <div class="p-1 col-12">
                <?= $form->field($model, 'sampai')->textInput(['id' => 'kode_sampai', 'type' => 'date'])->label("Sampai Dengan ") ?>
            </div>
        </div>


        <div class="col-12 ">
            <?= $form->field($model, 'surat_lamaran_pekerjaan')->fileInput(['class' => 'form-control'])->label('Surat Lamaran Pekerjaan <sup>(opsional)<sup>') ?>
        </div>

        <div class="col-12 ">
            <?= $form->field($model, 'is_aktif')->radioList(
                [
                    0 => 'Tidak Aktif',
                    1 => 'Aktif',
                ],
                ['class' => 'selama', 'itemOptions' => ['labelOptions' => ['style' => 'margin-right: 20px;']]]
            )->label('Apakah Aktif <p class="text-danger">(Pastikan Hanya boleh 1 Data Pekerjaan Aktif)</p> ') ?>
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
    const kode_sampai = document.querySelector('#kode_sampai');
    const dari = document.querySelector('#dari');
    const selamaRadios = document.querySelectorAll('.selama input[type="radio"]');

    // Fungsi untuk menghitung tanggal akhir berdasarkan pilihan
    function hitungTanggalSampai() {
        if (!dari.value) {
            alert('Silakan isi tanggal "Mulai Dari" terlebih dahulu');
            return;
        }

        const startDate = new Date(dari.value);
        const selectedRadio = document.querySelector('.selama input[type="radio"]:checked');

        if (!selectedRadio) {
            return;
        }

        const value = parseInt(selectedRadio.value);
        let endDate;

        // Menyesuaikan dengan bulan mulai
        if (value === 1) { // 3 bulan
            endDate = addMonths(startDate, 3);
        } else if (value === 2) { // 6 bulan
            endDate = addMonths(startDate, 6);
        } else if (value === 3) { // 1 tahun
            endDate = addMonths(startDate, 12);
        } else if (value === 4) { // 2 tahun
            endDate = addMonths(startDate, 24);
        }

        // Mengatur ke tanggal terakhir bulan
        if (endDate) {
            endDate.setDate(0); // Mengatur ke tanggal terakhir bulan sebelumnya
            const year = endDate.getFullYear();
            const month = String(endDate.getMonth() + 1).padStart(2, '0');
            const day = String(endDate.getDate()).padStart(2, '0');

            kode_sampai.value = `${year}-${month}-${day}`;
        }
    }

    // Menambahkan event listener ke semua radio button
    selamaRadios.forEach(radio => {
        radio.addEventListener('change', hitungTanggalSampai);
    });

    // Juga hitung ketika tanggal "dari" berubah
    dari.addEventListener('change', () => {
        const selectedRadio = document.querySelector('.selama input[type="radio"]:checked');
        if (selectedRadio) {
            hitungTanggalSampai();
        }
    });

    // Fungsi untuk menambah bulan
    function addMonths(date, months) {
        const newDate = new Date(date);
        // Menyimpan tanggal asli
        const originalDate = newDate.getDate();

        // Tambah bulan
        newDate.setMonth(newDate.getMonth() + months);

        // Jika tanggal melebihi akhir bulan baru, set ke akhir bulan
        const tempDate = new Date(newDate.getFullYear(), newDate.getMonth() + 1, 0);
        if (originalDate > tempDate.getDate()) {
            newDate.setDate(tempDate.getDate());
        } else {
            newDate.setDate(originalDate);
        }

        return newDate;
    }
</script>