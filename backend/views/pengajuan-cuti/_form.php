<?php

use backend\models\Karyawan;
use backend\models\MasterCuti;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCuti $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-cuti-form table-container">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-12">
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
        <div class="col-12">
            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterCuti::find()->all(), 'id_master_cuti', 'jenis_cuti');
            echo $form->field($model, 'jenis_cuti')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih jenis Cuti ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Jenis Cuti');
            ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'tanggal_mulai')->textInput(['type' => 'date']) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'tanggal_selesai')->textInput(['type' => 'date']) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'alasan_cuti')->textarea(['rows' => 2, 'placeholder' => 'Alasan Cuti Karyawan']) ?>
        </div>



        <?php if (!$model->isNewRecord): ?>
            <div class="col-12">
                <?= $form->field($model, 'catatan_admin')->textarea(['rows' => 2]) ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <button class="add-button" type="submit">
                <span>
                    Submit
                </span>
            </button>
        </div>

    </div>


    <?php ActiveForm::end(); ?>

</div>