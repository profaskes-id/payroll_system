<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JamKerja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jam-kerja-form table-container">

    <?php $form = ActiveForm::begin(); ?>


    <?php
    $data = \yii\helpers\ArrayHelper::map(\backend\models\JamKerja::find()->all(), 'nama_jam_kerja', 'nama_jam_kerja');
    echo $form->field($model, 'nama_jam_kerja')->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'id',
        'options' => ['placeholder' => 'Pilih Jam Kerja ...'],
        'pluginOptions' => [
            'tags' => true,
            'allowClear' => true
        ],
    ]);
    ?>

    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Submit
            </span>
        </button>
    </div>

    <?php ActiveForm::end(); ?>

</div>