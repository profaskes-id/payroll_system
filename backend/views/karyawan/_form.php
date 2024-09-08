<?php

use backend\models\MasterKab;
use backend\models\MasterKec;
use backend\models\MasterKode;
use backend\models\MasterProp;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use function PHPSTORM_META\type;

/** @var yii\web\View $this */
/** @var backend\models\Karyawan $model */
/** @var yii\widgets\ActiveForm $form */

$dataProvinsi = \yii\helpers\ArrayHelper::map(\backend\models\MasterProp::find()->orderBy(['nama_prop' => SORT_ASC])->all(), 'kode_prop', 'nama_prop');

$dataKabupaten = \yii\helpers\ArrayHelper::map(\backend\models\MasterKab::find()->orderBy(['nama_kab' => SORT_ASC])->all(), 'kode_kab', 'nama_kab');

$dataKecamatan = \yii\helpers\ArrayHelper::map(\backend\models\MasterKec::find()->orderBy(['nama_kec' => SORT_ASC])->all(), 'kode_kec', 'nama_kec');

?>

<div class="karyawan-form table-container">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row align-items-start ">

        <div class="row align-items-start col-4  p-3">
            <h6 class="text-center col-12">Data Personal</h6>
            <hr class="col-12">
            <div class="col-12  row align-items-start align-items-center justify-items-between ">
                <div class="p-1 col-6">
                    <?= $form->field($model, 'kode_karyawan')->textInput(['id' => 'kode_karyawan', 'readonly' => true, 'value' => $nextKode ?? $model->kode_karyawan])->label('Kode Karyawan') ?>
                </div>
                <div class="col-6 mt-3">
                    <label for="manual_kode">
                        <input type="checkbox" id="manual_kode">
                        <span style="font-size: 12px">Manual Kode</span>
                    </label>
                </div>
            </div>
            <div class="col-12 ">
                <?= $form->field($model, 'nama')->textInput([])->label('Nama Lengkap') ?>
            </div>
            <div class="col-12 ">
                <?= $form->field($model, 'nomer_identitas')->textInput(['type' => 'number'])->label('Nomor Identitas') ?>
            </div>
            <div class="col-12 ">
                <?php
                $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['jenis-identitas']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
                echo $form->field($model, 'jenis_identitas')->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Pilih jenis Identitas ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-12 ">
                <?= $form->field($model, 'kode_jenis_kelamin')->radioList(
                    ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['jenis-kelamin']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode'),
                    ['class' => 'form-check-inline d-flex justify-content-around']
                )->label('Jenis Kelamin') ?>
            </div>
            <div class="col-6 ">
                <?= $form->field($model, 'tempat_lahir')->textInput([])->label('Tempat Lahir') ?>
            </div>
            <div class="col-6 ">
                <?= $form->field($model, 'tanggal_lahir')->textInput(['type' => 'date'])->label('Tanggal Lahir') ?>
            </div>
            <div class="col-12 ">
                <?php
                $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pernikahan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
                echo $form->field($model, 'status_nikah')->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Pilih Status Pernikahan ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-6 ">
                <?php
                $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['agama']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
                echo $form->field($model, 'agama')->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Pilih Agama ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-6 ">
                <?= $form->field($model, 'suku')->input(['class' => 'form-control'])->label('Suku') ?>
            </div>
            <div class="col-12 ">
                <?= $form->field($model, 'email')->textInput(['type' => 'email'])->label('Email') ?>
            </div>
            <div class="col-12 ">
                <?= $form->field($model, 'nomer_telepon')->textInput(['type' => 'telp'])->label('Nomor Telepon') ?>
            </div>
        </div>


        <div class="row align-items-start col-4 p-3">
            <h6 class="text-center col-12">Alamat Sesuai Identitas</h6>
            <hr class="col-12">
            <div class="col-6 ">
                <?= $form->field($model, 'rt_identitas')->textInput(['value' => '00'])->label('RT') ?>
            </div>
            <div class="col-6 ">
                <?= $form->field($model, 'rw_identitas')->textInput(['value' => '00'])->label('RW') ?>
            </div>
            <div class="col-12 ">
                <?= $form->field($model, 'kode_post_identitas')->textInput([])->label('Kode Pos') ?>
            </div>

            <div class="col-12">
                <?php
                echo $form->field($model, 'kode_provinsi_identitas')->widget(Select2::classname(), [
                    'data' => $dataProvinsi,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Pilih Provinsi ...', 'id' => 'provinsi-identitas'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Provinsi');
                ?>
            </div>
            <div class="col-12">
                <?php
                echo $form->field($model, 'kode_kabupaten_kota_identitas')->widget(Select2::classname(), [
                    'data' => $dataKabupaten,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Pilih kabupaten/kota ...', 'id' => 'kode-kabupaten-kota-identitas'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Kabupaten/Kota');
                ?>
            </div>
            <div class="col-12">
                <?php
                echo $form->field($model, 'kode_kecamatan_identitas')->widget(Select2::classname(), [
                    'data' => $dataKecamatan,
                    'language' => 'id',
                    'options' => ['id' => 'kode-kecamatan-identitas', 'placeholder' => 'Pilih kecamatan ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Kecamatan');
                ?>
            </div>
            <div class="col-12 ">
                <?= $form->field($model, 'desa_lurah_identitas')->textInput([])->label('Desa/Lurah') ?>
            </div>
            <div class="col-12 ">
                <?= $form->field($model, 'alamat_identitas')->textarea(['rows' => 12])->label('Alamat') ?>
            </div>
        </div>


        <div class="row align-items-start col-4 p-3">
            <h6 class="text-center col-12">Alamat Sesuai Domisili</h6>
            <hr class="col-12">
            <div class="col-12 text-sm">
                <?= $form->field($model, 'is_current_domisili')->checkbox(['id' => 'is_currnetly_domisili'])->label('') ?>
            </div>

            <div class="col-6 ">
                <?= $form->field($model, 'rt_domisili')->textInput(['class' => ' form-control', 'value' => '00'])->label('RT') ?>
            </div>
            <div class="col-6 ">
                <?= $form->field($model, 'rw_domisili')->textInput(['class' => ' form-control', 'value' => '00'])->label('RW') ?>
            </div>
            <div class="col-12 ">
                <?= $form->field($model, 'kode_post_domisili')->textInput(['class' => ' form-control'])->label('Kode Pos') ?>
            </div>

            <div class="col-12">
                <?php
                echo $form->field($model, 'kode_provinsi_domisili')->widget(Select2::classname(), [
                    'data' => $dataProvinsi,
                    'language' => 'id',
                    'options' => ['id' => 'provinsi-domisili', 'placeholder' => 'Pilih Provinsi ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Provinsi');

                ?>
            </div>
            <div class="col-12">
                <?php
                echo $form->field($model, 'kode_kabupaten_kota_domisili')->widget(Select2::classname(), [
                    'data' => $dataKabupaten,
                    'language' => 'id',
                    'options' => ['id' => 'kode-kabupaten-kota-domisili', 'placeholder' => 'Pilih kabupaten/kota ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Kabupaten/Kota');

                ?>
            </div>
            <div class="col-12">
                <?php

                echo $form->field($model, 'kode_kecamatan_domisili')->widget(Select2::classname(), [
                    'data' => $dataKecamatan,
                    'language' => 'id',
                    'options' => ['id' => 'kode-kecamatan-domisili', 'placeholder' => 'Pilih Kecamatan ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Kecamatan');

                ?>
            </div>

            <div class="col-12 ">
                <?= $form->field($model, 'desa_lurah_domisili')->textInput(['class' => 'form-control'])->label('Desa/Lurah') ?>
            </div>
            <div class="col-12 ">
                <?= $form->field($model, 'alamat_domisili')->textarea(['class' => 'form-control', 'rows' => 5])->label('Alamat') ?>
            </div>
            <div class="col-12 ">
                <?= $form->field($model, 'informasi_lain')->textInput([])->label('Informasi Lain') ?>
            </div>
        </div>



        <div class="row align-items-start col-12 p-3">


            <div class="col-3 ">
                <?= $form->field($model, 'foto')->fileInput(['class' => 'form-control'])->label('Foto') ?>
            </div>
            <div class="col-3 ">
                <?= $form->field($model, 'ktp')->fileInput(['class' => 'form-control'])->label('KTP') ?>
            </div>
            <div class="col-3 ">
                <?= $form->field($model, 'cv')->fileInput(['class' => 'form-control'])->label('CV') ?>
            </div>
            <div class="col-3 ">
                <?= $form->field($model, 'ijazah_terakhir')->fileInput(['class' => 'form-control'])->label('Ijazah Terakhir') ?>
            </div>



        </div>



    </div>
    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Submit
            </span>
        </button>
    </div>
</div>
</div>

<?php ActiveForm::end(); ?>


<script>
    const manual_kode = document.querySelector('#manual_kode');
    const kode_karyawan = document.querySelector('#kode_karyawan');


    manual_kode.addEventListener('click', () => {
        kode_karyawan.disabled = kode_karyawan.disabled ? false : true;
    });

    const is_currnetly_domisili = document.querySelector('#is_currnetly_domisili');
    const domisili = Array.from(document.querySelectorAll('.domisili'));
    window.addEventListener('load', () => {
        if (is_currnetly_domisili.checked) {
            domisili.map((item) => {
                item.disabled = true;
                item.value = '';
            })
        }
    })
    is_currnetly_domisili.addEventListener('click', (e) => {
        let statusCheck = e.target.checked;
        domisili.map((item) => {
            item.value = '';
            item.select = ''
            item.disabled = e.target.checked
        })
    })
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function() {

        $('#provinsi-identitas').change(function(e) {
            $('#kode-kabupaten-kota-identitas').val();
            $('#kode-kecamatan-identitas').val();
            e.preventDefault();
            let valueProp = this.value;

            $.ajax({
                url: '/panel/karyawan/kabupaten',
                type: 'GET',
                data: {
                    id_prop: valueProp
                },
                success: function(data) {
                    let kabupatenSelect = $('#kode-kabupaten-kota-identitas');
                    kabupatenSelect.empty(); // Kosongkan pilihan yang ada
                    kabupatenSelect.append('<option></option>'); // Tambahkan opsi kosong untuk allowClear

                    $.each(data, function(key, value) {
                        kabupatenSelect.append('<option value="' + key + '">' + value + '</option>');
                    });

                    kabupatenSelect.trigger('change'); // Trigger change untuk memperbarui Select2
                }
            });
        });

        // Event handler untuk perubahan kabupaten
        $('#kode-kabupaten-kota-identitas').change(function(e) {
            e.preventDefault();
            $('#kode-kecamatan-identitas').val();
            let valueKabupaten = this.value;

            $.ajax({
                url: '/panel/karyawan/kecamatan',
                type: 'GET',
                data: {
                    id_kabupaten: valueKabupaten
                },
                success: function(data) {
                    let kecamatanSelect = $('#kode-kecamatan-identitas');
                    kecamatanSelect.empty(); // Kosongkan pilihan yang ada
                    kecamatanSelect.append('<option></option>'); // Tambahkan opsi kosong untuk allowClear

                    $.each(data, function(key, value) {
                        kecamatanSelect.append('<option value="' + key + '">' + value + '</option>');
                    });

                    kecamatanSelect.trigger('change'); // Trigger change untuk memperbarui Select2
                }
            });
        });


        $('#provinsi-domisili').change(function(e) {
            $('#kode-kabupaten-kota-domisili').val();
            $('#kode-kecamatan-domisili').val();
            e.preventDefault();
            let valueProp = this.value;

            $.ajax({
                url: '/panel/karyawan/kabupaten',
                type: 'GET',
                data: {
                    id_prop: valueProp
                },
                success: function(data) {
                    console.table(data);

                    let kabupatenSelect = $('#kode-kabupaten-kota-domisili');

                    kabupatenSelect.empty(); // Kosongkan pilihan yang ada
                    kabupatenSelect.append('<option></option>'); // Tambahkan opsi kosong untuk allowClear

                    $.each(data, function(key, value) {
                        kabupatenSelect.append('<option value="' + key + '">' + value + '</option>');
                    });

                    kabupatenSelect.trigger('change'); // Trigger change untuk memperbarui Select2
                }
            });
        });

        // Event handler untuk perubahan kabupaten
        $('#kode-kabupaten-kota-domisili').change(function(e) {
            e.preventDefault();
            $('#kode-kecamatan-domisili').val();
            let valueKabupaten = this.value;

            $.ajax({
                url: '/panel/karyawan/kecamatan',
                type: 'GET',
                data: {
                    id_kabupaten: valueKabupaten
                },
                success: function(data) {
                    let kecamatanSelect = $('#kode-kecamatan-domisili');
                    kecamatanSelect.empty(); // Kosongkan pilihan yang ada
                    kecamatanSelect.append('<option></option>'); // Tambahkan opsi kosong untuk allowClear

                    $.each(data, function(key, value) {
                        kecamatanSelect.append('<option value="' + key + '">' + value + '</option>');
                    });

                    kecamatanSelect.trigger('change'); // Trigger change untuk memperbarui Select2
                }
            });
        });

    });
</script>