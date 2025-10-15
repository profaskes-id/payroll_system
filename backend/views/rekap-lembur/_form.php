<?php

use backend\models\Karyawan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\RekapLembur $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="rekap-lembur-form table-container">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">


        <div class="col-md-6 col-12">
            <?php
            $data = \yii\helpers\ArrayHelper::map(Karyawan::find()->all(), 'id_karyawan', 'nama');
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


        <div class="col-12 col-md-6">
            <?= $form->field($model, 'tanggal')->textInput(['type' => 'date']) ?>
        </div>


        <div class="col-12 ">
            <?= $form->field($model, 'jam_total')->textInput() ?>
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