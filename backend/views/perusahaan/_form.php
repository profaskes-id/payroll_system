<?php

use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Perusahaan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
$dataProvinsi = \yii\helpers\ArrayHelper::map(\backend\models\MasterProp::find()->asArray()->orderBy(['nama_prop' => SORT_ASC])->all(), 'kode_prop', 'nama_prop');

$dataKabupaten = \yii\helpers\ArrayHelper::map(\backend\models\MasterKab::find()->asArray()->orderBy(['nama_kab' => SORT_ASC])->all(), 'kode_kab', 'nama_kab');
?>

<div class="perusahaan-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nama_perusahaan')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?php
            $pendidikan = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-perusahaan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'status_perusahaan')->widget(Select2::classname(), [
                'data' => $pendidikan,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Status Perusahaan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'direktur')->textInput(['maxlength' => true])->label('Nama Direktur') ?>
        </div>
        <div class="col-md-6 ">
            <?= $form->field($model, 'logo')->fileInput(['maxlength' => true, 'class' => 'form-control'])->label('Logo') ?>
        </div>
        <div class="col-12">
            <?php
            echo $form->field($model, 'provinsi')->widget(Select2::classname(), [
                'data' => $dataProvinsi,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Provinsi ...', 'id' => 'provinsi'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Provinsi');
            ?>
        </div>
        <div class="col-12">
            <?php
            echo $form->field($model, 'kabupaten_kota')->widget(Select2::classname(), [
                'data' => $dataKabupaten,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih kabupaten/kota ...', 'id' => 'kabupaten-kota'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Kabupaten/Kota');
            ?>
        </div>
        <div class="col-md-6 ">
            <?= $form->field($model, 'alamat')->textarea(['maxlength' => true, 'row' => 5])->label('Alamat') ?>
        </div>

        <div class="col-md-6 ">
            <?= $form->field($model, 'bidang_perusahaan')->textarea(['maxlength' => true, 'row' => 5, 'class' => 'form-control'])->label('Bidang Usaha') ?>
        </div>
        <div class="col-md-6 ">
            <?= $form->field($model, 'didirikan_pada')->textInput(['maxlength' => true, 'type' => 'date', 'class' => 'form-control'])->label('Didirikan Pada') ?>
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function() {

        $('#provinsi').change(function(e) {


            $('#kabupaten-kota').val();
            e.preventDefault();
            let valueProp = this.value;

            $.ajax({
                url: '/panel/perusahaan/kabupaten',
                type: 'GET',
                data: {
                    id_prop: valueProp
                },
                success: function(data) {
                    console.info(data)
                    let kabupatenSelect = $('#kabupaten-kota');
                    kabupatenSelect.empty(); // Kosongkan pilihan yang ada
                    kabupatenSelect.append('<option></option>'); // Tambahkan opsi kosong untuk allowClear

                    $.each(data, function(key, value) {
                        kabupatenSelect.append('<option value="' + key + '">' + value + '</option>');
                    });

                    kabupatenSelect.trigger('change'); // Trigger change untuk memperbarui Select2
                }
            });
        });
    });
</script>