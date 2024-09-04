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
    <div class="row">

        <div class="col-12 col-md-3 row align-items-center ">
            <div class="p-1 col-6">
                <?= $form->field($model, 'kode_karyawan')->textInput(['id' => 'kode_karyawan', 'disabled' => true, 'value' => $nextKode ?? $model->kode_karyawan])->label('Kode Karyawan') ?>
            </div>
            <div class="col-6 mt-3">
                <label for="manual_kode">
                    <input type="checkbox" id="manual_kode">
                    <span style="font-size: 12px">Manual Kode</span>
                </label>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'nama')->textInput([])->label('Nama Lengkap') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'nomer_identitas')->textInput(['type' => 'number'])->label('Nomer Identitas') ?>
        </div>
        <div class="col-12 col-md-3">
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\MasterKode::find()->where(['nama_group' => Yii::$app->params['jenis-identitas']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
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
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'kode_jenis_kelamin')->radioList(
                ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['jenis-kelamin']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode')
            ) ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'tempat_lahir')->textInput([])->label('Tempat Lahir') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'tanggal_lahir')->textInput(['type' => 'date'])->label('Tanggal Lahir') ?>
        </div>
        <div class="col-12 col-md-3">
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pernikahan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
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
        <div class="col-12 col-md-3">
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\MasterKode::find()->where(['nama_group' => Yii::$app->params['agama']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
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
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'suku')->input(['class' => 'form-control'])->label('Suku') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'email')->textInput(['type' => 'email'])->label('Email') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'nomer_telepon')->textInput(['type' => 'telp'])->label('Nomer Telepon') ?>
        </div>
        <h1 class="col-12 fw-bold text-uppercase mt-5">Identitas</h1>

        <div class="col-12 col-md-3">
            <?= $form->field($model, 'rt_identitas')->textInput(['value' => '00'])->label('RT') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'rw_identitas')->textInput(['value' => '00'])->label('RW') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'kode_post_identitas')->textInput([])->label('Kode Pos') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'kode_negara')->textInput(['value' => 'indonesia'])->label('Negara') ?>
        </div>

        <!-- !======================================== -->
        <div class="col-md-3">
            <?php
            echo $form->field($model, 'kode_provinsi_identitas')->widget(Select2::classname(), [
                'data' => $dataProvinsi,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Provinsi ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?php
            echo $form->field($model, 'kode_kabupaten_kota_identitas')->widget(Select2::classname(), [
                'data' => $dataKabupaten,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih kabupaten/kota ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?php
            echo $form->field($model, 'kode_kecamatan_identitas')->widget(Select2::classname(), [
                'data' => $dataKecamatan,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih kabupaten/kota ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <!-- !======================================== -->

        <div class="col-12 col-md-3">
            <?= $form->field($model, 'desa_lurah_identitas')->textInput([])->label('Desa/Lurah') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'alamat_identitas')->textarea([])->label('Alamat') ?>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'is_current_domisili')->checkbox(['id' => 'is_currnetly_domisili'])->label('') ?>
        </div>
        <h1 class="col-12 fw-bold text-uppercase mt-5">domisili</h1>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'rt_domisili')->textInput(['class' => 'domisili form-control'])->label('RT') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'rw_domisili')->textInput(['class' => 'domisili form-control'])->label('RW') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'kode_post_domisili')->textInput(['class' => 'domisili form-control'])->label('Kode Pos') ?>
        </div>


        <!-- !======================================== -->
        <div class="col-md-3">
            <?php
            echo $form->field($model, 'kode_provinsi_domisili')->widget(Select2::classname(), [
                'data' => $dataProvinsi,
                'language' => 'id',
                'options' => ['class' => 'domisili', 'placeholder' => 'Pilih Provinsi ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?php
            echo $form->field($model, 'kode_kabupaten_kota_domisili')->widget(Select2::classname(), [
                'data' => $dataKabupaten,
                'language' => 'id',
                'options' => ['class' => 'domisili', 'placeholder' => 'Pilih kabupaten/kota ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?php

            echo $form->field($model, 'kode_kecamatan_domisili')->widget(Select2::classname(), [
                'data' => $dataKecamatan,
                'language' => 'id',
                'options' => ['class' => 'domisili', 'placeholder' => 'Pilih kabupaten/kota ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <!-- !======================================== -->


        <div class="col-12 col-md-3">
            <?= $form->field($model, 'desa_lurah_domisili')->textInput(['class' => 'domisili form-control'])->label('Desa/Lurah') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'alamat_domisili')->textInput(['class' => 'domisili form-control'])->label('Alamat') ?>
        </div>



        <div class="col-12 col-md-3">
            <?= $form->field($model, 'foto')->fileInput(['class' => 'form-control'])->label('Foto') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'ktp')->fileInput(['class' => 'form-control'])->label('KTP') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'cv')->fileInput(['class' => 'form-control'])->label('CV') ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'ijazah_terakhir')->fileInput(['class' => 'form-control'])->label('Ijazah Terakhir') ?>
        </div>


        <div class="col-12 col-md-3">
            <?= $form->field($model, 'informasi_lain')->textInput([])->label('Informasi Lain') ?>
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


<?php ActiveForm::end(); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>





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