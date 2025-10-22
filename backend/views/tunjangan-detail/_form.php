<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Tunjangan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Modal;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var backend\models\TunjanganDetail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tunjangan-detail-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?php if ($id_karyawan) : ?>
            <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $id_karyawan,  'maxlength' => true])->label(false) ?>
        <?php else: ?>
            <div class="col-12">
                <?php
                $data = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
                echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Pilih karyawan ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(' Karyawan');
                ?>
            </div>
        <?php endif ?>


        <div class=" col-12">
            <?php $nama_kode = ArrayHelper::map(Tunjangan::find()->asArray()->all(), 'id_tunjangan', 'nama_tunjangan');
            echo $form->field($model, 'id_tunjangan')->widget(Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['id' => 'tunjangan-id-tunjangan', 'placeholder' => 'Cari nama Tunjangan', 'style' => 'margin: 0; padding:0;',],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label('Nama Tunjangan');
            ?>

            <button id="btn-tambah-tunjangan" class="btn btn-link" type="button" style="margin: -10px 0 20px; padding:0px ">
                Buat Tunjangan Baru +
            </button>
        </div>

        <div class="col-12 col-md-6 ">
            <?= $form->field($model, 'tipe_jumlah')->dropDownList(
                ['rupiah' => 'Rupiah (Rp)', 'persen' => 'Persen (%)'],
                ['id' => 'tipe-jumlah', 'prompt' => '-- Pilih Tipe Jumlah --']
            )->label('Tipe Jumlah') ?>
        </div>


        <div class="col-12 col-md-6">
            <?= $form->field($model, 'jumlah')->textInput([
                'maxlength' => true,
                'type' => 'number',
                'id' => 'input-jumlah'
            ])->label("Jumlah <span id='label-satuan' class='text-muted'></span>") ?>
        </div>

        <div class="col-md-6 col-12">
            <?= $form->field($model, 'status')->radioList(
                [
                    0 => 'Tidak Aktif',
                    1 => 'Aktif',
                ],
                [
                    'itemOptions' => ['class' => 'radio-spacing'],
                    'separator' => '',
                ]
            )->label("Status Tunjangan") ?>
        </div>

        <div class="form-group">
            <button class="add-button" type="submit">
                <span>Save</span>
            </button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
// Buat modal untuk form tunjangan baru
Modal::begin([
    'id' => 'modal-tunjangan',
    'title' => 'Buat Tunjangan Baru',
    'size' => Modal::SIZE_LARGE,
    'options' => [
        'tabindex' => false // penting untuk Select2 di dalam modal
    ],
    'closeButton' => [
        'id' => 'close-button',
        'class' => 'btn-close',
    ],
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => false
    ]
]);

$tunjanganModel = new \backend\models\Tunjangan();
$formTunjangan = ActiveForm::begin([
    'id' => 'form-tunjangan',
    'action' => Url::to(['tunjangan/create-ajax']),
    'enableClientValidation' => true,
    'validateOnSubmit' => true,
    'options' => [
        'class' => 'form-horizontal',
        'data-ajax' => 'true'
    ],
]);

?>

<div class="modal-body">
    <?= $formTunjangan->field($tunjanganModel, 'nama_tunjangan')->textInput(['maxlength' => true]) ?>
    <?= $formTunjangan->field($tunjanganModel, 'jumlah')->textInput(['type' => 'number']) ?>
    <?= $formTunjangan->field($tunjanganModel, 'satuan')->dropDownList(
        ['Rp' => 'Rp', '%' => '%'],
        ['prompt' => 'Pilih Satuan']
    ) ?>
</div>

<div class="modal-footer">
    <?= Html::button('Batal', ['class' => 'btn btn-secondary', 'data-bs-dismiss' => 'modal']) ?>
    <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        // Tampilkan modal
        $('#btn-tambah-tunjangan').on('click', function() {
            $('#modal-tunjangan').modal('show');
        });

        // Tangani submit form via AJAX
        $('#form-tunjangan').off('submit').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.info(response);
                    if (response.success) {
                        // Tambahkan ke select2
                        var newOption = new Option(response.text, response.id, true, true);
                        $('#tunjangan-id-tunjangan').append(newOption).trigger('change');

                        form[0].reset();
                        $('#modal-tunjangan').modal('hide');
                    } else {
                        let msg = "Gagal menyimpan:\n";
                        for (let field in response.errors) {
                            msg += `- ${field}: ${response.errors[field].join(", ")}\n`;
                        }
                        // alert(msg);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            });

            return false;
        });

        // Event ketika tunjangan berubah, ambil data jumlah dan satuan
        $('#tunjangan-id-tunjangan').on('change', function() {

            let id = $(this).val();
            if (!id) return;
            // alert(id);
            $.ajax({
                url: '/panel/tunjangan/selected-tunjangan',
                type: 'GET',
                data: {
                    id_tunjangan: id
                },
                success: function(response) {
                    if (response && response.jumlah !== undefined && response.satuan !== undefined) {
                        // Set jumlah

                        let satuanVal = response.satuan === 'Rp' ? 'rupiah' : 'persen';
                        $('#tipe-jumlah').val(satuanVal).trigger('change');

                        $('#input-jumlah').val(response.jumlah);

                        // Set label satuan (Rp atau %)
                        let satuanLabel = response.satuan === '%' ? '(%)' : '(Rp)';
                        $('#label-satuan-tunjangan').text(satuanLabel);
                    }
                },
                error: function(xhr) {
                    console.info(xhr)
                    console.error('Gagal mengambil data tunjangan');
                }
            });
        });
    });
</script>