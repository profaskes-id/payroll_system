<?php

use backend\models\Karyawan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\AtasanKaryawan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="atasan-karyawan-form table-container">


    <?php
    $id_karyawan = Yii::$app->request->get('id_karyawan');
    ?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $id_karyawan ?? $model->id_karyawan])->label(false) ?>

    <div class="row">
        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(Karyawan::find()->all(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_atasan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Atasan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Atasan Karyawan');
            ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'status')->radioList([
                0 => 'Inactive',
                1 => 'Active'
            ], [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $checked = $checked ? 'checked' : '';
                    return "<label style='margin-right: 20px;'>
                    <input type='radio' name='{$name}' value='{$value}' {$checked}> 
                    {$label}
                </label>";
                }
            ]) ?>
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