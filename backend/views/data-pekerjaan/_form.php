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
            <?= $form->field($model, 'dari')->textInput(['type' => 'date', 'id' => 'dari',  'max' => '2100-12-31'])->label("Mulai Dari") ?>
        </div>

        <div class="col-12 col-md-4">
            <?= $form->field($model, 'selama')->radioList(
                [
                    1 => '3 Bulan',
                    2 => '6 Bulan',
                    3 => '1 Tahun',
                ],
                ['class' => 'selama', 'itemOptions' => ['labelOptions' => ['style' => 'margin-right: 20px;', 'max' => '2100-12-31']]] // Opsi tambahan, misalnya style
            )->label("Selama") ?>
        </div>


        <div class="col-12 col-md-4 row align-items-center">
            <div class="p-1 col-12">
                <?= $form->field($model, 'sampai')->textInput(['id' => 'kode_sampai', 'type' => 'date',])->label("Sampai Dengan ") ?>
            </div>
            <div class="col-5 mt-3">
                <!-- <label for="manual_kode">
                    <input type="checkbox" id="manual_kode" checked>
                    <span style="font-size: 12px">Sampai Sekarang</span>
                </label> -->
            </div>
        </div>


        <div class="col-12 col-md-6">
            <?= $form->field($model, 'surat_lamaran_pekerjaan')->fileInput(['class' => 'form-control'])->label('Surat Lamaran Pekerjaan <sup>(opsional)<sup>') ?>

        </div>

        <div class="col-12 col-md-6 ">
            <?= $form->field($model, 'is_aktif')->radioList(
                [
                    0 => 'Tidak Aktif',
                    1 => 'Aktif',
                ],
                ['class' => 'selama', 'itemOptions' => ['labelOptions' => ['style' => 'margin-right: 20px;',]]] // Opsi tambahan, misalnya style
            )->label('Apakah Aktif ') ?>
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
    const sampai = document.querySelector('.selama');

    sampai.addEventListener('change', (e) => {
        const startDate = new Date(dari.value);


        let endDate;
        if (e.target.value == 1) { // Tambah 3 bulan
            endDate = addMonths(startDate, 3);
        } else if (e.target.value == 2) { // Tambah 6 bulan
            endDate = addMonths(startDate, 6);
        } else if (e.target.value == 3) { // Tambah 1 tahun
            endDate = addMonths(startDate, 12);
        }

        // Menghitung akhir bulan dari endDate
        if (endDate) {
            const month = endDate.getMonth();
            const year = endDate.getFullYear();

            // Menghitung tanggal terakhir di bulan itu
            const lastDateOfMonth = new Date(year, month, 0).getDate();

            // Mengatur kode_sampai sesuai dengan jumlah hari di bulan
            //  console.info(`${year}-${(month).toString().padStart(2, '0')}-${lastDateOfMonth}`);
            kode_sampai.value = `${year}-${(month).toString().padStart(2, '0')}-${lastDateOfMonth}`;
        }
    });

    // Fungsi untuk menambah bulan
    function addMonths(date, months) {
        const newDate = new Date(date);
        // Menyimpan tanggal asli
        const originalDate = newDate.getDate();

        newDate.setMonth(newDate.getMonth() + months);

        // Memastikan tanggal tidak melampaui tanggal terakhir bulan baru
        if (newDate.getDate() < originalDate) {
            newDate.setDate(0); // Mengatur ke tanggal terakhir bulan sebelumnya
        }

        return newDate;
    }
</script>