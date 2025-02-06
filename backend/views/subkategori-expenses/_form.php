<?php

use backend\models\KategoriExpenses;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\SubkategoriExpenses $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="subkategori-expenses-form table-container">

    <?php $form = ActiveForm::begin(); ?>


    <div class="col-12 ">
        <?php
        $data = \yii\helpers\ArrayHelper::map(KategoriExpenses::find()->orderBy(['nama_kategori' => SORT_ASC])->all(), 'id_kategori_expenses', 'nama_kategori');
        echo $form->field($model, 'id_kategori_expenses')->widget(Select2::classname(), [
            'data' => $data,
            'language' => 'id',
            'options' => ['placeholder' => 'Pilih Status Pernikahan ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Kategori');
        ?>
    </div>


    <?= $form->field($model, 'nama_subkategori')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>


    <?php ActiveForm::end(); ?>

</div>