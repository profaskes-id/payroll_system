<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\MasterGaji $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-gaji-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <?php
        $id_karyawan = Yii::$app->request->get('id_karyawan');
        ?>
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

        <div class="col-12">
            <?= $form->field($model, 'nominal_gaji')->textInput(['maxlength' => true, 'type' => 'number', 'class' => 'form-control nominal-gaji']) ?>
        </div>


        <div class="col-12">
            <?= $form->field($model, 'visibility')->radioList(
                [
                    0 => 'Tidak',
                    1 => 'Iya',
                ],
                [
                    'class' => 'selama',
                    'itemOptions' => ['labelOptions' => ['style' => 'margin-right: 20px;']]
                ]
            )->label('Tampilkan di menu penggajian') ?>
        </div>

        <div class="form-group">
            <button class="add-button" type="submit">
                <span>
                    Save
                </span>
            </button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>