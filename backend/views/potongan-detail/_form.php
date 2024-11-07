<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Potongan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PotonganDetail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="potongan-detail-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-12">
        <?php $nama_kode = \yii\helpers\ArrayHelper::map(Potongan::find()->asArray()->all(), 'id_potongan', 'nama_potongan');
        echo $form->field($model, 'id_potongan')->widget(Select2::classname(), [
            'data' => $nama_kode,
            'language' => 'id',
            'options' => ['placeholder' => 'Cari nama potongan'],
            'pluginOptions' => [
                'tags' => true,
                'allowClear' => true
            ],
        ])->label('Cari Potongan');
        ?>
    </div>
    <div class="col-12">
        <?php $nama_kode = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
        echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [

            'data' => $nama_kode,
            'language' => 'id',
            'options' => ['placeholder' => 'Cari nama karyawan'],
            'pluginOptions' => [
                'tags' => true,
                'allowClear' => true
            ],
        ])->label('Cari Karyawan');
        ?>
    </div>

    <?= $form->field($model, 'jumlah')->textInput(['maxlength' => true, 'type' => 'number'])->label("Jumlah Potongan <span class='text-danger'>(Rp)</span>") ?>


    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>


    <?php ActiveForm::end(); ?>

</div>