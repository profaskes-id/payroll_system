<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\MasterKode $model */
/** @var yii\widgets\ActiveForm $form */

$data = \yii\helpers\ArrayHelper::map(\backend\models\MasterKode::find()->all(), 'nama_group', 'nama_group');
?>

<div class="master-kode-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-4">
            <?= $form->field($model, 'nama_group')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Masukan Nama Group ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-4">
            <?= $form->field($model, 'kode')->textInput(['maxlength' => true, 'placeholder' => ' Kode Group']) ?>
        </div>

        <div class="col-4">
            <?= $form->field($model, 'nama_kode')->textInput(['maxlength' => true, 'placeholder' => 'Masukan Nama Kode']) ?>
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