<?php

use backend\models\JamKerja;
use backend\models\Karyawan;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaKaryawan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jam-kerja-karyawan-form table-container">

    <?php
    $id_karyawan = Yii::$app->request->get('id_karyawan');
    ?>

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $id_karyawan ?? $model->id_karyawan])->label(false) ?>
    <div class="row">
        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(JamKerja::find()->all(), 'id_jam_kerja', 'nama_jam_kerja');
            echo $form->field($model, 'id_jam_kerja')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Jam Kerja ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Jam Kerja');
            ?>
        </div>


        <div class="col-12">
            <?= $form->field($model, 'max_terlambat')->textInput(['type' => 'time'])->label('Maximal Terlambat');
            ?>
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