<?php

use backend\models\JamKerja;
use backend\models\MasterKode;
use backend\models\ShiftKerja;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JadwalKerja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
if ($model->isNewRecord) {
    $jamKerja = JamKerja::find()->where(['id_jam_kerja' => $id_jam_kerja])->one() ?? [];
    if ($jamKerja) {
        $jenisShift = MasterKode::find()->asArray()->where(['nama_group' => 'jenis-shift', 'kode' => $jamKerja['jenis_shift']])->one();
    } else {
        throw new \yii\web\NotFoundHttpException('membutuhkan data jam kerja dan shif yang valid');
    }
} else {
    $jamKerja = JamKerja::find()->where(['id_jam_kerja' => $model->id_jam_kerja])->one() ?? [];
    if ($jamKerja) {
        $jenisShift = MasterKode::find()->asArray()->where(['nama_group' => 'jenis-shift', 'kode' => $jamKerja['jenis_shift']])->one();
    } else {
        throw new \yii\web\NotFoundHttpException('membutuhkan data jam kerja dan shif yang valid');
    }
}


?>







<div class="jadwal-kerja-form table-container">

    <?php
    $hari = [];

    for ($i = 0; $i < 7; $i++) {
        $hari[$i] = $model->getNamaHari($i);
    }


    $form = ActiveForm::begin(); ?>


    <?php if (strtolower($jenisShift['nama_kode']) == "shift"): ?>

        <div class="row">
            <?php $id_jam_kerja = Yii::$app->request->get('id_jam_kerja'); ?>
            <?= $form->field($model, 'id_jam_kerja')->hiddenInput(['value' => $id_jam_kerja ?? $model->id_jam_kerja])->label(false) ?>


            <div class="col-12">
                <?= $form->field($model, 'nama_hari')->dropDownList($hari, ['prompt' => 'Pilih Hari']) ?>
            </div>

            <div class="col-md-6">

                <?php
                $shiftKerja = new ShiftKerja();
                $data = $shiftKerja->getShiftKerjaAll();
                $nama_kode = \yii\helpers\ArrayHelper::map($data, 'id_shift_kerja', 'tampilan');
                echo $form->field($model, 'id_shift_kerja')->widget(Select2::classname(), [
                    'data' => $nama_kode,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Cari nama kode ...'],
                    'pluginOptions' => [
                        'tags' => true,
                        'allowClear' => true
                    ],
                ])->label('Pilih Shift Kerja');
                ?>
            </div>
        </div>

    <?php else: ?>
        <div class="row">

            <?php $id_jam_kerja = Yii::$app->request->get('id_jam_kerja'); ?>


            <?= $form->field($model, 'id_jam_kerja')->hiddenInput(['value' => $id_jam_kerja ?? $model->id_jam_kerja])->label(false) ?>

            <div class="col-md-6">
                <?= $form->field($model, 'nama_hari')->dropDownList($hari, ['prompt' => 'Pilih Hari']) ?>
            </div>

            <div class="col-md-4 col-12">
                <?= $form->field($model, 'is_24jam')->radioList(
                    [
                        0 => 'Tidak',
                        1 => 'Iya',
                    ],
                    [
                        'class' => 'selama',
                        'itemOptions' => ['labelOptions' => ['style' => 'margin-right: 20px;']]
                    ]
                )->label('Apakah Jam Kerja 24 Jam') ?>
            </div>

            <div class="non24 row col-12 ">

                <div class="col-md-6">
                    <?= $form->field($model, 'jam_masuk')->textInput(['type' => 'time', 'value' => '08:00', 'id' => 'jam-masuk']) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'jam_keluar')->textInput(['type' => 'time', 'value' => '17:00', 'id' => 'jam-keluar']) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'mulai_istirahat')->textInput(['type' => 'time', 'value' => '12:00', 'id' => 'mulai-istirahat']) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'berakhir_istirahat')->textInput(['type' => 'time', 'value' => '13:00', 'id' => 'berakhir-istirahat']) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'jumlah_jam')->textInput(['type' => 'number', 'id' => 'jumlah-jam',])->label('Jumlah Jam Kerja') ?>
                </div>
            </div>

        </div>
    <?php endif; ?>







    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>
    <?php ActiveForm::end(); ?>


    <script>
        const is_24jam = document.querySelector('.selama');
        const non24 = document.querySelector('.non24');
        is_24jam.addEventListener('click', (e) => {
            console.info(e.target.value);
            if (e.target.value == 1) {
                non24.classList.add('d-none');
            } else {
                non24.classList.remove('d-none');
            }
        })
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    <script>
        $(document).ready(function() {

            function calculateWorkHours() {
                const jamMasuk = $('#jam-masuk').val();
                const jamKeluar = $('#jam-keluar').val();
                const mulaiIstirahat = $('#mulai-istirahat').val();
                const berakhirIstirahat = $('#berakhir-istirahat').val();

                if (jamMasuk && jamKeluar && mulaiIstirahat && berakhirIstirahat) {
                    const timeToMinutes = (time) => {
                        const [hour, minute] = time.split(':').map(Number);
                        return hour * 60 + minute;
                    };

                    // Calculate minutes
                    const masukMinutes = timeToMinutes(jamMasuk);
                    const keluarMinutes = timeToMinutes(jamKeluar);
                    const mulaiIstirahatMinutes = timeToMinutes(mulaiIstirahat);
                    const berakhirIstirahatMinutes = timeToMinutes(berakhirIstirahat);

                    // Calculate total work minutes excluding break
                    let totalMinutes = (keluarMinutes - masukMinutes) - (berakhirIstirahatMinutes - mulaiIstirahatMinutes);

                    // Handle overnight shifts (if necessary)
                    if (totalMinutes < 0) {
                        totalMinutes += 24 * 60; // Add 24 hours worth of minutes
                    }

                    // Convert minutes to hours and display
                    const totalHours = (totalMinutes / 60).toFixed(2);
                    $('#jumlah-jam').val(Number(totalHours));
                }
            }

            // Add event listeners
            calculateWorkHours();
            $('#jam-masuk, #jam-keluar, #mulai-istirahat, #berakhir-istirahat').on('change', calculateWorkHours);



        });
    </script>

</div>