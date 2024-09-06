<?php

use backend\models\Perusahaan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Bagian $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="bagian-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nama_bagian')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(Perusahaan::find()->all(), 'id_perusahaan', 'nama_perusahaan');
            echo $form->field($model, 'id_perusahaan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Perusahaan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Perusahaan');
            ?>
        </div>
    </div>


    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Submit
            </span>
        </button>
    </div>
    <?php ActiveForm::end(); ?>
</div>