<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Tunjangan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TunjanganDetail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tunjangan-detail-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-12">
            <?php $nama_kode = \yii\helpers\ArrayHelper::map(Tunjangan::find()->asArray()->all(), 'id_tunjangan', 'nama_tunjangan');
            echo $form->field($model, 'id_tunjangan')->widget(Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari nama Tunjangan'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label('Nama Tunjangan');
            ?>
        </div>
        <div class=" col-12">
            <?php $nama_kode = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari nama karyawan'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label("Nama Karyawan");
            ?>
        </div>

        <div class="col-12">

            <?= $form->field($model, 'jumlah')->textInput(['maxlength' => true, 'type' => 'number'])->label("Jumlah Tunjangan <span class='text-danger'>(Rp)</span>") ?>
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